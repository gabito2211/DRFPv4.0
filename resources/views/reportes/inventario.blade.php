<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reporte['titulo'] }} - Biblioteca Dr. Félix Pifano</title>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <link rel="icon" href="{{ asset('img/library.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/plantilla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos_generales.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilo_tablas.css') }}">
    <style>
        /* Caja de filtro activo, mismo estilo que el "contador-resultados" de Inventario */
        .contador-resultados {
            background: #e8f4fd;
            border-left: 4px solid #2196F3;
            padding: 12px 20px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 1rem;
            color: #1a237e;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .contador-resultados .icono-libro { font-size: 1.5rem; }

        /* Fila que agrupa por categoría/autor dentro de la tabla */
        tr.fila-grupo td {
            background: #eef2ff;
            color: #1e3a8a;
            font-weight: 600;
        }
        tr.fila-grupo td span {
            font-weight: normal;
            color: var(--texto-suave);
        }

        .celda-alerta { color: #d63031; font-weight: 600; }
        .celda-aviso  { color: #b45309; font-weight: 600; }
        .celda-num    { text-align: center; }

        .resumen-reporte {
            padding: 14px 5px 4px;
            font-weight: 600;
            color: var(--texto-suave);
        }

        /* Al imprimir: ocultar todo lo que no sea el reporte en sí */
        @media print {
            #sidebar, .no-imprimir, footer { display: none !important; }
            #main-wrapper { margin-left: 0 !important; }
            #main-content { padding: 0 !important; }
            .contenedor-tabla { box-shadow: none !important; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
            th, tr.fila-grupo td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    @include('layouts.nav')

    <div id="main-wrapper">
        <main id="main-content">

            <div class="header-acciones">
                <div>
                    <h1>{{ $reporte['titulo'] }}</h1>
                    <p style="color: var(--texto-suave);">Biblioteca Dr. Félix Pifano · Generado el {{ date('d/m/Y h:i A') }}</p>
                </div>
            </div>

            @if($reporte['filtro'])
                <div class="contador-resultados">
                    <span class="icono-libro">📊</span>
                    <span>{{ $reporte['filtro'] }}</span>
                </div>
            @endif

            <div class="contenedor-tabla">
                <div class="header-tabla">
                    <div class="titulo-seccion"><h3>Resultados</h3></div>
                    <div class="no-imprimir" style="display:flex; gap:10px; margin-left:auto; flex-wrap:wrap;">
                        <button type="button" class="btn btn-azul" onclick="window.print()">
                            <i data-lucide="printer"></i> Imprimir / PDF
                        </button>
                        <a href="{{ $urlCsv }}" class="btn btn-gris">
                            <i data-lucide="file-down"></i> Excel
                        </a>
                        <a href="{{ route('inventario.index') }}" class="btn btn-gris">
                            <i data-lucide="arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                @if($reporte['vacio'])
                    <div style="text-align: center; padding: 40px;">
                        <i data-lucide="book-open" size="48" style="margin-bottom: 10px; color: #9ca3af;"></i>
                        <p>No hay libros que cumplan con este reporte.</p>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                @foreach($reporte['columnas'] as $i => $columna)
                                    <th class="{{ in_array($i, $reporte['numericas']) ? 'celda-num' : '' }}">{{ $columna }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reporte['grupos'] as $grupo)
                                @if($grupo['nombre'] !== null)
                                    <tr class="fila-grupo">
                                        <td colspan="{{ count($reporte['columnas']) }}">
                                            {{ $reporte['etiquetaGrupo'] }}: {{ $grupo['nombre'] }}
                                            <span>— {{ $grupo['nota'] }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @foreach($grupo['filas'] as $fila)
                                    <tr>
                                        @foreach($fila as $i => $celda)
                                            <td class="
                                                {{ in_array($i, $reporte['numericas']) ? 'celda-num' : '' }}
                                                {{ $celda === 'Sin stock' ? 'celda-alerta' : ($celda === 'Stock bajo' ? 'celda-aviso' : '') }}
                                            ">{{ $celda }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>

                    <div class="resumen-reporte">{{ $reporte['resumen'] }}</div>
                @endif
            </div>

        </main>
        @include('layouts.footer')
    </div>

    <script src="{{ asset('js/plantilla.js') }}?v={{ filemtime(public_path('js/plantilla.js')) }}"></script>
</body>
</html>