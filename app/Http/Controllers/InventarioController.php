<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Inventario;
use App\Sede; // Asegúrate de importar el modelo Sede
use App\Autor;
use App\Estanteria;
use App\Sala;
use App\Pasillo;
use App\Bitacora;
use App\HistorialDanoLibro;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('search');
        $sedeFiltro = $request->input('sede_id');
        $user = \Illuminate\Support\Facades\Auth::user();

        // El filtro de sede solo está permitido para la sede principal (sede_id == 1 o super admin)
        $puedeFiltrarSede = ($user && ((int)$user->sede_id === 1 || empty($user->sede_id)));
        if (!$puedeFiltrarSede) {
            $sedeFiltro = null;
        }

        $cantidadResultados = 0;
        $resultados = collect();

        // Obtener todas las sedes para el select
        $sedes = Sede::orderBy('comunidad')->orderBy('biblioteca')->get();

        // Catálogo de autores para el select del formulario (ya no es un campo libre)
        $autores = Autor::orderBy('nombre')->get();

        // Catálogo de estanterías para asignar la ubicación física de cada libro
        $estanterias = Estanteria::with('pasillo.sala')->orderBy('numero')->get();

        // Catálogos de salas y pasillos para el modal de registro rápido de estanterías
        $salas = Sala::orderBy('nombre')->get();
        $pasillos = Pasillo::with('sala')->orderBy('numero')->get();

        $query = Inventario::with('sede', 'estanteria.pasillo.sala');

        if (!empty($sedeFiltro)) {
            $query->where(function ($q) use ($sedeFiltro) {
                $q->where('sede_id', $sedeFiltro)
                  ->orWhere('ubicacion', $sedeFiltro);
            });
        }

        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'LIKE', "%{$buscar}%")
                  ->orWhere('autor', 'LIKE', "%{$buscar}%")
                  ->orWhere('cota', 'LIKE', "%{$buscar}%");
            });
        }

        if (!empty($buscar) || !empty($sedeFiltro)) {
            $qContador = clone $query;
            $cantidadResultados = $qContador->count();
        }

        $inventarios = $query->paginate(10)->appends($request->query());

        return view('inventario', compact(
            'inventarios', 'buscar', 'sedeFiltro', 'puedeFiltrarSede',
            'cantidadResultados', 'resultados', 'sedes', 'autores',
            'estanterias', 'salas', 'pasillos'
        ));
    }

    public function store(Request $request)
    {
        $request->merge([
            'estanteria_id' => $request->input('estanteria_id') ?: null,
        ]);

        $this->validate($request, [
            'titulo'    => 'required|string|max:255',
            'autor'     => 'required|string|max:255',
            'cota'      => 'required|string|unique:inventario,cota', 
            'categoria' => 'required|string',
            'ubicacion' => 'required|exists:sedes,id', // Cambiado para validar que exista en sedes[cite: 9]
            'estanteria_id' => 'nullable|integer|exists:estanterias,id',
            'ingreso'   => 'nullable|string',
            'estado'    => 'required|in:bueno,deteriorado,almacen', 
            'observaciones' => 'nullable|string', 
            'volumen'   => 'nullable|integer|min:0|max:5',
            'dias_prestamo' => 'nullable|integer|min:1|max:90',
        ]);

        if ($request->input('estanteria_id')) {
            Estanteria::findOrFail($request->input('estanteria_id'));
        }

        $datos = $request->all();
        $datos['sede_id'] = $request->input('ubicacion');
        
        if (in_array($request->estado, ['bueno', 'almacen'])) {
            $datos['observaciones'] = null;
        }

        if ($request->estado !== 'bueno') {
            $datos['dias_prestamo'] = null;
        }

        $inventario = Inventario::create($datos);

        Bitacora::registrar('create', 'inventario', "Se registró el libro '{$inventario->titulo}' con cota '{$inventario->cota}'.", null, ['titulo' => $inventario->titulo, 'cota' => $inventario->cota, 'autor' => $inventario->autor]);

        return redirect()->route('inventario.index')->with('success', '✅ Libro agregado con éxito');
    }

    public function update(Request $request, $cota)
    {
        $libro = Inventario::where('cota', $cota)->firstOrFail();

        $request->merge([
            'estanteria_id' => $request->input('estanteria_id') ?: null,
        ]);
        
        $this->validate($request, [
            'titulo'    => 'required|string|max:255',
            'autor'     => 'required|string|max:255',
            'categoria' => 'required|string',
            'ubicacion' => 'required|exists:sedes,id', // Cambiado para validar que exista en sedes[cite: 9]
            'estanteria_id' => 'nullable|integer|exists:estanterias,id',
            'ingreso'   => 'nullable|string',
            'estado'    => 'required|in:bueno,deteriorado,almacen',
            'observaciones' => 'nullable|string',
            'volumen'   => 'nullable|integer|min:0|max:5',
            'dias_prestamo' => 'nullable|integer|min:1|max:90',
        ]);

        if ($request->input('estanteria_id')) {
            Estanteria::findOrFail($request->input('estanteria_id'));
        }

        $datos = $request->all();
        $datos['sede_id'] = $request->input('ubicacion');

        if (in_array($request->estado, ['bueno', 'almacen'])) {
            $datos['observaciones'] = null;
        }

        if ($request->estado !== 'bueno') {
            $datos['dias_prestamo'] = null;
        }

        $datosAntes = $libro->toArray();
        $libro->update($datos);

        Bitacora::registrar('update', 'inventario', "Se actualizó el libro '{$libro->titulo}' (cota: {$libro->cota}).", $datosAntes, ['titulo' => $libro->titulo, 'cota' => $libro->cota, 'autor' => $libro->autor, 'estado' => $libro->estado]);

        return redirect()->route('inventario.index')->with('success', '✅ Libro actualizado con éxito');
    }
    public function destroy($cota)
    {
        $libro = Inventario::where('cota', $cota)->firstOrFail();

        $datosAntes = $libro->toArray();

        // Evitar eliminar un libro que tiene préstamos activos o pendientes
        if ($libro->estaPrestadoADomicilio() || $libro->estaPrestadoEnSala()) {
            return redirect()->route('inventario.index')
                ->with('error', '❌ No se puede eliminar: el libro tiene un préstamo activo o pendiente.');
        }

        try {
            $libro->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('inventario.index')
                ->with('error', '❌ No se puede eliminar el libro porque tiene registros relacionados.');
        }

        Bitacora::registrar('delete', 'inventario', "Se eliminó el libro '{$datosAntes['titulo']}' (cota: {$datosAntes['cota']}).", $datosAntes, null);

        return redirect()->route('inventario.index')->with('success', '✅ Libro eliminado con éxito');
    }

    public function verificarDisponibilidad($cota)
    {
        // Buscar el libro por su cota en el inventario[cite: 9]
        $libro = Inventario::where('cota', trim($cota))->first();

        if (!$libro) {
            return response()->json([
                'disponible' => false,
                'mensaje' => 'El libro no existe en el inventario.'
            ]);
        }

        // Estado registrado en el inventario ('bueno' | 'deteriorado' | 'almacen'),
        // se incluye siempre que el libro exista para que el encargado sepa
        // en qué condiciones sale, independientemente de si está disponible.[cite: 9]
        $estadoLibro = $libro->estado;
        $estadoLibroLabel = 'Bueno';
        if ($estadoLibro === 'deteriorado') {
            $estadoLibroLabel = 'Deteriorado';
        } elseif ($estadoLibro === 'almacen') {
            $estadoLibroLabel = 'En almacén';
        }

        // Los libros deteriorados o en almacén no optan al préstamo
        if ($estadoLibro === 'deteriorado') {
            return response()->json([
                'disponible'     => false,
                'mensaje'        => 'El libro está DETERIORADO y no puede ser prestado.',
                'estado'         => $estadoLibro,
                'estado_label'   => $estadoLibroLabel,
                'observaciones'  => $libro->observaciones,
                'dias_prestamo'  => $libro->dias_prestamo,
                'motivo_bloqueo' => 'deteriorado',
            ]);
        }

        if ($estadoLibro === 'almacen') {
            return response()->json([
                'disponible'     => false,
                'mensaje'        => 'El libro está en ALMACÉN y no está disponible para préstamo directo.',
                'estado'         => $estadoLibro,
                'estado_label'   => $estadoLibroLabel,
                'observaciones'  => $libro->observaciones,
                'dias_prestamo'  => $libro->dias_prestamo,
                'motivo_bloqueo' => 'almacen',
            ]);
        }

        // Verificar si está prestado a domicilio[cite: 9]
        if ($libro->estaPrestadoADomicilio()) {
            return response()->json([
                'disponible'    => false,
                'mensaje'       => 'El libro no está disponible (tiene un préstamo a domicilio pendiente).',
                'estado'        => $estadoLibro,
                'estado_label'  => $estadoLibroLabel,
                'observaciones' => $libro->observaciones,
                'dias_prestamo' => $libro->dias_prestamo,
            ]);
        }

        // Verificar si está prestado en sala[cite: 9]
        if ($libro->estaPrestadoEnSala()) {
            return response()->json([
                'disponible'    => false,
                'mensaje'       => 'El libro no está disponible (se encuentra prestado en sala).',
                'estado'        => $estadoLibro,
                'estado_label'  => $estadoLibroLabel,
                'observaciones' => $libro->observaciones,
                'dias_prestamo' => $libro->dias_prestamo,
            ]);
        }

        // Si existe y no está prestado de ninguna forma[cite: 9]
        return response()->json([
            'disponible'    => true,
            'mensaje'       => 'Libro disponible para préstamo: ' . $libro->titulo,
            'estado'        => $estadoLibro,
            'estado_label'  => $estadoLibroLabel,
            'observaciones' => $libro->observaciones,
            'dias_prestamo' => $libro->dias_prestamo,
        ]);
    }

    /**
     * Historial de daños del libro
     */
    public function historial($cota)
    {
        try {
            $libro = Inventario::where('cota', trim($cota))->firstOrFail();
            $historial = HistorialDanoLibro::with(['persona', 'prestamo', 'sancion'])
                ->where('libro_id', $libro->cota)
                ->orderBy('created_at', 'desc')
                ->get();

            $datos = array();
            foreach ($historial as $item) {
                $datos[] = array(
                    'id' => $item->id,
                    'tipo_evento' => $item->tipo_evento,
                    'estado' => $item->estado,
                    'descripcion' => $item->descripcion,
                    'solucion' => $item->solucion,
                    'fecha_evento' => $item->fecha_evento ? $item->fecha_evento->format('d/m/Y H:i') : ($item->created_at ? $item->created_at->format('d/m/Y H:i') : null),
                    'fecha_resolucion' => $item->fecha_resolucion ? $item->fecha_resolucion->format('d/m/Y H:i') : null,
                    'persona' => $item->persona ? ($item->persona->nombre . ' ' . $item->persona->apellido) : null,
                    'cedula' => $item->persona ? $item->persona->cedula : null,
                    'prestamo_id' => $item->prestamo_id,
                    'sancion_id' => $item->sancion_id,
                );
            }

            return response()->json(array(
                'libro' => array(
                    'cota' => $libro->cota,
                    'titulo' => $libro->titulo,
                    'autor' => $libro->autor,
                    'estado' => $libro->estado
                ),
                'historial' => $datos
            ));
        } catch (\Exception $e) {
            return response()->json(array('error' => 'No se pudo obtener el historial'), 404);
        }
    }

    /**
     * Exportar la tabla de inventario a un archivo SQL descargable.
     * La sede principal puede exportar todos los libros o filtrados por sede.
     * Las sedes secundarias solo pueden exportar estrictamente sus propios registros.
     */
    public function exportarSql(Request $request)
    {
        $user = Auth::user();
        $query = Inventario::query();

        $esPrincipal = ($user && ((int)$user->sede_id === 1 || empty($user->sede_id)));

        if ($esPrincipal) {
            $sedeFiltro = $request->input('sede_id');
            if (!empty($sedeFiltro)) {
                $query->where(function ($q) use ($sedeFiltro) {
                    $q->where('sede_id', $sedeFiltro)->orWhere('ubicacion', $sedeFiltro);
                });
            }
        } else {
            // Forzar que sedes secundarias solo exporten su propia sede
            if ($user && !empty($user->sede_id)) {
                $query->where('sede_id', $user->sede_id);
            }
        }

        // Filtro por plazo de tiempo / días
        $plazo = $request->input('plazo', 'todo');
        $textoPlazo = 'Todo el catálogo';
        $sufijoArchivo = 'completo';

        if ($plazo === 'semana') {
            $desde = Carbon::now()->subDays(7)->startOfDay();
            $query->where('created_at', '>=', $desde);
            $textoPlazo = 'Última semana (desde ' . $desde->format('d/m/Y') . ')';
            $sufijoArchivo = 'semana';
        } elseif ($plazo === 'mes') {
            $desde = Carbon::now()->subDays(30)->startOfDay();
            $query->where('created_at', '>=', $desde);
            $textoPlazo = 'Último mes (desde ' . $desde->format('d/m/Y') . ')';
            $sufijoArchivo = 'mes';
        } elseif ($plazo === 'trimestre') {
            $desde = Carbon::now()->subDays(90)->startOfDay();
            $query->where('created_at', '>=', $desde);
            $textoPlazo = 'Último trimestre (desde ' . $desde->format('d/m/Y') . ')';
            $sufijoArchivo = 'trimestre';
        } elseif ($plazo === 'personalizado') {
            $fechaDesde = $request->input('fecha_desde');
            $fechaHasta = $request->input('fecha_hasta');
            if (!empty($fechaDesde)) {
                $query->where('created_at', '>=', $fechaDesde . ' 00:00:00');
            }
            if (!empty($fechaHasta)) {
                $query->where('created_at', '<=', $fechaHasta . ' 23:59:59');
            }
            $textoPlazo = 'Rango personalizado (' . ($fechaDesde ?: 'Inicio') . ' al ' . ($fechaHasta ?: 'Hoy') . ')';
            $sufijoArchivo = 'rango';
        }

        $libros = $query->orderBy('cota')->get();

        $fecha = Carbon::now()->format('Y-m-d H:i:s');
        $fechaArchivo = Carbon::now()->format('Y-m-d_H-i-s');
        
        $nombreSede = 'Todas las sedes';
        if (!$esPrincipal && $user && $user->sede) {
            $nombreSede = $user->sede->biblioteca;
        } elseif ($esPrincipal && !empty($sedeFiltro)) {
            $sObj = Sede::find($sedeFiltro);
            if ($sObj) {
                $nombreSede = $sObj->biblioteca;
            }
        } elseif ($esPrincipal && $user && $user->sede) {
            $nombreSede = $user->sede->biblioteca . ' (General)';
        }

        $sql = "-- ============================================================\n";
        $sql .= "-- SISTEMA DRFP - EXPORTACION DE BASE DE DATOS (INVENTARIO)\n";
        $sql .= "-- Generado por: " . ($user ? $user->name : 'Sistema') . "\n";
        $sql .= "-- Sede: " . $nombreSede . "\n";
        $sql .= "-- Plazo de registros: " . $textoPlazo . "\n";
        $sql .= "-- Fecha de exportación: " . $fecha . "\n";
        $sql .= "-- Total de libros exportados: " . $libros->count() . "\n";
        $sql .= "-- ============================================================\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        $campos = array(
            'cota', 'sede_id', 'titulo', 'autor', 'volumen', 'categoria',
            'ubicacion', 'ingreso', 'estado', 'observaciones', 'estanteria_id',
            'dias_prestamo', 'created_at', 'updated_at'
        );

        foreach ($libros as $libro) {
            $valores = array();
            foreach ($campos as $campo) {
                $val = $libro->$campo;
                if ($val === null) {
                    $valores[] = "NULL";
                } elseif (is_numeric($val) && in_array($campo, array('volumen', 'dias_prestamo', 'estanteria_id', 'sede_id'))) {
                    $valores[] = (int)$val;
                } else {
                    $valores[] = "'" . addslashes($val) . "'";
                }
            }

            $sql .= "INSERT INTO `inventario` (`" . implode("`, `", $campos) . "`)\n";
            $sql .= "VALUES (" . implode(", ", $valores) . ")\n";
            $sql .= "ON DUPLICATE KEY UPDATE\n";
            $sql .= "  `sede_id` = VALUES(`sede_id`),\n";
            $sql .= "  `titulo` = VALUES(`titulo`),\n";
            $sql .= "  `autor` = VALUES(`autor`),\n";
            $sql .= "  `volumen` = VALUES(`volumen`),\n";
            $sql .= "  `categoria` = VALUES(`categoria`),\n";
            $sql .= "  `ubicacion` = VALUES(`ubicacion`),\n";
            $sql .= "  `ingreso` = VALUES(`ingreso`),\n";
            $sql .= "  `estado` = VALUES(`estado`),\n";
            $sql .= "  `observaciones` = VALUES(`observaciones`),\n";
            $sql .= "  `estanteria_id` = VALUES(`estanteria_id`),\n";
            $sql .= "  `dias_prestamo` = VALUES(`dias_prestamo`),\n";
            $sql .= "  `updated_at` = VALUES(`updated_at`);\n\n";
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Guardar copia obligatoria en la carpeta de respaldos del sistema
        $carpetaBackups = storage_path('backups');
        if (!is_dir($carpetaBackups)) {
            @mkdir($carpetaBackups, 0755, true);
        }

        $identificadorSede = $user && !empty($user->sede_id) ? 'sede_' . $user->sede_id : 'sede_principal';
        $nombreArchivo = 'export_inventario_' . $identificadorSede . '_' . $sufijoArchivo . '_' . $fechaArchivo . '.sql';
        $rutaCopia = $carpetaBackups . DIRECTORY_SEPARATOR . $nombreArchivo;
        @file_put_contents($rutaCopia, $sql);

        Bitacora::registrar(
            'export',
            'inventario',
            "Se exportó la base de datos de inventario ({$libros->count()} registros, plazo: {$textoPlazo}) a SQL.",
            null,
            array('archivo' => $nombreArchivo, 'total' => $libros->count(), 'plazo' => $textoPlazo)
        );

        return response($sql, 200, array(
            'Content-Type' => 'application/sql; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
        ));
    }

    /**
     * Importar un archivo SQL de inventario.
     * Guarda una copia en storage/backups, valida la seguridad del SQL
     * y ejecuta las sentencias dentro de una transacción.
     */
    public function importarSql(Request $request)
    {
        $this->validate($request, array(
            'archivo_sql' => 'required|file',
        ));

        $user = Auth::user();
        $archivo = $request->file('archivo_sql');

        // Validar extensión
        $extension = strtolower($archivo->getClientOriginalExtension());
        if ($extension !== 'sql' && $extension !== 'txt') {
            return back()->with('error', '❌ El archivo subido debe tener extensión .sql');
        }

        // Guardar copia en storage/backups
        $carpetaBackups = storage_path('backups');
        if (!is_dir($carpetaBackups)) {
            @mkdir($carpetaBackups, 0755, true);
        }

        $fechaArchivo = Carbon::now()->format('Y-m-d_H-i-s');
        $nombreOriginalLimpio = preg_replace('/[^A-Za-z0-9_\.]/', '', $archivo->getClientOriginalName());
        $identificadorSede = $user && !empty($user->sede_id) ? 'sede_' . $user->sede_id : 'sede_principal';
        $nombreCopia = 'import_inventario_' . $identificadorSede . '_' . $fechaArchivo . '_' . $nombreOriginalLimpio;
        $rutaCopia = $carpetaBackups . DIRECTORY_SEPARATOR . $nombreCopia;

        $archivo->move($carpetaBackups, $nombreCopia);

        if (!file_exists($rutaCopia)) {
            return back()->with('error', '❌ No se pudo guardar la copia del archivo en el servidor.');
        }

        $contenido = file_get_contents($rutaCopia);

        // Validación de seguridad: no permitir sentencias destructivas ni accesos a otras tablas
        if (preg_match('/\b(DROP\s+DATABASE|DROP\s+TABLE|TRUNCATE|DELETE\s+FROM\s+(?!inventario)|UPDATE\s+users|INSERT\s+INTO\s+(?!inventario))\b/i', $contenido)) {
            return back()->with('error', '❌ El archivo contiene sentencias SQL no permitidas para la importación de inventario.');
        }

        $esPrincipal = ($user && ((int)$user->sede_id === 1 || empty($user->sede_id)));

        try {
            DB::beginTransaction();

            DB::unprepared($contenido);

            // Si es sede secundaria, asegurar que cualquier libro importado pertenezca a su sede
            if (!$esPrincipal && $user && !empty($user->sede_id)) {
                DB::table('inventario')
                    ->where(function ($q) use ($user) {
                        $q->whereNull('sede_id')->orWhere('sede_id', 0);
                    })
                    ->update(array(
                        'sede_id' => $user->sede_id,
                        'ubicacion' => $user->sede_id,
                    ));
            }

            DB::commit();

            Bitacora::registrar(
                'import',
                'inventario',
                "Se importó la base de datos de inventario desde el archivo SQL '{$nombreOriginalLimpio}'.",
                null,
                array('archivo' => $nombreCopia)
            );

            return redirect()->route('inventario.index')
                ->with('success', '✅ Base de datos de inventario importada y sincronizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al ejecutar el script SQL: ' . $e->getMessage());
        }
    }
}