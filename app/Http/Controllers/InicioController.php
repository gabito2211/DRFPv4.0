<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use App\Inventario;
use App\PrestamoInterno;
use App\Prestamo;
use Carbon\Carbon;
use App\Sede;
use App\Bitacora;
use Illuminate\Support\Facades\DB;

class InicioController extends Controller
{
    public function index()
    {
        // ===== PRÉSTAMOS INTERNOS DEL DÍA =====
        $prestamosInternos = PrestamoInterno::where('fecha', Carbon::today()->toDateString())
            ->orderBy('id', 'desc')
            ->get();

        // ===== MÉTRICAS DEL DASHBOARD =====
        $hoy = Carbon::today();

        // ---- INVENTARIO ----
        $totalLibros = Inventario::count();

        // Libros prestados a domicilio = libros únicos con préstamo pendiente
        $librosPrestados = Prestamo::where('estatus', 'pendiente')
            ->distinct()
            ->count('libro_id');

        $librosDisponibles = max(0, $totalLibros - $librosPrestados);

        // ---- PRÉSTAMOS A DOMICILIO ----
        $prestamosVencidos = Prestamo::where('estatus', 'pendiente')
            ->whereDate('fecha_devolucion', '<', $hoy)
            ->count();

        // Total de préstamos pendientes (sin devolver, vencidos o no)
        $prestamosPendientes = Prestamo::where('estatus', 'pendiente')->count();

        return view('inicio', compact(
            'prestamosInternos',
            'totalLibros',
            'librosDisponibles',
            'librosPrestados',
            'prestamosVencidos',
            'prestamosPendientes'
        ));
    }

    // Buscar lector por Cédula vía AJAX
    public function buscarLector($cedula)
    {
        $persona = Persona::where('cedula', $cedula)->first();

        if ($persona) {
            return response()->json([
                'encontrado' => true,
                'nombre'     => $persona->nombre,
                'telefono'   => $persona->telefono,
                'correo'     => $persona->correo
            ]);
        }

        return response()->json([
            'encontrado' => false,
            'mensaje'    => 'Lector no encontrado en el sistema'
        ]);
    }

    // Buscar libro por Cota vía AJAX (para Modal de Sala)
    public function buscarLibro($cota)
    {
        $libro = Inventario::where('cota', $cota)->first();

        if ($libro) {
            // 1. Verificar si existe un préstamo interno activo para esta cota
            $enUsoSala = PrestamoInterno::where('cota_libro', $cota)
                ->where('estado', 'activo')
                ->exists();

            if ($enUsoSala) {
                return response()->json([
                    'encontrado' => true,
                    'disponible' => false,
                    'titulo'     => $libro->titulo,
                    'autor'      => $libro->autor,
                    'mensaje'    => 'El libro actualmente se encuentra prestado en sala.'
                ]);
            }

            // 2. Verificar si el libro está prestado a domicilio
            if ($libro->estaPrestadoADomicilio()) {
                return response()->json([
                    'encontrado' => true,
                    'disponible' => false,
                    'titulo'     => $libro->titulo,
                    'autor'      => $libro->autor,
                    'mensaje'    => 'El libro actualmente se encuentra prestado a domicilio.'
                ]);
            }

            return response()->json([
                'encontrado' => true,
                'disponible' => true,
                'titulo'     => $libro->titulo,
                'autor'      => $libro->autor
            ]);
        }

        return response()->json([
            'encontrado' => false,
            'mensaje'    => 'Libro no encontrado en el inventario'
        ]);
    }

    // Guardar Préstamo Interno (Sala)
    public function guardarPrestamoInterno(Request $request)
    {
        $this->validate($request, [
            'cedula'        => 'required',
            'nombre_lector' => 'required',
            'cota_libro'    => 'required',
            'titulo_libro'  => 'required'
        ]);

        $cota  = trim($request->cota_libro);
        $libro = Inventario::where('cota', $cota)->first();

        if (!$libro) {
            return redirect()->back()->with('error', 'El libro no existe en el inventario.');
        }

        // Validación 1: Préstamo en Sala
        $enUsoSala = PrestamoInterno::where('cota_libro', $cota)
            ->where('estado', 'activo')
            ->exists();

        if ($enUsoSala) {
            return redirect()->back()->with('error', 'El libro ya está en uso en sala y no se puede prestar nuevamente.');
        }

        // Validación 2: Préstamo a Domicilio
        if ($libro->estaPrestadoADomicilio()) {
            return redirect()->back()->with('error', 'El libro se encuentra en un préstamo a domicilio activo.');
        }

        $horaActual = Carbon::now()->format('H:i:s');

        $prestamoInterno = PrestamoInterno::create([
            'cedula'        => $request->cedula,
            'nombre_lector' => $request->nombre_lector,
            'cota_libro'    => $cota,
            'titulo_libro'  => $request->titulo_libro,
            'fecha'         => Carbon::today()->toDateString(),
            'hora_prestamo' => $horaActual,
            'estado'        => 'activo'
        ]);

        Bitacora::registrar(
            'create',
            'prestamos_internos',
            "Se registró un préstamo interno de '{$request->titulo_libro}' para '{$request->nombre_lector}'.",
            null,
            [
                'nombre_lector' => $request->nombre_lector,
                'cedula'        => $request->cedula,
                'titulo_libro'  => $request->titulo_libro,
                'cota_libro'    => $cota
            ]
        );

        return redirect()->back()->with('mensaje', 'Préstamo interno registrado exitosamente.');
    }

    // Marcar como Devuelto (Registrar hora de devolución)
    public function devolverPrestamoInterno($id)
    {
        $prestamo   = PrestamoInterno::findOrFail($id);
        $datosAntes = [
            'nombre_lector' => $prestamo->nombre_lector,
            'titulo_libro'  => $prestamo->titulo_libro,
            'estado'        => $prestamo->estado
        ];

        $prestamo->hora_devolucion = Carbon::now()->format('H:i:s');
        $prestamo->estado          = 'devuelto';
        $prestamo->save();

        Bitacora::registrar(
            'update',
            'prestamos_internos',
            "Se devolvió el préstamo interno de '{$prestamo->titulo_libro}' (lector: {$prestamo->nombre_lector}).",
            $datosAntes,
            [
                'estado'          => $prestamo->estado,
                'hora_devolucion' => $prestamo->hora_devolucion
            ]
        );

        return redirect()->back()->with('mensaje', 'Libro entregado / devuelto a la biblioteca.');
    }
}