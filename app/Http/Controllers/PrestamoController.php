<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prestamo;
use App\Inventario;
use App\Carnet;
use App\Persona;
use App\Sancion;
use App\PrestamoInterno;
use Carbon\Carbon;
use App\Sede;
use App\Bitacora;
use App\HistorialDanoLibro;

class PrestamoController extends Controller
{
    /**
     * Muestra la lista de préstamos con buscador y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('search');

        $prestamos = Prestamo::when($buscar, function ($q) use ($buscar) {
            return $q->where(function ($sub) use ($buscar) {
                $sub->where('libro_id', 'LIKE', "%{$buscar}%")
                    ->orWhere('lector_id', 'LIKE', "%{$buscar}%")
                    ->orWhere('estatus', 'LIKE', "%{$buscar}%");
            });
        })->orderBy('id', 'desc')->paginate(10);

        return view('prestamos', compact('prestamos', 'buscar'));
    }

    /**
     * Busca a la persona ya sea por su cédula o por el número de carnet.
     * Se prueba primero por cédula (comportamiento original) y, si no hay
     * coincidencia, se busca el carnet con ese número y se toma su titular.
     */
    private function resolverPersonaPorTermino($termino)
    {
        $termino = trim($termino);

        $persona = Persona::where('cedula', $termino)->first();

        if (!$persona) {
            $carnetBuscado = Carnet::where('num_carnet', $termino)->first();
            if ($carnetBuscado) {
                $persona = Persona::find($carnetBuscado->persona_id);
            }
        }

        return $persona;
    }

    /**
     * Helper privado para evaluar y actualizar el estado del carnet en tiempo real.
     */
    private function actualizarYObtenerCarnet($persona)
    {
        $carnet = Carnet::where('persona_id', $persona->id)->first();

        if (!$carnet) {
            return null;
        }

        $hoy = Carbon::now()->startOfDay();

        // 1. DESBLOQUEO AUTOMÁTICO: ÚNICAMENTE si es bloqueo TEMPORAL por mora y ya pasaron 30 días
        if ($carnet->estado === 'Bloqueado' && $carnet->tipo_bloqueo === 'temporal') {
            $fechaBloqueo = Carbon::parse($carnet->updated_at)->startOfDay();
            $diasSancionado = $hoy->diffInDays($fechaBloqueo);

            if ($diasSancionado >= 30) {
                $carnet->estado = 'Activo';
                $carnet->tipo_bloqueo = null; // Se limpia el motivo del bloqueo
                $carnet->save();
            }
        }

        // 2. BLOQUEO AUTOMÁTICO POR MORA: Si posee algún préstamo PENDIENTE fuera de lapso
        $tienePrestamoVencido = Prestamo::where('lector_id', $persona->cedula)
            ->where('estatus', 'pendiente')
            ->where('fecha_devolucion', '<', $hoy)
            ->exists();

        if ($tienePrestamoVencido && $carnet->estado !== 'Bloqueado') {
            $carnet->estado = 'Bloqueado';
            $carnet->tipo_bloqueo = 'temporal';
            $carnet->save();
        }

        return $carnet;
    }

    /**
     * Registra un nuevo préstamo en el sistema.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'lector_id'          => 'required',
            'libro_id'           => 'required',
            'fecha_salida'       => 'required|date',
            'fecha_devolucion'   => 'required|date|after:fecha_salida',
            'condiciones_salida' => 'nullable|string|max:500', 
        ]);

        $persona = $this->resolverPersonaPorTermino($request->lector_id);
        
        if (!$persona) {
            return back()->withInput()->with('error', '❌ No existe un usuario con esa cédula o número de carnet en el sistema.');
        }
        
        $carnet = $this->actualizarYObtenerCarnet($persona);
        
        if (!$carnet || $carnet->estado !== 'Activo' || Carbon::parse($carnet->fecha_vencimiento)->lt(Carbon::now())) {
            return back()->withInput()->with('error', '❌ El usuario no tiene un carnet activo y vigente.');
        }

        // Verificar existencia del libro
        $libro = Inventario::where('cota', trim($request->libro_id))->first();
        
        if (!$libro) {
            return back()->withInput()->with('error', '❌ El libro no existe en el inventario.');
        }

        // Los libros deteriorados o en almacén no optan al préstamo
        if ($libro->estado === 'deteriorado') {
            return back()->withInput()->with('error', '❌ El libro está DETERIORADO y no puede ser prestado. Debe ser reparado o dado de baja.');
        }

        if ($libro->estado === 'almacen') {
            return back()->withInput()->with('error', '❌ El libro está en ALMACÉN y no está disponible para préstamo directo.');
        }
        
        // Validación 1: Ya prestado a domicilio
        if ($libro->estaPrestadoADomicilio()) {
            return back()->withInput()->with('error', '❌ El libro no está disponible porque tiene un préstamo a domicilio pendiente.');
        }

        // Validación 2: Prestado actualmente en Sala
        if ($libro->estaPrestadoEnSala()) {
            return back()->withInput()->with('error', '❌ El libro no está disponible para domicilio porque actualmente se encuentra prestado en sala.');
        }

        $data = $request->all();
        // Se guarda siempre la cédula del lector (aunque el registro se haya
        // buscado por número de carnet), ya que el resto del sistema
        // (mora, sanciones, bloqueo de carnet) asume que lector_id = cédula.
        $data['lector_id'] = $persona->cedula;
        $data['estatus'] = 'pendiente';

        // Aplicar el límite de días del libro si está definido
        if ($libro->dias_prestamo) {
            $fechaSalida = Carbon::parse($request->fecha_salida);
            $fechaDevolucionMaxima = $fechaSalida->copy()->addDays($libro->dias_prestamo);

            if (Carbon::parse($request->fecha_devolucion)->gt($fechaDevolucionMaxima)) {
                $data['fecha_devolucion'] = $fechaDevolucionMaxima->format('Y-m-d');
            }
        }
        
        $prestamo = Prestamo::create($data);

        Bitacora::registrar('create', 'prestamos', "Se registró un préstamo para el lector con cédula {$prestamo->lector_id}.", null, ['lector_id' => $prestamo->lector_id, 'libro_id' => $prestamo->libro_id, 'fecha_salida' => $prestamo->fecha_salida]);

        return redirect('/prestamo')->with('success', '✅ Préstamo registrado correctamente.');
    }

    /**
     * Edita los datos de un préstamo registrado.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'lector_id'           => 'required',
            'libro_id'            => 'required',
            'fecha_salida'        => 'required|date',
            'fecha_devolucion'    => 'required|date|after:fecha_salida',
            'condiciones_salida'  => 'nullable|string|max:500',  
            'condiciones_regreso' => 'nullable|string|max:500',  
            'estatus'             => 'required|in:pendiente,entregado',
        ]);

        $prestamo = Prestamo::findOrFail($id);
        $persona = $this->resolverPersonaPorTermino($request->lector_id);
        
        if (!$persona) {
            return back()->with('error', '❌ No existe un usuario con esa cédula o número de carnet en el sistema.');
        }
        
        $carnet = $this->actualizarYObtenerCarnet($persona);
        
        if (!$carnet || $carnet->estado !== 'Activo' || Carbon::parse($carnet->fecha_vencimiento)->lt(Carbon::now())) {
            return back()->with('error', '❌ El usuario no tiene un carnet activo y vigente.');
        }
        
        if ($request->libro_id != $prestamo->libro_id) {
            $libro = Inventario::where('cota', trim($request->libro_id))->first();
            
            if (!$libro) {
                return back()->with('error', '❌ El libro no existe en el inventario.');
            }

            if ($libro->estado === 'deteriorado') {
                return back()->with('error', '❌ El libro está DETERIORADO y no puede ser prestado.');
            }

            if ($libro->estado === 'almacen') {
                return back()->with('error', '❌ El libro está en ALMACÉN y no puede ser prestado.');
            }
            
            if ($libro->estaPrestadoADomicilio()) {
                return back()->with('error', '❌ El libro no está disponible porque tiene un préstamo a domicilio pendiente.');
            }

            if ($libro->estaPrestadoEnSala()) {
                return back()->with('error', '❌ El libro no está disponible porque se encuentra en sala.');
            }
        }
        
        $datosActualizar = $request->all();
        $datosActualizar['lector_id'] = $persona->cedula;

        // Aplicar el límite de días si el libro lo tiene definido
        $libroActual = Inventario::where('cota', trim($request->libro_id))->first();
        if ($libroActual && $libroActual->dias_prestamo) {
            $fechaSalida = Carbon::parse($request->fecha_salida);
            $fechaDevolucionMaxima = $fechaSalida->copy()->addDays($libroActual->dias_prestamo);

            if (Carbon::parse($request->fecha_devolucion)->gt($fechaDevolucionMaxima)) {
                $datosActualizar['fecha_devolucion'] = $fechaDevolucionMaxima->format('Y-m-d');
            }
        }

        $prestamo->update($datosActualizar);

        Bitacora::registrar('update', 'prestamos', "Se actualizó el préstamo #{$prestamo->id}.", null, ['lector_id' => $prestamo->lector_id, 'libro_id' => $prestamo->libro_id, 'estatus' => $prestamo->estatus]);

        return redirect('/prestamo')->with('success', '✅ Registro de préstamo actualizado con éxito.');
    }

    /**
     * Procesa la devolución/entrega de un libro y aplica restricciones al carnet según el caso.
     */
    public function entregar(Request $request, $id)
    {
        $this->validate($request, [
            'estado_libro'        => 'required|in:bueno,deteriorado',
            'condiciones_regreso' => 'nullable|string|max:500',
        ]);

        $prestamo = Prestamo::findOrFail($id);

        if ($prestamo->estatus === 'entregado') {
            return back()->with('error', '❌ Este préstamo ya ha sido registrado como entregado.');
        }

        $detalleCondicion = ($request->input('estado_libro') === 'deteriorado')
            ? ($request->input('condiciones_regreso') ?: 'Libro devuelto en mal estado / deteriorado')
            : 'Buen estado';

        $prestamo->estatus = 'entregado';
        $prestamo->condiciones_regreso = $detalleCondicion;
        $prestamo->save();

        Bitacora::registrar('update', 'prestamos', "Se entregó el préstamo #{$prestamo->id}.");

        $persona = Persona::where('cedula', $prestamo->lector_id)->first();

        // 🔴 CASO 1: ENTREGA CON DETERIORO -> SANCIÓN Y BLOQUEO DEFINITIVO
        if ($request->input('estado_libro') === 'deteriorado') {
            if ($persona) {
                $sancion = Sancion::create([
                    'prestamo_id'      => $prestamo->id,
                    'persona_id'       => $persona->id,
                    'tipo_dano'        => 'Deterioro de libro: ' . $detalleCondicion,
                    'estado'           => 'pendiente',
                    'fecha_resolucion' => null
                ]);

                // Registrar en historial de daños del libro
                try {
                    $libro = Inventario::where('cota', $prestamo->libro_id)->first();
                    HistorialDanoLibro::create([
                        'libro_id' => $prestamo->libro_id,
                        'prestamo_id' => $prestamo->id,
                        'sancion_id' => $sancion->id,
                        'persona_id' => $persona->id,
                        'tipo_evento' => 'dano',
                        'estado' => 'pendiente',
                        'descripcion' => 'Deterioro de libro: ' . $detalleCondicion,
                        'fecha_evento' => Carbon::now(),
                    ]);
                    if ($libro) {
                        $libro->estado = 'deteriorado';
                        if (!$libro->observaciones) {
                            $libro->observaciones = $detalleCondicion;
                        }
                        $libro->save();
                    }
                } catch (\Exception $e) {
                    // Continuar aunque falle el historial
                }

                Carnet::where('persona_id', $persona->id)->update([
                    'estado'       => 'Bloqueado',
                    'tipo_bloqueo' => 'definitivo' // NO SE DESBLOQUEA AUTOMÁTICAMENTE
                ]);
            }

            return redirect('/prestamo')->with('warning', '⚠️ Devolución procesada. El libro se entregó deteriorado y el carnet ha sido BLOQUEADO INDEFINIDAMENTE.');
        }

        // 🟡 CASO 2: ENTREGA CON RETRASO (MOROSIDAD) -> BLOQUEO TEMPORAL (30 DÍAS)
        $fechaDevolucionEstimada = Carbon::parse($prestamo->fecha_devolucion)->startOfDay();
        $fechaHoy = Carbon::now()->startOfDay();

        if ($fechaHoy->gt($fechaDevolucionEstimada)) {
            $diasAtraso = $fechaHoy->diffInDays($fechaDevolucionEstimada);

            if ($persona) {
                Sancion::create([
                    'prestamo_id' => $prestamo->id,
                    'persona_id'  => $persona->id,
                    'tipo_dano'   => "Entrega tardía con {$diasAtraso} día(s) de retraso.",
                    'estado'      => 'pendiente'
                ]);

                Carnet::where('persona_id', $persona->id)->update([
                    'estado'       => 'Bloqueado',
                    'tipo_bloqueo' => 'temporal' // SE DESBLOQUEA SOLO A LOS 30 DÍAS
                ]);

                return redirect('/prestamo')->with('warning', "⚠️ Devolución procesada con {$diasAtraso} día(s) de retraso. El carnet se mantendrá bloqueado durante 30 días.");
            }
        }

        // 🟢 CASO 3: ENTREGADO A TIEMPO Y EN BUEN ESTADO
        return redirect('/prestamo')->with('success', '✅ Libro entregado correctamente a tiempo.');
    }

    /**
     * Elimina un registro de préstamo (solo si ya no está pendiente).
     */
    public function destroy($id)
    {
        $prestamo = Prestamo::findOrFail($id);
        
        if ($prestamo->estatus == 'pendiente') {
            return back()->with('error', '❌ No se puede eliminar un préstamo pendiente. Primero debe registrar la entrega del libro.');
        }

        $datosAntes = $prestamo->toArray();
        $prestamo->delete();

        Bitacora::registrar('delete', 'prestamos', "Se eliminó el préstamo #{$prestamo->id}.", $datosAntes, null);

        return back()->with('success', '✅ Registro eliminado correctamente.');
    }

    /**
     * Retorna datos del préstamo para el modal de edición.
     */
    public function edit($id)
    {
        $prestamo = Prestamo::findOrFail($id);
        return response()->json($prestamo);
    }

    /**
     * Endpoint AJAX para verificar validez y bloqueo del carnet por cédula.
     */
    public function verificarLectorPorCedula($termino)
    {
        try {
            $persona = $this->resolverPersonaPorTermino($termino);
            
            if (!$persona) {
                return response()->json([
                    'valido' => false,
                    'mensaje' => '❌ No existe un usuario con esa cédula o número de carnet en el sistema.'
                ]);
            }
            
            $carnet = $this->actualizarYObtenerCarnet($persona);
            
            if (!$carnet) {
                return response()->json([
                    'valido' => false,
                    'mensaje' => '❌ El usuario no tiene un carnet registrado.'
                ]);
            }

            // EVALUACIÓN DE BLOQUEOS
            if ($carnet->estado === 'Bloqueado') {
                // Si es un bloqueo definitivo por deterioro
                if ($carnet->tipo_bloqueo === 'definitivo') {
                    return response()->json([
                        'valido' => false,
                        'mensaje' => '❌ Carnet BLOQUEADO INDEFINIDAMENTE por daño/deterioro de libro. Diríjase a administración.',
                        'hasta' => 'Requiere desbloqueo manual del Administrador'
                    ]);
                }

                // Si es un bloqueo temporal por mora
                $fechaBloqueo = Carbon::parse($carnet->updated_at)->startOfDay();
                $diasTranscurridos = Carbon::now()->startOfDay()->diffInDays($fechaBloqueo);
                $diasRestantes = max(0, 30 - $diasTranscurridos);

                return response()->json([
                    'valido' => false,
                    'mensaje' => "❌ Carnet BLOQUEADO por mora. Días restantes de sanción: {$diasRestantes} día(s).",
                    'hasta' => $fechaBloqueo->addDays(30)->format('d/m/Y')
                ]);
            }

            if (Carbon::parse($carnet->fecha_vencimiento)->lt(Carbon::now())) {
                return response()->json([
                    'valido' => false,
                    'mensaje' => '❌ El carnet del usuario se encuentra vencido.'
                ]);
            }
            
            return response()->json([
                'valido' => true,
                'mensaje' => '✅ Carnet válido y activo',
                'nombre' => $persona->nombre . ' ' . $persona->apellido,
                'profesion' => isset($carnet->profesion) ? $carnet->profesion : 'Lector',
                'num_carnet' => $carnet->num_carnet,
                'fecha_vencimiento' => $carnet->fecha_vencimiento
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valido' => false,
                'mensaje' => '❌ Error en la verificación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alias de compatibilidad por si el JavaScript AJAX llama a la ruta anterior.
     */
    public function verificarLector($cedula)
    {
        return $this->verificarLectorPorCedula($cedula);
    }
}