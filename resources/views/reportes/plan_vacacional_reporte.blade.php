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

        /* Caja de filtros (selectores) */
        .caja-filtros {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        .caja-filtros form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        .caja-filtros .campo {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .caja-filtros label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #4b5563;
            text-transform: uppercase;
        }
        .caja-filtros input,
        .caja-filtros select {
            padding: 7px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.9rem;
            font-family: inherit;
            background: white;
        }
        .caja-filtros input:focus,
        .caja-filtros select:focus {
            outline: none;
            border-color: var(--primario);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Fila que agrupa dentro de la tabla */
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

        /* Badges de estatus dentro de la tabla */
        .badge-estatus {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-verde    { background: #d1fae5; color: #065f46; }
        .badge-amarillo { background: #fef3c7; color: #92400e; }
        .badge-rojo     { background: #fee2e2; color: #991b1b; }
        .badge-azul     { background: #dbeafe; color: #1e40af; }

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
            .caja-filtros, .no-imprimir { display: none !important; }
        }
    </style>
</head>
<body>
    @include('layouts.nav')

    <div id="main-wrapper">
        <main id="main-content">

            @include('layouts.mensajes')

            <div class="header-acciones">
                <div>
                    <h1>{{ $reporte['titulo'] }}</h1>
                    <p style="color: var(--texto-suave);">Biblioteca Dr. Félix Pifano · Generado el {{ date('d/m/Y h:i A') }}</p>
                </div>
            </div>

            {{-- CAJA DE FILTROS --}}
            <div class="caja-filtros no-imprimir">
                <form method="GET" action="{{ route('reportes.plan.index') }}">
                    <div class="campo">
                        <label for="plan_id">Plan Vacacional</label>
                        <select name="plan_id" id="plan_id" onchange="this.form.submit()" style="min-width: 280px;">
                            <option value="">-- Seleccione un plan --</option>
                            @foreach($planes as $p)
                                @php
                                    $ep = $p->estado;
                                    if ($ep !== 'inactivo' && $ep !== 'completado' && \Carbon\Carbon::parse($p->hasta)->isPast()) {
                                        $ep = 'completado';
                                    }
                                    $icono = $ep === 'activo' ? '🔴' : ($ep === 'completado' ? '✅' : '⛔');
                                @endphp
                                <option value="{{ $p->id }}" {{ (string)($planId !== null ? $planId : ($plan ? $plan->id : '')) === (string)$p->id ? 'selected' : '' }}>
                                    {{ $icono }} {{ $p->nombre }}
                                    ({{ \Carbon\Carbon::parse($p->desde)->format('d/m/Y') }} -
                                     {{ \Carbon\Carbon::parse($p->hasta)->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="campo">
                        <label for="tipo-select">Tipo de reporte</label>
                        <select name="tipo" id="tipo-select" onchange="this.form.submit()">
                            <option value="general"     {{ $tipo === 'general'     ? 'selected' : '' }}>📋 Listado general</option>
                            <option value="cronologico" {{ $tipo === 'cronologico' ? 'selected' : '' }}>📅 Cronograma completo</option>
                            <option value="por_tipo"    {{ $tipo === 'por_tipo'    ? 'selected' : '' }}>🏷️ Por tipo de actividad</option>
                            <option value="por_sede"    {{ $tipo === 'por_sede'    ? 'selected' : '' }}>📍 Por sede</option>
                            <option value="por_objetivo"{{ $tipo === 'por_objetivo'? 'selected' : '' }}>🎯 Por objetivo</option>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="search">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Actividad, responsable, sede...">
                    </div>

                    <div class="campo">
                        <button type="submit" class="btn btn-azul">
                            <i data-lucide="filter"></i> Filtrar
                        </button>
                    </div>

                    <div class="campo">
                        <a href="{{ route('reportes.plan.index', array('plan_id' => $plan ? $plan->id : null, 'tipo' => $tipo)) }}" class="btn btn-gris">
                            <i data-lucide="x"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- CONTADOR DE RESULTADOS --}}
            <div class="contador-resultados no-imprimir">
                <span class="icono-libro">📊</span>
                <span>
                    Mostrando <strong>{{ collect($reporte['grupos'])->sum(function($g) { return count($g['filas']); }) }}</strong>
                    actividades
                    @if($plan)
                        del plan <strong>{{ $plan->nombre }}</strong>
                    @endif
                    @if($search !== '')
                        · Filtro: <em>"{{ $search }}"</em>
                    @endif
                </span>
            </div>

            {{-- TABLA DE RESULTADOS --}}
            <div class="contenedor-tabla">
                <div class="header-tabla">
                    <div class="titulo-seccion"><h3>Resultados</h3></div>
                    <div class="no-imprimir" style="display:flex; gap:10px; margin-left:auto; flex-wrap:wrap;">
                        <button type="button" class="btn btn-azul" onclick="window.print()">
                            <i data-lucide="printer"></i> Imprimir / PDF
                        </button>
                        <a href="{{ route('planes.index') }}" class="btn btn-gris">
                            <i data-lucide="arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                @if($reporte['vacio'])
                    <div style="text-align: center; padding: 40px;">
                        <i data-lucide="inbox" size="48" style="margin-bottom: 10px; color: #9ca3af;"></i>
                        <p>No hay actividades que cumplan con este reporte.</p>
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
                                            @if($grupo['nota'])
                                                <span>— {{ $grupo['nota'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @foreach($grupo['filas'] as $fila)
                                    <tr>
                                        @foreach($fila as $i => $celda)
                                            <td class="
                                                {{ in_array($i, $reporte['numericas']) ? 'celda-num' : '' }}
                                                @if(is_string($celda))
                                                    @if(strpos($celda, '🔴') !== false || strpos($celda, '⚠️') !== false)
                                                        celda-alerta
                                                    @elseif(strpos($celda, '🟡') !== false)
                                                        celda-aviso
                                                    @endif
                                                @endif
                                            ">
                                                @if(is_string($celda) && strpos($celda, '🔴') !== false)
                                                    <span class="badge-estatus badge-rojo">{{ str_replace('🔴 ', '', $celda) }}</span>
                                                @elseif(is_string($celda) && strpos($celda, '🟡') !== false)
                                                    <span class="badge-estatus badge-amarillo">{{ str_replace('🟡 ', '', $celda) }}</span>
                                                @elseif(is_string($celda) && strpos($celda, '🟢') !== false)
                                                    <span class="badge-estatus badge-verde">{{ str_replace('🟢 ', '', $celda) }}</span>
                                                @elseif(is_string($celda) && strpos($celda, '⚠️') !== false)
                                                    <span class="badge-estatus badge-rojo">{{ $celda }}</span>
                                                @elseif(is_string($celda) && strpos($celda, '✅') !== false)
                                                    <span class="badge-estatus badge-verde">{{ $celda }}</span>
                                                @else
                                                    {{ $celda }}
                                                @endif
                                            </td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        });
    </script>
    <script src="{{ asset('js/plantilla.js') }}?v={{ filemtime(public_path('js/plantilla.js')) }}"></script>
</body>
</html>