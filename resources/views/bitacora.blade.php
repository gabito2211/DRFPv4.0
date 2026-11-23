<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bitácora del Sistema - Biblioteca Dr. Félix Pifano</title>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <link rel="icon" href="{{ asset('img/library.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/plantilla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos_generales.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilo_tablas.css') }}">
</head>
<body>

    @include('layouts.nav')

    <div id="main-wrapper">
        <main id="main-content">

            @include('layouts.mensajes')

            <div class="header-acciones">
                <div>
                    <h1>Bitácora del Sistema</h1>
                    <p style="color: var(--texto-suave);">Registro de todas las acciones realizadas por los usuarios.</p>
                </div>
            </div>

            <div class="contenedor-form" style="max-width: 100%; margin-bottom: 20px;">
                <div class="titulo-form">
                    <h3>Filtros de Búsqueda</h3>
                </div>
                <form method="GET" action="{{ route('bitacora.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
                    <div class="grupo-campo">
                        <label for="user_id">Usuario</label>
                        <select name="user_id" id="user_id" class="entrada">
                            <option value="">Todos los usuarios</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ request('user_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grupo-campo">
                        <label for="modulo">Módulo</label>
                        <select name="modulo" id="modulo" class="entrada">
                            <option value="">Todos los módulos</option>
                            @foreach($modulos as $modulo)
                                <option value="{{ $modulo }}" {{ request('modulo') == $modulo ? 'selected' : '' }}>
                                    {{ ucfirst($modulo) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grupo-campo">
                        <label for="accion">Acción</label>
                        <select name="accion" id="accion" class="entrada">
                            <option value="">Todas las acciones</option>
                            @foreach($acciones as $accion)
                                <option value="{{ $accion }}" {{ request('accion') == $accion ? 'selected' : '' }}>
                                    {{ ucfirst($accion) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grupo-campo">
                        <label for="fecha_desde">Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" class="entrada" value="{{ request('fecha_desde') }}">
                    </div>

                    <div class="grupo-campo">
                        <label for="fecha_hasta">Hasta</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" class="entrada" value="{{ request('fecha_hasta') }}">
                    </div>

                    <div class="grupo-campo" style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-azul">
                            <i data-lucide="search"></i> Filtrar
                        </button>
                        <a href="{{ route('bitacora.index') }}" class="btn btn-gris">
                            <i data-lucide="rotate-ccw"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <div class="contenedor-tabla">
                <div class="titulo-form">
                    <h3>Registro de Actividad</h3>
                    <span style="color: var(--texto-suave); font-size: 0.9rem;">
                        {{ $bitacoras->total() }} registros encontrados
                    </span>
                </div>

                <div style="overflow-x: auto;">
                    <table id="tabla-bitacora" style="min-width: 900px;">
                        <thead>
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Módulo</th>
                                <th>Descripción</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bitacoras as $registro)
                            <tr>
                                <td style="white-space: nowrap;">{{ $registro->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $registro->user_name }}</td>
                                <td>
                                    <span class="badge" style="padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
                                        @if(in_array($registro->accion, ['login', 'create']))
                                            background: #e6f4ea; color: #137333;
                                        @elseif(in_array($registro->accion, ['logout']))
                                            background: #fff3e0; color: #e65100;
                                        @elseif(in_array($registro->accion, ['login-fallido']))
                                            background: #fdecea; color: #b71c1c;
                                        @elseif(in_array($registro->accion, ['update']))
                                            background: #e3f2fd; color: #1565c0;
                                        @elseif(in_array($registro->accion, ['delete']))
                                            background: #fce8e6; color: #c5221f;
                                        @elseif(in_array($registro->accion, ['backup']))
                                            background: #e0f2f1; color: #00695c;
                                        @elseif(in_array($registro->accion, ['backup-fallido']))
                                            background: #fff8e1; color: #b71c1c;
                                        @else
                                            background: #f3e5f5; color: #7b1fa2;
                                        @endif
                                    ">
                                        {{ $registro->accion }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($registro->modulo) }}</td>
                                <td style="max-width: 400px; word-wrap: break-word;">{{ $registro->descripcion }}</td>
                                <td style="font-size: 0.8rem; color: var(--texto-suave);">{{ $registro->ip_address }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--texto-suave); padding: 40px;">
                                    No se encontraron registros con los filtros seleccionados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="paginacion-container" style="margin-top: 20px;">
                    <div class="info-paginacion">
                        Mostrando {{ $bitacoras->firstItem() }} a {{ $bitacoras->lastItem() }} de {{ $bitacoras->total() }} registros
                    </div>
                    <div class="botones-paginacion">
                        {{ $bitacoras->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

        </main>

        @include('layouts.footer')
    </div>

    <script src="{{ asset('js/plantilla.js') }}?v={{ filemtime(public_path('js/plantilla.js')) }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
