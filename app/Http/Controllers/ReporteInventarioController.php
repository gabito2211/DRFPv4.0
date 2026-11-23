<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventario;

class ReporteInventarioController extends Controller
{
    // Las categorías se guardan en minúscula y sin tilde (así las guarda el
    // formulario de Inventario). Este mapa sirve para mostrarlas bien escritas.
    private $categorias = [
        'literatura' => 'Literatura',
        'historia'   => 'Historia',
        'medicina'   => 'Medicina',
        'ciencia'    => 'Ciencia',
        'arte'       => 'Arte',
        'tecnologia' => 'Tecnología',
        'educacion'  => 'Educación',
    ];

    /**
     * Punto de entrada único de los reportes.
     * Parámetros (GET): tipo, formato (html|csv) y los filtros de cada tipo.
     */
    public function generar(Request $request)
    {
        $tipo = $request->input('tipo', 'general');

        switch ($tipo) {
            case 'general':
                $reporte = $this->listado($request, null);
                break;
            case 'categoria':
                $reporte = $this->listado($request, 'categoria');
                break;
            case 'autor':
                $reporte = $this->listado($request, 'autor');
                break;
            case 'stock':
                $reporte = $this->stock($request);
                break;
            case 'prestados':
                $reporte = $this->masPrestados($request);
                break;
            default:
                abort(404);
        }

        if ($request->input('formato') === 'csv') {
            return $this->descargarCsv($reporte);
        }

        // Enlace para descargar este mismo reporte (con los mismos filtros) en CSV
        $urlCsv = route('inventario.reportes', array_merge(
            $request->query(),
            ['tipo' => $tipo, 'formato' => 'csv']
        ));

        return view('reportes.inventario', compact('reporte', 'urlCsv'));
    }

    // =========================================================
    // REPORTE 1, 2 y 3: listado general / por categoría / por autor
    // =========================================================
    private function listado(Request $request, $agruparPor)
    {
        $consulta = Inventario::query();
        $filtro = null;

        if ($agruparPor === 'categoria' || $agruparPor === 'autor') {
            $valor = trim($request->input($agruparPor, ''));
            if ($valor !== '') {
                $consulta->where($agruparPor, $valor);
                $filtro = ($agruparPor === 'categoria' ? 'Categoría: ' . $this->etiquetaCategoria($valor) : 'Autor: ' . $valor);
            }
            $consulta->orderBy($agruparPor);
        }

        $libros  = $consulta->orderBy('titulo')->orderBy('volumen')->get();
        $titulos = $this->agruparTitulos($libros);

        $agrupados = [];
        $totalEjemplares = 0;
        foreach ($titulos as $t) {
            $fila = [
                $t['titulo'],
                $t['autor'],
                $this->etiquetaCategoria($t['categoria']),
                $this->volumen($t['volumen']),
                $t['ejemplares'],
            ];
            $totalEjemplares += $t['ejemplares'];

            if ($agruparPor === 'categoria') {
                $nombre = $this->etiquetaCategoria($t['categoria']);
            } elseif ($agruparPor === 'autor') {
                $nombre = $t['autor'];
            } else {
                $nombre = '';
            }
            $agrupados[$nombre][] = $fila;
        }

        $grupos = [];
        foreach ($agrupados as $nombre => $filas) {
            $nombre = (string) $nombre;
            $grupos[] = [
                'nombre' => $agruparPor ? ($nombre === '' ? '(sin dato)' : $nombre) : null,
                'filas'  => $filas,
                'nota'   => count($filas) . ' título(s) · ' . array_sum(array_column($filas, 4)) . ' ejemplar(es)',
            ];
        }

        $tipo = $agruparPor ? $agruparPor : 'general';
        $titulosReporte = [
            'general'   => 'Listado general de libros',
            'categoria' => 'Libros por categoría',
            'autor'     => 'Libros por autor',
        ];
        $archivos = [
            'general'   => 'listado_general',
            'categoria' => 'libros_por_categoria',
            'autor'     => 'libros_por_autor',
        ];

        return [
            'titulo'        => $titulosReporte[$tipo],
            'archivo'       => $archivos[$tipo],
            'filtro'        => $filtro,
            'columnas'      => ['Título', 'Autor', 'Categoría', 'Vol.', 'Ejemplares'],
            'numericas'     => [3, 4],
            'etiquetaGrupo' => $agruparPor ? ($agruparPor === 'categoria' ? 'Categoría' : 'Autor') : null,
            'grupos'        => $grupos,
            'vacio'         => count($titulos) === 0,
            'resumen'       => 'Total: ' . count($titulos) . ' título(s) y ' . $totalEjemplares . ' ejemplar(es).',
        ];
    }

    // =========================================================
    // REPORTE 4: libros con pocos ejemplares disponibles o sin stock
    // "Disponibles" = ejemplares que NO están prestados en este momento
    // (ni a domicilio pendiente ni en sala activo).
    // =========================================================
    private function stock(Request $request)
    {
        $umbral = (int) $request->input('umbral', 2);
        if ($umbral < 0) {
            $umbral = 0;
        }
        if ($umbral > 50) {
            $umbral = 50;
        }

        $libros = Inventario::withCount([
            'prestamos' => function ($q) {
                $q->where('estatus', 'pendiente');
            },
            'prestamosInternos' => function ($q) {
                $q->where('estado', 'activo');
            },
        ])->orderBy('titulo')->orderBy('volumen')->get();

        $filas = [];
        foreach ($this->agruparTitulos($libros) as $t) {
            $disponibles = $t['ejemplares'] - $t['prestados'];
            if ($disponibles > $umbral) {
                continue;
            }
            $filas[] = [
                $t['titulo'],
                $t['autor'],
                $this->etiquetaCategoria($t['categoria']),
                $this->volumen($t['volumen']),
                $t['ejemplares'],
                $disponibles,
                $disponibles === 0 ? 'Sin stock' : 'Stock bajo',
            ];
        }

        // Primero los que tienen menos disponibles; a igualdad, por título
        usort($filas, function ($a, $b) {
            if ($a[5] !== $b[5]) {
                return $a[5] - $b[5];
            }
            return strcasecmp($a[0], $b[0]);
        });

        $sinStock = 0;
        foreach ($filas as $f) {
            if ($f[5] === 0) {
                $sinStock++;
            }
        }

        return [
            'titulo'        => 'Libros con pocos ejemplares o sin stock',
            'archivo'       => 'libros_stock_bajo',
            'filtro'        => 'Se muestran los títulos con ' . $umbral . ' ejemplar(es) disponible(s) o menos',
            'columnas'      => ['Título', 'Autor', 'Categoría', 'Vol.', 'Ejemplares', 'Disponibles', 'Estado'],
            'numericas'     => [3, 4, 5],
            'etiquetaGrupo' => null,
            'grupos'        => [['nombre' => null, 'filas' => $filas, 'nota' => null]],
            'vacio'         => count($filas) === 0,
            'resumen'       => 'Total: ' . count($filas) . ' título(s), de los cuales ' . $sinStock . ' están sin stock.',
        ];
    }

    // =========================================================
    // REPORTE 5: libros más prestados (a domicilio + en sala)
    // Se cuentan todos los préstamos registrados en el sistema.
    // =========================================================
    private function masPrestados(Request $request)
    {
        $limite = (int) $request->input('limite', 20);
        if ($limite < 1 || $limite > 500) {
            $limite = 20;
        }

        $libros = Inventario::withCount(['prestamos', 'prestamosInternos'])->get();

        $ranking = [];
        foreach ($this->agruparTitulos($libros) as $t) {
            $total = $t['dom'] + $t['sala'];
            if ($total > 0) {
                $t['total'] = $total;
                $ranking[] = $t;
            }
        }

        usort($ranking, function ($a, $b) {
            if ($a['total'] !== $b['total']) {
                return $b['total'] - $a['total'];
            }
            return strcasecmp($a['titulo'], $b['titulo']);
        });

        $ranking = array_slice($ranking, 0, $limite);

        $filas = [];
        foreach ($ranking as $i => $t) {
            $filas[] = [
                $i + 1,
                $t['titulo'],
                $t['autor'],
                $this->etiquetaCategoria($t['categoria']),
                $this->volumen($t['volumen']),
                $t['dom'],
                $t['sala'],
                $t['total'],
            ];
        }

        return [
            'titulo'        => 'Libros más prestados',
            'archivo'       => 'libros_mas_prestados',
            'filtro'        => 'Los ' . $limite . ' títulos con más préstamos (a domicilio y en sala)',
            'columnas'      => ['#', 'Título', 'Autor', 'Categoría', 'Vol.', 'Préstamos a domicilio', 'Préstamos en sala', 'Total'],
            'numericas'     => [0, 4, 5, 6, 7],
            'etiquetaGrupo' => null,
            'grupos'        => [['nombre' => null, 'filas' => $filas, 'nota' => null]],
            'vacio'         => count($filas) === 0,
            'resumen'       => 'Total mostrado: ' . count($filas) . ' título(s).',
        ];
    }

    // =========================================================
    // AYUDANTES
    // =========================================================

    /**
     * En la tabla inventario cada fila es UN ejemplar físico (identificado
     * por su cota). Para reportar por libro se juntan las filas que tienen
     * el mismo título + autor + volumen + categoría: el número de filas
     * juntadas es la cantidad de ejemplares.
     *
     * Además acumula, según los withCount de la consulta:
     *  - prestados: ejemplares con al menos un préstamo contado
     *  - dom / sala: suma de préstamos a domicilio / en sala contados
     * (en el reporte de stock los conteos son solo los préstamos abiertos;
     *  en el de más prestados son todos los préstamos).
     */
    private function agruparTitulos($libros)
    {
        $titulos = [];

        foreach ($libros as $libro) {
            $clave = mb_strtolower(trim($libro->titulo)) . '|'
                   . mb_strtolower(trim($libro->autor)) . '|'
                   . (int) $libro->volumen . '|'
                   . mb_strtolower(trim($libro->categoria));

            if (!isset($titulos[$clave])) {
                $titulos[$clave] = [
                    'titulo'     => trim($libro->titulo),
                    'autor'      => trim($libro->autor),
                    'categoria'  => trim($libro->categoria),
                    'volumen'    => (int) $libro->volumen,
                    'ejemplares' => 0,
                    'prestados'  => 0,
                    'dom'        => 0,
                    'sala'       => 0,
                ];
            }

            $dom  = (int) $libro->prestamos_count;
            $sala = (int) $libro->prestamos_internos_count;

            $titulos[$clave]['ejemplares']++;
            $titulos[$clave]['dom']  += $dom;
            $titulos[$clave]['sala'] += $sala;
            if ($dom > 0 || $sala > 0) {
                $titulos[$clave]['prestados']++;
            }
        }

        return $titulos;
    }

    private function etiquetaCategoria($valor)
    {
        $clave = mb_strtolower(trim($valor));
        return isset($this->categorias[$clave]) ? $this->categorias[$clave] : ucfirst(trim($valor));
    }

    private function volumen($valor)
    {
        return $valor ? $valor : '—';
    }

    /**
     * CSV listo para Excel: separador ";" (el de Excel en español) y BOM UTF-8
     * para que las tildes y la ñ se vean bien.
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
