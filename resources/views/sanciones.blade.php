<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Sanciones - Biblioteca Dr. Félix Pifano</title>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <link rel="icon" href="{{ asset('img/library.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/plantilla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos_generales.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilo_tablas.css') }}">
</head>
<body>
    @include('layouts.mensajes')
    @include('layouts.nav')

    <div id="main-wrapper">
        <main id="main-content">
            <div class="header-acciones" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; width: 100%; max-width: 1100px;">
                <div>
                    <h1>Gestión de Sanciones y Bloqueos</h1>
                    <p style="color: var(--texto-suave);">Control y levantamiento de suspensiones a usuarios del sistema.</p>
                </div>
            </div>

            <!-- TABLA -->
            <div class="contenedor-tabla" style="max-width: 1100px; width: 100%;">

                <div class="titulo-form" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <h3>Listado de Sanciones Registradas</h3>
                    <form action="{{ route('sanciones.index') }}" method="GET" style="display: flex; gap: 8px; align-items: center; margin: 0;">
                        <div class="busqueda-rapida" style="margin: 0;">
                            <i data-lucide="search" size="18"></i>
                            <input type="text" name="search" class="input-busqueda" placeholder="Buscar por cédula o nombre..." value="{{ isset($buscar) ? $buscar : '' }}">
                        </div>
                        <button type="submit" class="btn btn-azul" style="padding: 8px 15px;">Buscar</button>
                    </form>
                </div>

                <table id="tabla-sanciones">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cédula</th>
                            <th>Lector</th>
                            <th>Tipo de Daño</th>
                            <th>Estado Sanción</th>
                            <th>Fecha Registro</th>
                            <th>Fecha Resolución</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        @forelse($sanciones as $sancion)
                            <tr>
                                <td>{{ $sancion->id }}</td>
                                <td><strong>{{ $sancion->persona ? $sancion->persona->cedula : 'N/A' }}</strong></td>
                                <td>{{ $sancion->persona ? $sancion->persona->nombre . ' ' . $sancion->persona->apellido : 'Desconocido' }}</td>
                                <td>{{ $sancion->tipo_dano }}</td>
                                <td>
                                    @if($sancion->estado === 'pendiente')
                                        <span style="background: var(--error, #dc3545); color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Bloqueado</span>
                                    @elseif($sancion->estado === 'solventado')
                                        <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Solventado</span>
                                    @else
                                        <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Resuelta</span>
                                    @endif
                                </td>
                                <td>{{ $sancion->created_at ? $sancion->created_at->format('d/m/Y') : '-' }}</td>
                                <td>{{ $sancion->fecha_resolucion ? \Carbon\Carbon::parse($sancion->fecha_resolucion)->format('d/m/Y') : 'Pendiente' }}</td>
                                <td style="text-align: center; display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                    {{-- Botón VER (siempre visible) --}}
                                    <button type="button" class="btn btn-gris btn-ver-sancion"
                                            data-id="{{ $sancion->id }}"
                                            style="padding: 6px 12px; font-size: 12px;">
                                        <i data-lucide="eye" size="16"></i> Ver
                                    </button>

                                    @if($sancion->estado === 'pendiente')
                                        {{-- Solo visible para Administradores (rol 1) --}}
                                        @if(Auth::user()->rol == 1)
                                            <button type="button" class="btn btn-azul btn-abrir-modal" data-id="{{ $sancion->id }}" style="padding: 6px 12px; font-size: 12px;">
                                                <i data-lucide="check-circle" size="16"></i> Resolver
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 20px; color: var(--texto-suave);">
                                    No se encontraron registros de sanciones.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="paginacion-container" style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                    <div class="info-paginacion" id="info-paginacion">
                        Mostrando registros
                    </div>
                    <div class="botones-paginacion">
                        <button class="btn-pag" id="btn-prev" disabled><i data-lucide="chevron-left" size="16"></i> Anterior</button>
                        <div id="numeros-paginas" style="display: flex; gap: 5px;"></div>
                        <button class="btn-pag" id="btn-next">Siguiente <i data-lucide="chevron-right" size="16"></i></button>
                    </div>
                </div>

            </div>

            {{-- ======================================================= --}}
            {{-- MODALES DE RESOLVER SANCIONES PENDIENTES                --}}
            {{-- ======================================================= --}}
            @foreach($sanciones as $sancion)
                @if($sancion->estado === 'pendiente')
                    <div id="modal-resolver-{{ $sancion->id }}" class="contenedor-form" style="display: none !important; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; width: 90%; max-width: 500px; box-shadow: 0 0 25px rgba(0,0,0,0.6); text-align: left; background: #ffffff; padding: 25px; border-radius: 8px;">
                        <div class="titulo-form" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h2 style="margin: 0; font-size: 18px;">Resolver Sanción</h2>
                            <button type="button" class="btn-cerrar-modal" data-id="{{ $sancion->id }}" style="border:none; background:none; cursor:pointer; color:var(--error);">
                                <i data-lucide="x-circle"></i>
                            </button>
                        </div>

                        <form action="{{ route('sanciones.solventar', $sancion->id) }}" method="POST">
                            {{ csrf_field() }}
                            <div class="rejilla-form" style="display: block;">
                                <div class="grupo-campo" style="margin-bottom: 15px;">
                                    <p style="font-size: 14px; color: var(--texto-suave); margin: 0 0 10px 0;">Cédula del Lector: <strong>{{ $sancion->persona ? $sancion->persona->cedula : 'N/A' }}</strong></p>
                                </div>
                                <div class="grupo-campo" style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Tipo de solución:</label>
                                    <select name="tipo_resolucion" class="entrada">
                                        <option value="">Seleccionar (opcional)</option>
                                        <option value="reparado">Libro reparado</option>
                                        <option value="pagado">Daño pagado / repuesto</option>
                                        <option value="resuelto">Sanción resuelta / otro</option>
                                    </select>
                                </div>
                                <div class="grupo-campo ancho-total" style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Observación de resolución:</label>
                                    <textarea name="observacion_resolucion" rows="3" class="area-texto" required placeholder="Ej. El usuario pagó la multa / reparó el libro..."></textarea>
                                </div>
                            </div>
                            <div class="fila-botones" style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
                                <button type="button" class="btn btn-gris btn-cerrar-modal" data-id="{{ $sancion->id }}">Cancelar</button>
                                <button type="submit" class="btn btn-azul"><i data-lucide="save"></i> Confirmar y Desbloquear</button>
                            </div>
                        </form>
                    </div>
                @endif
            @endforeach

            {{-- ======================================================= --}}
            {{-- MODAL VER SANCIÓN (único, reutilizable)                 --}}
            {{-- ======================================================= --}}
            <div id="modal-ver-sancion" class="contenedor-form" style="display: none !important; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; width: 90%; max-width: 550px; box-shadow: 0 0 25px rgba(0,0,0,0.6); background: #ffffff; padding: 25px; border-radius: 8px;">
                <div class="titulo-form" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h2 style="margin: 0; font-size: 18px;">Detalles de la Sanción</h2>
                    <button type="button" class="btn-cerrar-ver" style="border:none; background:none; cursor:pointer; color:var(--error);">
                        <i data-lucide="x-circle"></i>
                    </button>
                </div>

                <div style="font-size: 14px; line-height: 1.8; color: var(--texto-oscuro);">
                    <p style="margin: 8px 0;"><strong>ID:</strong> #<span id="ver-id"></span></p>
                    <p style="margin: 8px 0;"><strong>Cédula:</strong> <span id="ver-cedula"></span></p>
                    <p style="margin: 8px 0;"><strong>Lector:</strong> <span id="ver-lector"></span></p>
                    <p style="margin: 8px 0;"><strong>Tipo de Daño:</strong> <span id="ver-tipo-dano"></span></p>
                    <p style="margin: 8px 0;"><strong>Estado:</strong> <span id="ver-estado"></span></p>
                    <p style="margin: 8px 0;"><strong>Fecha de Registro:</strong> <span id="ver-fecha-registro"></span></p>
                    <p style="margin: 8px 0;"><strong>Fecha de Resolución:</strong> <span id="ver-fecha-resolucion"></span></p>
                    <p style="margin: 8px 0;"><strong>Observación de resolución:</strong><br>
                        <span id="ver-observacion" style="color: var(--texto-suave);"></span>
                    </p>
                </div>

                <div class="fila-botones" style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
                    <button type="button" class="btn btn-gris btn-cerrar-ver">Cerrar</button>
                </div>
            </div>

        </main>
        @include('layouts.footer')
    </div>

    <script src="{{ asset('js/plantilla.js') }}?v={{ filemtime(public_path('js/plantilla.js')) }}"></script>
    <script src="{{ asset('js/paginacion.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if(window.lucide) lucide.createIcons();

            // === Abrir modal de RESOLVER ===
            document.querySelectorAll('.btn-abrir-modal').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const modal = document.getElementById('modal-resolver-' + id);
                    if (modal) {
                        modal.style.setProperty('display', 'block', 'important');
                    }
                });
            });

            // === Cerrar modales de RESOLVER ===
            document.querySelectorAll('.btn-cerrar-modal').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const modal = document.getElementById('modal-resolver-' + id);
                    if (modal) {
                        modal.style.setProperty('display', 'none', 'important');
                    }
                });
            });

            // === Abrir modal de VER ===
            document.querySelectorAll('.btn-ver-sancion').forEach(button => {
                button.addEventListener('click', () => {
                    const fila = button.closest('tr');
                    const celdas = fila.querySelectorAll('td');

                    const id        = celdas[0].innerText.trim();
                    const cedula    = celdas[1].innerText.trim();
                    const lector    = celdas[2].innerText.trim();
                    const tipoDano  = celdas[3].innerText.trim();
                    const estado    = celdas[4].innerText.trim();
                    const fRegistro = celdas[5].innerText.trim();
                    const fResol    = celdas[6].innerText.trim();

                    document.getElementById('ver-id').textContent               = id;
                    document.getElementById('ver-cedula').textContent           = cedula;
                    document.getElementById('ver-lector').textContent           = lector;
                    document.getElementById('ver-tipo-dano').textContent        = tipoDano;
                    document.getElementById('ver-estado').textContent           = estado;
                    document.getElementById('ver-fecha-registro').textContent   = fRegistro;
                    document.getElementById('ver-fecha-resolucion').textContent = fResol;
                    document.getElementById('ver-observacion').textContent      = 'Cargando detalles...';

                    // Consulta AJAX para obtener observaciones de resolución
                    fetch(`/sanciones/${id}/datos`)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('ver-observacion').textContent =
                                data.observacion_resolucion || 'Sin observaciones registradas.';
                        })
                        .catch(() => {
                            document.getElementById('ver-observacion').textContent = 'Sin observaciones registradas.';
                        });

                    document.getElementById('modal-ver-sancion').style.setProperty('display', 'block', 'important');
                    if (window.lucide) lucide.createIcons();
                });
            });

            // === Cerrar modal de VER ===
            document.querySelectorAll('.btn-cerrar-ver').forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('modal-ver-sancion').style.setProperty('display', 'none', 'important');
                });
            });
        });

        // Cerrar modales haciendo clic fuera del recuadro
        window.onclick = function(event) {
            if (event.target.classList.contains('contenedor-form')) {
                event.target.style.setProperty('display', 'none', 'important');
            }
        }
    </script>
</body>
</html>