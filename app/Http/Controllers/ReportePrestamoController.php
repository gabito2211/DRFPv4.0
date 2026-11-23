<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prestamo;
use App\Inventario;
use App\Carnet;
use App\Persona;
use App\Sancion;
use Carbon\Carbon;

class ReportePrestamoController extends Controller
{
    /**
     * Punto de entrada único de los reportes de préstamos.
     * Parámetros (GET): tipo, formato (html|csv) y filtros de cada tipo.
     */
    public function generar(Request $request)
    {
        $tipo = $request->input('tipo', 'general');

        switch ($tipo) {
            case 'general':
                $reporte = $this->listadoGeneral($request);
                break;
            case 'vencidos':
                $reporte = $this->vencidos($request);
                break;
            case 'sancionados':
                $reporte = $this->sancionados($request);
                break;
            case 'por_lector':
                $reporte = $this->porLector($request);
                break;
            case 'por_libro':
                $reporte = $this->porLibro($request);
                break;
            default:
                abort(404);
        }

        if ($request->input('formato') === 'csv') {
            return $this->descargarCsv($reporte);
        }

        // Enlace para descargar este mismo reporte (con los mismos filtros) en CSV
        $urlCsv = route('prestamo.reportes', array_merge(
            $request->query(),
            ['tipo' => $tipo, 'formato' => 'csv']
        ));

        return view('reportes.prestamos_reporte', compact('reporte', 'urlCsv'));
    }

    // =========================================================
    // REPORTE 1: LISTADO GENERAL DE PRÉSTAMOS
    // =========================================================
    private function listadoGeneral(Request $request)
    {
        $desde   = $request->input('desde', '');
        $hasta   = $request->input('hasta', '');
        $estatus = $request->input('estatus', '');

        $consulta = Prestamo::query();

        if ($desde !== '') {
            $consulta->where('fecha_salida', '>=', $desde);
        }
        if ($hasta !== '') {
            $consulta->where('fecha_salida', '<=', $hasta);
        }
        if ($estatus !== '') {
            $consulta->where('estatus', $estatus);
        }

        $prestamos = $consulta->orderBy('id', 'desc')->get();

        $hoy = Carbon::today();
        $filas = [];
        $totalVencidos = 0;
        $totalSancionados = 0;

        foreach ($prestamos as $p) {
            $datos = $this->enriquecerPrestamo($p, $hoy);

            if ($datos['esta_vencido']) {
                $totalVencidos++;
            }
            if ($datos['sanciones']->count() > 0) {
                $totalSancionados++;
            }

            $filas[] = [
                $p->id,
                $datos['lector_nombre'],
                $datos['lector_cedula'],
                $datos['libro_titulo'],
                $p->libro_id,
                Carbon::parse($p->fecha_salida)->format('d/m/Y'),
                Carbon::parse($p->fecha_devolucion)->format('d/m/Y'),
                $datos['estatus_label'],
                $datos['sanciones']->count() > 0
                    ? '⚠️ ' . $datos['sanciones']->count() . ' sanción(es)'
                    : '✅ Sin sanción',
            ];
        }

        $filtro = $this->construirFiltroTexto([
            'Desde'   => $desde,
            'Hasta'   => $hasta,
            'Estatus' => $estatus,
        ]);

        return [
            'titulo'        => 'Listado general de préstamos',
            'archivo'       => 'prestamos_general',
            'filtro'        => $filtro,
            'columnas'      => ['ID', 'Lector', 'Cédula', 'Libro', 'Cota', 'Fecha Salida', 'Fecha Devolución', 'Estatus', 'Sanciones'],
            'numericas'     => [0],
            'etiquetaGrupo' => null,
            'grupos'        => [['nombre' => null, 'filas' => $filas, 'nota' => null]],
            'vacio'         => count($filas) === 0,
            'resumen'       => 'Total: ' . count($filas) . ' préstamo(s) · ' . $totalVencidos . ' vencido(s) · ' . $totalSancionados . ' con sanción.',
            'resumen_items' => [
                ['label' => 'Total',       'valor' => count($filas),     'color' => 'azul'],
                ['label' => 'Vencidos',    'valor' => $totalVencidos,    'color' => 'rojo'],
                ['label' => 'Con Sanción', 'valor' => $totalSancionados, 'color' => 'rojo'],
            ],
        ];
    }

    // =========================================================
    // REPORTE 2: PRÉSTAMOS VENCIDOS
    // =========================================================
    private function vencidos(Request $request)
    {
        $hoy = Carbon::today();

        $prestamos = Prestamo::where('estatus', 'pendiente')
            ->where('fecha_devolucion', '<', $hoy)
            ->orderBy('fecha_devolucion', 'asc')
            ->get();

        $filas = [];
        $totalDias = 0;

        foreach ($prestamos as $p) {
            $datos = $this->enriquecerPrestamo($p, $hoy);
            $diasAtraso = abs($datos['dias_restantes']);
            $totalDias += $diasAtraso;

            $filas[] = [
                $p->id,
                $datos['lector_nombre'],
                $datos['lector_cedula'],
                $datos['libro_titulo'],
                Carbon::parse($p->fecha_devolucion)->format('d/m/Y'),
                $diasAtraso . ' día(s)',
                $datos['sanciones']->count() > 0 ? 'Con sanción' : 'Sin sanción',
            ];
        }

        $promedio = count($filas) > 0 ? round($totalDias / count($filas), 1) : 0;

        return [
            'titulo'        => 'Préstamos vencidos (fuera de lapso)',
            'archivo'       => 'prestamos_vencidos',
            'filtro'        => 'Préstamos con estatus pendiente y fecha de devolución menor a hoy',
            'columnas'      => ['ID', 'Lector', 'Cédula', 'Libro', 'Debía Devolver', 'Días de Atraso', 'Sanciones'],
            'numericas'     => [0],
            'etiquetaGrupo' => null,
            'grupos'        => [['nombre' => null, 'filas' => $filas, 'nota' => null]],
            'vacio'         => count($filas) === 0,
            'resumen'       => 'Total: ' . count($filas) . ' préstamo(s) vencido(s). Promedio de atraso: ' . $promedio . ' día(s).',
            'resumen_items' => [
                ['label' => 'Vencidos',      'valor' => count($filas), 'color' => 'rojo'],
                ['label' => 'Total días',    'valor' => $totalDias,    'color' => 'rojo'],
                ['label' => 'Promedio días', 'valor' => $promedio,     'color' => 'amarillo'],
            ],
        ];
    }

    // =========================================================
    // REPORTE 3: PRÉSTAMOS CON SANCIÓN
    // =========================================================
    private function sancionados(Request $request)
    {
        $sanciones = Sancion::orderBy('id', 'desc')->get();

        $filas = [];
        $totalTemporal = 0;
        $totalDefinitivo = 0;

        foreach ($sanciones as $s) {
            $prestamo = Prestamo::find($s->prestamo_id);
            $persona  = Persona::find($s->persona_id);
            $carnet   = $persona ? Carnet::where('persona_id', $persona->id)->first() : null;

            $tipoBloqueo = $carnet ? ($carnet->tipo_bloqueo ?: 'N/A') : 'N/A';

            if ($tipoBloqueo === 'temporal') {
                $totalTemporal++;
            } elseif ($tipoBloqueo === 'definitivo') {
                $totalDefinitivo++;
            }

            $filas[] = [
                $s->id,
                $prestamo ? $prestamo->id : '—',
                $persona ? ($persona->nombre . ' ' . $persona->apellido) : 'No encontrado',
                $persona ? $persona->cedula : '—',
                $carnet ? $carnet->num_carnet : 'N/A',
                \Illuminate\Support\Str::limit($s->motivo, 60),
                $s->estado,
                ucfirst($tipoBloqueo),
            ];
        }

        return [
            'titulo'        => 'Préstamos con sanción aplicada',
            'archivo'       => 'prestamos_sancionados',
            'filtro'        => 'Todas las sanciones registradas en el sistema',
            'columnas'      => ['ID Sanción', 'ID Préstamo', 'Lector', 'Cédula', 'Carnet', 'Motivo', 'Estado', 'Tipo Bloqueo'],
            'numericas'     => [0, 1],
            'etiquetaGrupo' => null,
            'grupos'        => [['nombre' => null, 'filas' => $filas, 'nota' => null]],
            'vacio'         => count($filas) === 0,
            'resumen'       => 'Total: ' . count($filas) . ' sanción(es) · ' . $totalTemporal . ' temporal(es) · ' . $totalDefinitivo . ' definitiva(s).',
            'resumen_items' => [
                ['label' => 'Total',       'valor' => count($filas),     'color' => 'azul'],
                ['label' => 'Temporales',  'valor' => $totalTemporal,    'color' => 'amarillo'],
                ['label' => 'Definitivas', 'valor' => $totalDefinitivo,  'color' => 'rojo'],
            ],
        ];
    }

    // =========================================================
    // REPORTE 4: PRÉSTAMOS POR LECTOR
    // =========================================================
    private function porLector(Request $request)
    {
        $hoy = Carbon::today();

        $prestamos = Prestamo::orderBy('lector_id')->get();

        $porLector = [];
        foreach ($prestamos as $p) {
            $datos = $this->enriquecerPrestamo($p, $hoy);
            $clave = $p->lector_id;

            if (!isset($porLector[$clave])) {
                $porLector[$clave] = [
                    'nombre'     => $datos['lector_nombre'],
                    'cedula'     => $p->lector_id,
                    'total'      => 0,
                    'pendientes' => 0,
                    'entregados' => 0,
                    'vencidos'   => 0,
                    'sanciones'  => 0,
                ];
            }

            $porLector[$clave]['total']++;
            if ($p->estatus === 'pendiente') {
                $porLector[$clave]['pendientes']++;
            } else {
                $porLector[$clave]['entregados']++;
            }
            if ($datos['esta_vencido']) {
                $porLector[$clave]['vencidos']++;
            }
            $porLector[$clave]['sanciones'] += $datos['sanciones']->count();
        }

        usort($porLector, function ($a, $b) {
            return $b['total'] - $a['total'];
        });

        $filas = [];
        foreach ($porLector as $l) {
            $filas[] = [
                $l['nombre'],
                $l['cedula'],
                $l['total'],
                $l['pendientes'],
                $l['entregados'],
                $l['vencidos'],
                $l['sanciones'],
            ];
        }

        return [
            'titulo'        => 'Préstamos por lector',
            'archivo'       => 'prestamos_por_lector',
            'filtro'        => 'Resumen agrupado por lector, ordenado por cantidad de préstamos',
            'columnas'      => ['Lector', 'Cédula', 'Total', 'Pendientes', 'Entregados', 'Vencidos', 'Sanciones'],
            'numericas'     => [2, 3, 4, 5, 6],
            'etiquetaGrupo' => null,
            'grupos'        => [['nombre' => null, 'filas' => $filas, 'nota' => null]],
            'vacio'         => count($filas) === 0,
            'resumen'       => 'Total: ' . count($filas) . ' lector(es) con préstamos registrados.',
            'resumen_items' => [
                ['label' => 'Lectores',  'valor' => count($filas),     'color' => 'azul'],
                ['label' => 'Préstamos', 'valor' => count($prestamos), 'color' => 'azul'],
            ],
        ];
    }

    // =========================================================
    // REPORTE 5: PRÉSTAMOS POR LIBRO
    // =========================================================
    private function porLibro(Request $request)
    {
        $hoy = Carbon::today();

        $prestamos = Prestamo::orderBy('libro_id')->get();

        $porLibro = [];
        foreach ($prestamos as $p) {
            $libro = Inventario::where('cota', $p->libro_id)->first();
            $clave = $p->libro_id;

            if (!isset($porLibro[$clave])) {
                $porLibro[$clave] = [
                    'titulo'     => $libro && isset($libro->titulo) ? $libro->titulo : 'No encontrado',
                    'autor'      => $libro && isset($libro->autor)  ? $libro->autor  : 'N/A',
                    'cota'       => $p->libro_id,
                    'total'      => 0,
                    'pendientes' => 0,
                    'entregados' => 0,
                    'vencidos'   => 0,
                ];
            }

            $porLibro[$clave]['total']++;
            if ($p->estatus === 'pendiente') {
                $porLibro[$clave]['pendientes']++;
            } else {
                $porLibro[$clave]['entregados']++;
            }
            if ($p->estatus === 'pendiente' && Carbon::parse($p->fecha_devolucion)->lt($hoy)) {
                $porLibro[$clave]['vencidos']++;
            }
        }

        usort($porLibro, function ($a, $b) {
            return $b['total'] - $a['total'];
        });

        $filas = [];
        foreach ($porLibro as $l) {
            $filas[] = [
                $l['titulo'],
                $l['autor'],
                $l['cota'],
                $l['total'],
                $l['pendientes'],
                $l['entregados'],
                $l['vencidos'],
            ];
        }

        return [
            'titulo'        => 'Préstamos por libro',
            'archivo'       => 'prestamos_por_libro',
            'filtro'        => 'Resumen agrupado por libro, ordenado por cantidad de préstamos',
            'columnas'      => ['Título', 'Autor', 'Cota', 'Total', 'Pendientes', 'Entregados', 'Vencidos'],
            'numericas'     => [3, 4, 5, 6],
            'etiquetaGrupo' => null,
            'grupos'        => [['nombre' => null, 'filas' => $filas, 'nota' => null]],
            'vacio'         => count($filas) === 0,
            'resumen'       => 'Total: ' . count($filas) . ' libro(s) con préstamos registrados.',
            'resumen_items' => [
                ['label' => 'Libros',    'valor' => count($filas),     'color' => 'azul'],
                ['label' => 'Préstamos', 'valor' => count($prestamos), 'color' => 'azul'],
            ],
        ];
    }

    // =========================================================
    // AYUDANTES
    // =========================================================

    /**
     * Enriquece un préstamo con datos del lector, libro y sanciones.
     */
    private function enriquecerPrestamo($prestamo, $hoy)
    {
        $persona = Persona::where('cedula', $prestamo->lector_id)->first();
        $carnet  = $persona ? Carnet::where('persona_id', $persona->id)->first() : null;
        $libro   = Inventario::where('cota', $prestamo->libro_id)->first();
        $sanciones = Sancion::where('prestamo_id', $prestamo->id)->get();

        $fechaDev = Carbon::parse($prestamo->fecha_devolucion);
        $estaVencido = ($prestamo->estatus === 'pendiente') && $fechaDev->lt($hoy);
        $diasRestantes = $hoy->diffInDays($fechaDev, false);

        // Etiqueta visual del estatus
        if ($estaVencido) {
            $estatusLabel = '🔴 Vencido';
        } elseif ($prestamo->estatus === 'pendiente') {
            $estatusLabel = '🟡 Pendiente';
        } else {
            $estatusLabel = '🟢 Entregado';
        }

        return [
            'lector_nombre'  => $persona ? ($persona->nombre . ' ' . $persona->apellido) : 'No encontrado',
            'lector_cedula'  => $prestamo->lector_id,
            'lector_carnet'  => $carnet ? $carnet->num_carnet : 'N/A',
            'libro_titulo'   => $libro && isset($libro->titulo) ? $libro->titulo : 'No encontrado',
            'libro_autor'    => $libro && isset($libro->autor)  ? $libro->autor  : 'N/A',
            'esta_vencido'   => $estaVencido,
            'dias_restantes' => $diasRestantes,
            'estatus_label'  => $estatusLabel,
            'sanciones'      => $sanciones,
        ];
    }

    /**
     * Construye un texto legible con los filtros aplicados.
     */
    private function construirFiltroTexto(array $filtros)
    {
        $partes = [];
        foreach ($filtros as $label => $valor) {
            if ($valor !== '' && $valor !== null) {
                $partes[] = $label . ': ' . $valor;
            }
        }
        return count($partes) > 0 ? implode(' · ', $partes) : null;
    }

    /**
     * CSV listo para Excel: separador ";" y BOM UTF-8.
     */
    private function descargarCsv(array $reporte)
    {
        $tmp = fopen('php://temp', 'r+');

        $cabecera = $reporte['columnas'];
        if ($reporte['etiquetaGrupo']) {
            array_unshift($cabecera, $reporte['etiquetaGrupo']);
        }
        fputcsv($tmp, $cabecera, ';');

        foreach ($reporte['grupos'] as $grupo) {
            foreach ($grupo['filas'] as $fila) {
                if ($reporte['etiquetaGrupo']) {
                    array_unshift($fila, $grupo['nombre']);
                }
                fputcsv($tmp, $fila, ';');
            }
        }

        rewind($tmp);
        $contenido = "\xEF\xBB\xBF" . stream_get_contents($tmp);
        fclose($tmp);

        return response($contenido, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $reporte['archivo'] . '_' . date('Y-m-d') . '.csv"',
        ]);
    }
}