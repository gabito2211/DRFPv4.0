<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sancion;
use App\Carnet;
use Carbon\Carbon;
use App\Sede;
use App\Bitacora;
use App\HistorialDanoLibro;
use App\Inventario;

class SancionController extends Controller
{
    // Listado de usuarios con sanciones pendientes o resueltas
    public function index(Request $request)
    {
        // =========================================================================
        // 🔓 DESBLOQUEO AUTOMÁTICO DE SANCIONES VENCIDAS
        // =========================================================================
        $sancionesVencidas = Sancion::where('estado', 'pendiente')
            ->whereNotNull('fecha_resolucion')
            ->where('fecha_resolucion', '<=', Carbon::now())
            ->get();

        foreach ($sancionesVencidas as $sancion) {
            // 1. Marcar la sanción como solventada
            $sancion->estado = 'solventado';
            $sancion->observacion_resolucion = 'Desbloqueo automático por cumplimiento de fecha límite.';
            $sancion->save();

            // 2. Verificar si la persona aún tiene otras sanciones pendientes
            $sancionesPendientes = Sancion::where('persona_id', $sancion->persona_id)
                ->where('estado', 'pendiente')
                ->count();

            // 3. Si no tiene más sanciones pendientes, reactivar el carnet
            if ($sancionesPendientes === 0) {
                Carnet::where('persona_id', $sancion->persona_id)
                    ->update(['estado' => 'Activo']);
            }
        }
        // =========================================================================

        $buscar = $request->input('search');

        $sanciones = Sancion::with(['persona', 'prestamo'])
            ->when($buscar, function ($q) use ($buscar) {
                return $q->whereHas('persona', function ($qp) use ($buscar) {
                    $qp->where('cedula', 'LIKE', "%{$buscar}%")
                       ->orWhere('nombre', 'LIKE', "%{$buscar}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('sanciones', compact('sanciones', 'buscar'));
    }

    // Método para restituir el acceso al lector
    public function solventar(Request $request, $id)
    {
        $this->validate($request, [
            'observacion_resolucion' => 'required|string|max:255',
            'tipo_resolucion' => 'nullable|in:reparado,pagado,resuelto',
        ]);

        $sancion = Sancion::findOrFail($id);
        $sancion->estado = 'solventado';
        $sancion->observacion_resolucion = $request->observacion_resolucion;
        $sancion->fecha_resolucion = Carbon::now();
        $sancion->save();

        Bitacora::registrar('update', 'sanciones', "Se solventó la sanción #{$sancion->id}.", null, ['estado' => $sancion->estado]);

        // Actualizar historial de daños del libro si existe
        try {
            $tipoResolucion = $request->input('tipo_resolucion');
            if (empty($tipoResolucion)) {
                $obs = strtolower($request->observacion_resolucion);
                if (strpos($obs, 'repar') !== false) {
                    $tipoResolucion = 'reparado';
                } elseif (strpos($obs, 'pag') !== false || strpos($obs, 'compra') !== false) {
                    $tipoResolucion = 'pagado';
                } else {
                    $tipoResolucion = 'resuelto';
                }
            }

            $historiales = HistorialDanoLibro::where('sancion_id', $sancion->id)
                ->where('estado', 'pendiente')
                ->get();

            foreach ($historiales as $historial) {
                $historial->estado = $tipoResolucion;
                $historial->solucion = $request->observacion_resolucion;
                $historial->fecha_resolucion = Carbon::now();
                if ($tipoResolucion === 'reparado') {
                    $historial->tipo_evento = 'reparacion';
                } elseif ($tipoResolucion === 'pagado') {
                    $historial->tipo_evento = 'pago';
                }
                $historial->save();

                // Si fue reparado y no hay otros daños pendientes, pasar libro a bueno
                if ($tipoResolucion === 'reparado' && $historial->libro_id) {
                    $pendientes = HistorialDanoLibro::where('libro_id', $historial->libro_id)
                        ->where('estado', 'pendiente')
                        ->count();
                    if ($pendientes == 0) {
                        $libro = Inventario::where('cota', $historial->libro_id)->first();
                        if ($libro && $libro->estado === 'deteriorado') {
                            $libro->estado = 'bueno';
                            if ($libro->observaciones && stripos($libro->observaciones, 'deterior') !== false) {
                                // limpiar observaciones de daño si solo era eso? no tocar arbitrariamente
                            }
                            $libro->save();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // No romper el flujo si falla el historial
        }

        // Verificar si la persona aún tiene otras sanciones sin resolver
        $sancionesPendientes = Sancion::where('persona_id', $sancion->persona_id)
            ->where('estado', 'pendiente')
            ->count();

        // Si ya solucionó todo, se reactiva el carnet
        if ($sancionesPendientes === 0) {
            Carnet::where('persona_id', $sancion->persona_id)
                  ->update(['estado' => 'Activo']);

            return back()->with('success', '✅ Sanción solventada con éxito. El carnet del lector se encuentra Activo nuevamente.');
        }

        return back()->with('success', '✅ Sanción actualizada, pero el lector mantiene otras sanciones pendientes.');
    }

    // Devuelve los datos de una sanción en JSON (para el modal de Ver)
    public function datos($id)
    {
        $sancion = Sancion::with('persona')->find($id);

        if (!$sancion) {
            return response()->json(['error' => 'Sanción no encontrada'], 404);
        }

        return response()->json([
            'id'                     => $sancion->id,
            'cedula'                 => $sancion->persona ? $sancion->persona->cedula : 'N/A',
            'lector'                 => $sancion->persona ? $sancion->persona->nombre . ' ' . $sancion->persona->apellido : 'Desconocido',
            'tipo_dano'              => $sancion->tipo_dano,
            'estado'                 => $sancion->estado,
            'observacion_resolucion' => $sancion->observacion_resolucion,
            'fecha_resolucion'       => $sancion->fecha_resolucion
                ? \Carbon\Carbon::parse($sancion->fecha_resolucion)->format('d/m/Y H:i')
                : null,
            'created_at'             => $sancion->created_at
                ? $sancion->created_at->format('d/m/Y H:i')
                : null,
        ]);
    }
}