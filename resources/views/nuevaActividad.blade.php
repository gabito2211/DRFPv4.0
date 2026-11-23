<!-- nuevaActividad.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro de Actividades - Biblioteca Dr. Félix Pifano</title>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <link rel="icon" href="{{ asset('img/library.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/plantilla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos_generales.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilo_tablas.css') }}">
    <style>
        /* ===== ESTILOS ADICIONALES ESPECÍFICOS ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 2000;
            backdrop-filter: blur(3px);
            overflow-y: auto;
        }
        
        .modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 700px;
            margin: 2% auto;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-height: 90vh;
            overflow-y: auto;
            padding: 20px;
        }

        .modal-content .rejilla-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .modal-content .grupo-campo-completo {
            grid-column: 1 / -1;
        }

        .count-badge {
            background: var(--primario);
            color: white;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .plan-info {
            background: #e8f8f5;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #00b894;
            max-width: 1100px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .plan-info-activo {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }

        .plan-info-completado {
            background: #dbeafe;
            border-left: 4px solid #1e40af;
        }

        .plan-info-activo .badge-estatus {
            background: #f59e0b;
            color: white;
        }

        .plan-info-alert {
            background: #fff3cd;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #ffc107;
            max-width: 1100px;
            width: 100%;
        }

        .mensaje-exito {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
            max-width: 1100px;
            width: 100%;
        }

        .mensaje-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
            max-width: 1100px;
            width: 100%;
        }

        .mensaje-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .cronograma-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            max-width: 1100px;
            width: 100%;
            display: none;
        }

        .cronograma-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .cronograma-calendario {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            text-align: center;
        }

        .cronograma-dia {
            padding: 8px 4px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
            border: 1px solid transparent;
        }
        .cronograma-dia:hover {
            background: #e8f8f5;
            border-color: #00b894;
        }
        .cronograma-dia.tiene-actividad {
            background: #00b894;
            color: white;
            font-weight: bold;
        }
        .cronograma-dia.tiene-actividad:hover {
            background: #00a381;
        }
        .cronograma-dia.otro-mes {
            color: #ccc;
        }
        .cronograma-dia.otro-mes.tiene-actividad {
            background: #b2bec3;
            color: white;
        }
        .cronograma-dia.seleccionado {
            border-color: #0984e3;
            box-shadow: 0 0 0 2px #0984e3;
        }

        .cronograma-detalles {
            border-left: 2px solid var(--fondo-sitio);
            padding-left: 20px;
        }

        .badge-estatus {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-activo {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-inactivo {
            background: #fecaca;
            color: #991b1b;
        }
        .badge-completado {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-deshabilitado {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .advertencia-activo {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 10px 15px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .advertencia-completado {
            background: #dbeafe;
            border: 1px solid #1e40af;
            color: #1e40af;
            padding: 10px 15px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .advertencia-activo i,
        .advertencia-completado i {
            margin-right: 8px;
        }

        .actividad-mini {
            background: #f8f9fa;
            padding: 6px 10px;
            border-radius: 4px;
            margin-bottom: 6px;
            font-size: 0.85rem;
            border-left: 3px solid #00b894;
        }
        .actividad-mini .actividad-hora {
            font-weight: 600;
            color: #00b894;
        }
        .actividad-mini .actividad-nombre {
            color: var(--texto);
        }

        /* Estilo para opción sin encargado */
        .option-sin-encargado {
            color: #e74c3c !important;
        }

        .error-sede {
            color: #e74c3c;
            font-size: 0.85rem;
            padding: 8px 0;
            display: none;
            background: #fde8e8;
            border-radius: 4px;
            padding: 8px 12px;
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .modal-content .rejilla-form {
                grid-template-columns: 1fr;
            }
            .cronograma-grid {
                grid-template-columns: 1fr;
            }
            .cronograma-detalles {
                border-left: none;
                padding-left: 0;
                padding-top: 20px;
                border-top: 2px solid var(--fondo-sitio);
            }
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
                    <h1>Registro de Actividades</h1>
                    <p style="color: var(--texto-suave);">Control de actividades, instituciones y recorridos programados.</p>
                </div>

                    <a href="{{ route('planes.index') }}" class="btn btn-gris">
                        <i data-lucide="arrow-left"></i> Volver a Planes
                    </a>
            </div>

            <!-- Información del Plan Vacacional -->
            @if($planSeleccionado)
            <div class="plan-info {{ $planActivo ? 'plan-info-activo' : '' }} {{ $planCompletado ? 'plan-info-completado' : '' }}">
                <div>
                    <p style="margin: 0; font-weight: 500;">
                        <i data-lucide="check-circle" style="color: {{ $planActivo ? '#f59e0b' : ($planCompletado ? '#1e40af' : '#00b894') }};"></i>
                        <strong>{{ $planSeleccionado->nombre }}</strong>
                        <span style="color: var(--texto-suave); font-weight: normal; font-size: 0.9rem;">
                            ({{ \Carbon\Carbon::parse($planSeleccionado->desde)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($planSeleccionado->hasta)->format('d/m/Y') }})
                        </span>
                        @if($planActivo)
                            <span class="badge-estatus" style="background: #f59e0b; color: white; margin-left: 10px;">🔴 Activo</span>
                            <span style="color: #92400e; font-size: 0.8rem; margin-left: 10px;">
                                ⚠️ No se pueden registrar ni eliminar actividades
                            </span>
                        @elseif($planCompletado)
                            <span class="badge-estatus badge-completado" style="margin-left: 10px;">✅ Completado</span>
                            <span style="color: #1e40af; font-size: 0.8rem; margin-left: 10px;">
                                📋 Solo visualización de actividades
                            </span>
                        @else
                            <span class="badge-estatus badge-inactivo" style="margin-left: 10px;">Inactivo</span>
                            <span style="color: #065f46; font-size: 0.8rem; margin-left: 10px;">
                                ✅ Se pueden modificar actividades
                            </span>
                        @endif
                    </p>
                </div>
                <button id="btn-nueva-actividad" class="btn btn-verde {{ ($planActivo || $planCompletado) ? 'btn-deshabilitado' : '' }}" 
                        {{ ($planActivo || $planCompletado) ? 'disabled' : '' }}
                        title="{{ $planActivo ? 'No se pueden registrar nuevas actividades en un plan activo' : ($planCompletado ? 'No se pueden registrar nuevas actividades en un plan completado' : 'Agregar nueva actividad') }}">
                    <i data-lucide="plus-circle"></i> Nueva Actividad
                </button>
            </div>

            @if($planActivo)
            <div class="advertencia-activo">
                <i data-lucide="alert-triangle"></i>
                <strong>⚠️ Plan Activo:</strong> No se pueden <strong>registrar</strong> ni <strong>eliminar</strong> actividades. 
                Solo está permitida la <strong>edición</strong> de actividades existentes.
                @if($planSeleccionado->hasta)
                    <br><small>El plan finaliza el {{ \Carbon\Carbon::parse($planSeleccionado->hasta)->format('d/m/Y') }}.</small>
                @endif
            </div>
            @elseif($planCompletado)
            <div class="advertencia-completado">
                <i data-lucide="check-circle"></i>
                <strong>✅ Plan Completado:</strong> Este plan ha finalizado. Solo puedes <strong>visualizar</strong> las actividades registradas.
                <br><small>No se pueden registrar, editar ni eliminar actividades.</small>
            </div>
            @endif
            @else
            <div class="plan-info-alert">
                <p style="margin: 0; font-weight: 500;">
                    <i data-lucide="alert-triangle" style="color: #ffc107;"></i>
                    No se ha seleccionado un plan vacacional. 
                    <a href="{{ route('actividades.index') }}" style="color: var(--primario);">Seleccione un plan</a> para registrar actividades.
                </p>
            </div>
            @endif

            <!-- Cronograma -->
            <div style="max-width: 1100px; width: 100%; margin-bottom: 20px;">
                <button id="btn-toggle-cronograma" class="btn btn-gris" style="border: 1px solid var(--borde);">
                    <i data-lucide="calendar"></i> Ver Cronograma de Actividades
                </button>
            </div>

            <div id="cronograma-container" class="cronograma-container">
                <div class="cronograma-grid">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <button id="cal-prev" class="btn-icon"><i data-lucide="chevron-left"></i></button>
                            <h3 id="cal-mes-ano" style="margin: 0; font-size: 1.2rem;"></h3>
                            <button id="cal-next" class="btn-icon"><i data-lucide="chevron-right"></i></button>
                        </div>
                        <div class="cronograma-calendario" style="font-weight: 600; color: var(--texto-suave); font-size: 0.9rem; margin-bottom: 10px;">
                            <div>Do</div><div>Lu</div><div>Ma</div><div>Mi</div><div>Ju</div><div>Vi</div><div>Sá</div>
                        </div>
                        <div id="cal-dias" class="cronograma-calendario"></div>
                    </div>
                    <div class="cronograma-detalles">
                        <h3>Actividades para el <span id="detalles-fecha" style="color: var(--primario);">Seleccione un día</span></h3>
                        <div id="detalles-actividades-lista" style="margin-top: 15px; max-height: 300px; overflow-y: auto;">
                            <p style="color: var(--texto-claro); font-style: italic;">Haz clic en un día marcado en verde para ver el desglose.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Actividades del Plan -->
            @if($planSeleccionado)
            <div class="contenedor-tabla" style="max-width: 1100px; width: 100%; margin-top: 30px;">
                <div class="titulo-form">
                    <h3>Actividades del Plan: {{ $planSeleccionado->nombre }}</h3>
                    <div class="busqueda-rapida">
                        <i data-lucide="search"></i>
                        <input type="text" id="input-filtro-tabla" class="input-busqueda" placeholder="Buscar actividad...">
                    </div>
                </div>

                <table id="tabla-actividades">
                    <thead>
                        <tr>
                            <th>Tipo de actividad</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Sede</th>
                            <th>Responsable</th>
                            <th>Cantidad</th>
                            <th class="th-acciones" style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        @forelse($actividadesDelPlan as $actividad)
                        @php
                            $nombreSede = 'Sin sede';
                            if (isset($actividad->sede) && is_object($actividad->sede)) {
                                if (isset($actividad->sede->nombre_completo)) {
                                    $nombreSede = $actividad->sede->nombre_completo;
                                }
                            }
                            
                            $nombreEncargado = 'Sin responsable';
                            if (isset($actividad->encargado) && is_object($actividad->encargado)) {
                                if (isset($actividad->encargado->nombre)) {
                                    $nombreEncargado = $actividad->encargado->nombre;
                                }
                            } elseif (isset($actividad->responsable) && !empty($actividad->responsable)) {
                                $nombreEncargado = $actividad->responsable;
                            }
                            
                            $puedeEditar = !$planActivo && !$planCompletado;
                            $puedeEliminar = !$planActivo && !$planCompletado;
                        @endphp
                        <tr>
                            <td><strong>{{ $actividad->actividad }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }}</td>
                            <td>{{ isset($actividad->descripcion) ? \Illuminate\Support\Str::limit($actividad->descripcion, 50) : '' }}</td>
                            <td>{{ $nombreSede }}</td>
                            <td>{{ $nombreEncargado }}</td>
                            <td><span class="count-badge">{{ $actividad->cantidad }}</span></td>
                            <td class="td-acciones" style="text-align: center;">
                                <div class="tabla-acciones">
                                    <button class="btn-icon" title="Ver" onclick="verActividad({{ $actividad->id }})">
                                        <i data-lucide="eye" style="color: #0984e3;"></i>
                                    </button>
                                    @if($puedeEditar)
                                        <button class="btn-icon" title="Editar" onclick="editarActividad({{ $actividad->id }})">
                                            <i data-lucide="edit-3" style="color: #00b894;"></i>
                                        </button>
                                    @else
                                        <button class="btn-icon" title="Editar" disabled>
                                            <i data-lucide="edit-3" style="color: #b2bec3;"></i>
                                        </button>
                                    @endif
                                    @if($puedeEliminar)
                                        <button class="btn-icon" title="Eliminar" onclick="eliminarActividad({{ $actividad->id }})">
                                            <i data-lucide="trash-2" style="color: #e74c3c;"></i>
                                        </button>
                                    @else
                                        <button class="btn-icon" title="Eliminar" disabled>
                                            <i data-lucide="trash-2" style="color: #b2bec3;"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="empty-state" style="text-align: center; padding: 30px;">
                                <i data-lucide="calendar" style="display: block; margin: 0 auto 10px; width: 40px; height: 40px; color: #ddd;"></i>
                                No hay actividades registradas para este plan.
                                <br>
                                @if($planActivo)
                                <small style="color: #f59e0b;">⚠️ El plan está activo, no se pueden registrar nuevas actividades.</small>
                                @elseif($planCompletado)
                                <small style="color: #1e40af;">📋 El plan está completado, solo se pueden visualizar las actividades.</small>
                                @else
                                <small>Haga clic en "Nueva Actividad" para agregar la primera.</small>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
        </main>

        @include('layouts.footer')
    </div>

    <!-- ============================================ -->
    <!-- MODALES -->
    <!-- ============================================ -->

    <!-- Modal Nueva Actividad -->
    <div id="modal-nueva-actividad" class="modal-overlay">
        <div class="modal-content">
            <div class="titulo-form">
                <h2>Nueva Actividad</h2>
                <button type="button" class="btn-icon" onclick="cerrarModalActividad()" style="color: var(--error);">
                    <i data-lucide="x-circle"></i>
                </button>
            </div>
            <form id="form-nueva-actividad" action="{{ route('actividades.store') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="plan_vacacional_id" value="{{ $planSeleccionado ? $planSeleccionado->id : '' }}">
                <div class="rejilla-form">
                    <div class="grupo-campo grupo-campo-completo">
                        <label for="actividad">Tipo de actividad</label>
                        <select name="actividad" id="actividad" class="entrada" required>
                            <option value="">Seleccione...</option>
                            <option value="taller">Taller Formativo</option>
                            <option value="paseo">Paseo</option>
                            <option value="visitas">Visitas Guiadas</option>
                            <option value="proyaccion">Proyeccion de pelicula</option>
                            <option value="hemeroteca">Hemeroteca</option>
                        </select>
                    </div>
                    <div class="grupo-campo">
                        <label for="hora">Hora</label>
                        <input type="time" name="hora" id="hora" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label for="fecha">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label for="objetivo">Objetivo</label>
                        <select name="objetivo" id="objetivo" class="entrada" required>
                            <option value="">Seleccione...</option>
                            <option value="promocion">Promocion</option>
                        </select>
                    </div>
                    <div class="grupo-campo">
                        <label for="cantidad">Cantidad de Personas</label>
                        <input type="number" name="cantidad" id="cantidad" class="entrada" min="1" required placeholder="Número de participantes">
                    </div>
                    <div class="grupo-campo">
                        <label for="sede_id">Sede</label>
                        <select name="sede_id" id="sede_id" class="entrada" required>
                            <option value="">Seleccione una sede</option>
                            @foreach($sedes as $sede)
                                @php
                                    $tieneEncargado = isset($sede->encargado) && is_object($sede->encargado);
                                @endphp
                                <option value="{{ $sede->id }}" 
                                    data-encargado-id="{{ $sede->encargado_id }}"
                                    data-encargado-nombre="{{ $tieneEncargado ? $sede->encargado->nombre : '' }}"
                                    data-encargado-telefono="{{ $tieneEncargado ? $sede->encargado->telefono : '' }}"
                                    data-encargado-cedula="{{ $tieneEncargado ? $sede->encargado->cedula : '' }}"
                                    class="{{ !$tieneEncargado ? 'option-sin-encargado' : '' }}">
                                    {{ $sede->comunidad }} - {{ $sede->biblioteca }}
                                    @if(!$tieneEncargado) ⚠️ (Sin encargado) @endif
                                </option>
                            @endforeach
                        </select>
                        <div id="error-sede-modal-nueva-actividad" class="error-sede"></div>
                    </div>
                    <div class="grupo-campo">
                        <label for="responsable">Responsable</label>
                        <input type="text" name="responsable" id="responsable" class="entrada" required placeholder="Nombre del responsable">
                    </div>
                    <div class="grupo-campo grupo-campo-completo">
                        <label for="descripcion">Descripción de la actividad</label>
                        <textarea name="descripcion" id="descripcion" class="area-texto" rows="3" placeholder="Descripción detallada de la actividad..."></textarea>
                    </div>
                </div>
                <div class="fila-botones">
                    <button type="button" class="btn btn-gris" onclick="cerrarModalActividad()">Cancelar</button>
                    <button type="submit" id="btn-guardar-actividad" class="btn btn-azul">
                        <i data-lucide="save"></i> Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Ver Actividad -->
    <div id="modal-ver-guia" class="modal-overlay">
        <div class="modal-content">
            <div class="titulo-form">
                <h2>Detalles de la Actividad</h2>
                <button type="button" class="btn-icon" onclick="cerrarModalVer()" style="color: var(--error);">
                    <i data-lucide="x-circle"></i>
                </button>
            </div> 
            <div class="modal-body">
                <p><strong>Actividad:</strong> <span id="ver-actividad"></span></p>
                <p><strong>Responsable:</strong> <span id="ver-responsable"></span></p>
                <p><strong>Hora:</strong> <span id="ver-hora"></span></p>
                <p><strong>Cantidad de Personas:</strong> <span id="ver-cantidad"></span></p>
                <p><strong>Sede:</strong> <span id="ver-sede"></span></p>
                <p><strong>Fecha:</strong> <span id="ver-fecha"></span></p>
                <p><strong>Objetivo:</strong> <span id="ver-objetivo"></span></p>
                <p><strong>Descripción:</strong> <span id="ver-descripcion"></span></p>
            </div>
            <div class="fila-botones">
                <button type="button" class="btn btn-gris" onclick="cerrarModalVer()">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Modal Editar Actividad -->
    <div id="modal-editar-guia" class="modal-overlay">
        <div class="modal-content" style="max-width: 700px;">
            <div class="titulo-form" style="padding-bottom: 8px; margin-bottom: 10px;">
                <h2 style="font-size: 1.1rem; margin: 0;">Editar Actividad</h2>
                <button type="button" class="btn-icon" onclick="cerrarModalEditar()" style="color: var(--error);">
                    <i data-lucide="x-circle"></i>
                </button>
            </div>
            <form id="form-editar-guia" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <div class="rejilla-form" style="grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div class="grupo-campo grupo-campo-completo">
                        <label for="edit_actividad" style="font-size: 0.85rem;">Tipo de actividad</label>
                        <select name="edit_actividad" id="edit_actividad" class="entrada" required>
                            <option value="">Seleccione...</option>
                            <option value="taller">Taller Formativo</option>
                            <option value="paseo">Paseo</option>
                            <option value="visitas">Visitas Guiadas</option>
                            <option value="proyaccion">Proyeccion de pelicula</option>
                            <option value="hemeroteca">Hemeroteca</option>
                        </select>
                    </div>
                    <div class="grupo-campo">
                        <label for="edit_hora" style="font-size: 0.85rem;">Hora</label>
                        <input type="time" name="edit_hora" id="edit_hora" class="entrada" required 
                            style="font-size: 0.85rem; padding: 5px 8px;">
                    </div>
                    <div class="grupo-campo">
                        <label for="edit_fecha" style="font-size: 0.85rem;">Fecha</label>
                        <input type="date" name="edit_fecha" id="edit_fecha" class="entrada" required style="font-size: 0.85rem; padding: 5px 8px;">
                    </div>
                    <div class="grupo-campo">
                        <label for="edit_objetivo">Objetivo</label>
                        <select name="edit_objetivo" id="edit_objetivo" class="entrada" required style="font-size: 0.85rem; padding: 5px 8px;">
                            <option value="">Seleccione...</option>
                            <option value="promocion">Promocion</option>
                        </select>
                    </div>
                    <div class="grupo-campo">
                        <label for="edit_cantidad" style="font-size: 0.85rem;">Cantidad</label>
                        <input type="number" name="edit_cantidad" id="edit_cantidad" class="entrada" min="1" required style="font-size: 0.85rem; padding: 5px 8px;">
                    </div>
                    <div class="grupo-campo">
                        <label for="edit_sede_id" style="font-size: 0.85rem;">Sede</label>
                        <select name="edit_sede_id" id="edit_sede_id" class="entrada" required style="font-size: 0.85rem; padding: 5px 8px;">
                            <option value="">Seleccione una sede</option>
                            @foreach($sedes as $sede)
                                @php
                                    $tieneEncargado = isset($sede->encargado) && is_object($sede->encargado);
                                @endphp
                                <option value="{{ $sede->id }}" 
                                    data-encargado-id="{{ $sede->encargado_id }}"
                                    data-encargado-nombre="{{ $tieneEncargado ? $sede->encargado->nombre : '' }}"
                                    data-encargado-telefono="{{ $tieneEncargado ? $sede->encargado->telefono : '' }}"
                                    data-encargado-cedula="{{ $tieneEncargado ? $sede->encargado->cedula : '' }}"
                                    class="{{ !$tieneEncargado ? 'option-sin-encargado' : '' }}">
                                    {{ $sede->comunidad }} - {{ $sede->biblioteca }}
                                    @if(!$tieneEncargado) ⚠️ (Sin encargado) @endif
                                </option>
                            @endforeach
                        </select>
                        <div id="error-sede-modal-editar-guia" class="error-sede"></div>
                    </div>
                    <div class="grupo-campo">
                        <label for="edit_responsable" style="font-size: 0.85rem;">Responsable</label>
                        <input type="text" name="edit_responsable" id="edit_responsable" class="entrada" required style="font-size: 0.85rem; padding: 5px 8px;">
                    </div>
                    <div class="grupo-campo grupo-campo-completo">
                        <label for="edit_descripcion" style="font-size: 0.85rem;">Descripción</label>
                        <textarea name="edit_descripcion" id="edit_descripcion" class="area-texto" rows="2" style="font-size: 0.85rem; padding: 5px 8px;"></textarea>
                    </div>
                </div>
                <div class="fila-botones" style="margin-top: 10px;">
                    <button type="button" class="btn btn-gris" onclick="cerrarModalEditar()">Cancelar</button>
                    <button type="submit" id="btn-actualizar-actividad" class="btn btn-azul">
                        <i data-lucide="save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/plantilla.js') }}?v={{ filemtime(public_path('js/plantilla.js')) }}"></script>
    <script src="{{ asset('js/paginacion.js') }}"></script>
    <script>
        // Variables globales (fuera de DOMContentLoaded)
        var planActivo = {!! json_encode($planActivo) !!};
        var planCompletado = {!! json_encode($planCompletado) !!};
        var actividadesData = {!! json_encode($actividadesDelPlan) !!};
        var currentMonth = new Date().getMonth();
        var currentYear = new Date().getFullYear();
        var selectedDate = null;

        // ===== FUNCIONES DE VALIDACIÓN DE SEDE CON ENCARGADO =====
        
        function validarSedeConEncargado(selectElement, errorContainerId, submitButtonId) {
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var encargadoId = selectedOption.getAttribute('data-encargado-id');
            var encargadoNombre = selectedOption.getAttribute('data-encargado-nombre');
            var errorContainer = document.getElementById(errorContainerId);
            var submitButton = document.getElementById(submitButtonId);
            
            if (!encargadoId || !encargadoNombre) {
                if (errorContainer) {
                    errorContainer.textContent = '❌ Esta sede no tiene un encargado asignado. Seleccione otra sede o asigne un encargado.';
                    errorContainer.style.display = 'block';
                }
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.style.opacity = '0.5';
                    submitButton.style.cursor = 'not-allowed';
                    submitButton.title = 'Debe seleccionar una sede con encargado';
                }
                return false;
            } else {
                if (errorContainer) {
                    errorContainer.style.display = 'none';
                }
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.style.opacity = '1';
                    submitButton.style.cursor = 'pointer';
                    submitButton.title = '';
                }
                return true;
            }
        }

        function cargarDatosEncargadoYValidar(selectElement, responsableInput, errorContainerId, submitButtonId) {
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var encargadoNombre = selectedOption.getAttribute('data-encargado-nombre') || '';
            var encargadoTelefono = selectedOption.getAttribute('data-encargado-telefono') || '';
            var encargadoCedula = selectedOption.getAttribute('data-encargado-cedula') || '';
            
            if (encargadoNombre) {
                var nombreCompleto = encargadoNombre;
                if (encargadoCedula) {
                    nombreCompleto += ' (Cédula: ' + encargadoCedula + ')';
                }
                if (encargadoTelefono) {
                    nombreCompleto += ' - Tel: ' + encargadoTelefono;
                }
                responsableInput.value = nombreCompleto;
            } else {
                responsableInput.value = '';
                responsableInput.placeholder = '⚠️ No hay encargado asignado a esta sede';
            }
            
            validarSedeConEncargado(selectElement, errorContainerId, submitButtonId);
        }

        // ===== FUNCIONES GLOBALES =====
        
        window.verActividad = function(id) {
            fetch('/actividades/' + id)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    document.getElementById('ver-actividad').textContent = data.actividad || 'Sin nombre';
                    document.getElementById('ver-responsable').textContent = data.responsable || 'Sin responsable';
                    document.getElementById('ver-hora').textContent = formatearHora(data.hora);
                    document.getElementById('ver-cantidad').textContent = data.cantidad || 0;
                    var nombreSede = 'Sin sede';
                    if (data.sede && data.sede.nombre_completo) {
                        nombreSede = data.sede.nombre_completo;
                    }
                    document.getElementById('ver-sede').textContent = nombreSede;
                    document.getElementById('ver-fecha').textContent = data.fecha ? new Date(data.fecha).toLocaleDateString('es-ES') : 'Sin fecha';
                    document.getElementById('ver-objetivo').textContent = data.objetivo || 'Sin objetivo';
                    document.getElementById('ver-descripcion').textContent = data.descripcion || 'Sin descripción';
                    document.getElementById('modal-ver-guia').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                })
                .catch(function(error) {
                    alert('Error al cargar los datos de la actividad: ' + error);
                });
        };

        window.cerrarModalVer = function() {
            document.getElementById('modal-ver-guia').style.display = 'none';
            document.body.style.overflow = '';
        };

        window.editarActividad = function(id) {
            if (planActivo) {
                Swal.fire('⚠️ Plan activo', 'No se pueden editar actividades de un plan activo.', 'warning');
                return;
            }
            if (planCompletado) {
                Swal.fire('📋 Plan completado', 'No se pueden editar actividades de un plan completado.', 'info');
                return;
            }

            fetch('/actividades/' + id)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    document.getElementById('edit_actividad').value = data.actividad || '';
                    document.getElementById('edit_hora').value = formatearHora(data.hora);
                    document.getElementById('edit_fecha').value = data.fecha || '';
                    document.getElementById('edit_objetivo').value = data.objetivo || 'promocion';
                    document.getElementById('edit_cantidad').value = data.cantidad || 1;
                    
                    var sedeSelect = document.getElementById('edit_sede_id');
                    if (data.sede_id) {
                        sedeSelect.value = data.sede_id;
                        // Disparar validación después de cargar
                        setTimeout(function() {
                            var responsableInput = document.getElementById('edit_responsable');
                            cargarDatosEncargadoYValidar(
                                sedeSelect, 
                                responsableInput, 
                                'error-sede-modal-editar-guia',
                                'btn-actualizar-actividad'
                            );
                        }, 100);
                    }
                    
                    document.getElementById('edit_descripcion').value = data.descripcion || '';
                    document.getElementById('form-editar-guia').action = '/actividades/' + id;
                    document.getElementById('modal-editar-guia').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                })
                .catch(function(error) {
                    alert('Error al cargar los datos de la actividad: ' + error);
                });
        };

        window.cerrarModalEditar = function() {
            document.getElementById('modal-editar-guia').style.display = 'none';
            document.body.style.overflow = '';
        };

        window.eliminarActividad = function(id) {
            if (planActivo) {
                Swal.fire('⚠️ Plan activo', 'No se pueden eliminar actividades de un plan activo. Desactive el plan primero.', 'warning');
                return;
            }
            if (planCompletado) {
                Swal.fire('📋 Plan completado', 'No se pueden eliminar actividades de un plan completado.', 'info');
                return;
            }

            Swal.fire({
                title: '¿Eliminar actividad?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                if (!result.isConfirmed) return;

                fetch('/actividades/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success) {
                        Swal.fire('Eliminada', data.message || 'Actividad eliminada correctamente', 'success')
                            .then(function() { location.reload(); });
                    } else {
                        Swal.fire('❌ Error', data.message || 'No se pudo eliminar la actividad', 'error');
                    }
                })
                .catch(function() {
                    Swal.fire('❌ Error', 'Ocurrió un error de conexión al eliminar la actividad', 'error');
                });
            });
        };

        window.cerrarModalActividad = function() {
            document.getElementById('modal-nueva-actividad').style.display = 'none';
            document.body.style.overflow = '';
        };

        // ===== FUNCIONES AUXILIARES =====

        function formatearHora(hora) {
            if (!hora) return '00:00';
            if (hora.length > 5) {
                return hora.substring(0, 5);
            }
            return hora;
        }

        function getActividadesPorFecha(fecha) {
            if (!fecha) return [];
            var fechaStr = new Date(fecha).toISOString().split('T')[0];
            var resultado = [];
            for (var i = 0; i < actividadesData.length; i++) {
                var act = actividadesData[i];
                var actFecha = new Date(act.fecha).toISOString().split('T')[0];
                if (actFecha === fechaStr) {
                    resultado.push(act);
                }
            }
            return resultado;
        }

        function mostrarDetallesFecha(fecha) {
            var fechaStr = fecha.toISOString().split('T')[0];
            var actividades = getActividadesPorFecha(fecha);
            var lista = document.getElementById('detalles-actividades-lista');
            var tituloFecha = document.getElementById('detalles-fecha');
            
            if (tituloFecha) {
                tituloFecha.textContent = fecha.toLocaleDateString('es-ES', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }
            
            if (lista) {
                if (actividades.length === 0) {
                    lista.innerHTML = '<p style="color: var(--texto-claro); font-style: italic;">No hay actividades programadas para este día.</p>';
                    return;
                }
                
                var html = '';
                for (var i = 0; i < actividades.length; i++) {
                    var act = actividades[i];
                    var nombreSede = 'Sin sede';
                    if (act.sede && act.sede.nombre_completo) {
                        nombreSede = act.sede.nombre_completo;
                    }
                    html += '<div class="actividad-mini">' +
                                '<div>' +
                                    '<span class="actividad-hora">' + formatearHora(act.hora) + '</span>' +
                                    '<span class="actividad-nombre"> - ' + (act.actividad || 'Sin nombre') + '</span>' +
                                '</div>' +
                                '<div style="font-size: 0.75rem; color: var(--texto-suave); margin-top: 2px;">' +
                                    (act.responsable || 'Sin responsable') + ' · ' + nombreSede +
                                '</div>' +
                            '</div>';
                }
                lista.innerHTML = html;
            }
        }

        function renderCalendar(month, year) {
            var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                          'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            
            var mesAnoElement = document.getElementById('cal-mes-ano');
            if (mesAnoElement) {
                mesAnoElement.textContent = meses[month] + ' ' + year;
            }
            
            var firstDay = new Date(year, month, 1).getDay();
            var daysInMonth = new Date(year, month + 1, 0).getDate();
            var daysInPrevMonth = new Date(year, month, 0).getDate();
            
            var container = document.getElementById('cal-dias');
            if (!container) return;
            
            container.innerHTML = '';
            
            for (var i = firstDay - 1; i >= 0; i--) {
                var day = daysInPrevMonth - i;
                var diaElement = document.createElement('div');
                diaElement.className = 'cronograma-dia otro-mes';
                diaElement.textContent = day;
                container.appendChild(diaElement);
            }
            
            for (var day = 1; day <= daysInMonth; day++) {
                var dateObj = new Date(year, month, day);
                var fechaStr = dateObj.toISOString().split('T')[0];
                var actividades = getActividadesPorFecha(dateObj);
                
                var diaElement = document.createElement('div');
                diaElement.className = 'cronograma-dia';
                diaElement.textContent = day;
                
                if (actividades.length > 0) {
                    diaElement.classList.add('tiene-actividad');
                }
                
                if (selectedDate && fechaStr === selectedDate) {
                    diaElement.classList.add('seleccionado');
                }
                
                (function(diaElement, day, month, year) {
                    diaElement.addEventListener('click', function() {
                        var fechaClick = new Date(year, month, day);
                        var fechaStrClick = fechaClick.toISOString().split('T')[0];
                        selectedDate = fechaStrClick;
                        
                        var dias = document.querySelectorAll('.cronograma-dia');
                        for (var j = 0; j < dias.length; j++) {
                            dias[j].classList.remove('seleccionado');
                        }
                        this.classList.add('seleccionado');
                        
                        mostrarDetallesFecha(fechaClick);
                    });
                })(diaElement, day, month, year);
                
                container.appendChild(diaElement);
            }
            
            var totalDays = firstDay + daysInMonth;
            var remainingDays = 7 - (totalDays % 7);
            if (remainingDays < 7) {
                for (var day = 1; day <= remainingDays; day++) {
                    var diaElement = document.createElement('div');
                    diaElement.className = 'cronograma-dia otro-mes';
                    diaElement.textContent = day;
                    container.appendChild(diaElement);
                }
            }
            
            if (selectedDate) {
                var fechaObj = new Date(selectedDate + 'T00:00:00');
                if (fechaObj.getMonth() === month && fechaObj.getFullYear() === year) {
                    mostrarDetallesFecha(fechaObj);
                }
            }
        }

        // ===== INICIALIZACIÓN =====
        
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            renderCalendar(currentMonth, currentYear);
            
            // Configurar el toggle del cronograma
            var toggleBtn = document.getElementById('btn-toggle-cronograma');
            var container = document.getElementById('cronograma-container');
            
            if (toggleBtn && container) {
                toggleBtn.addEventListener('click', function() {
                    if (container.style.display === 'none' || container.style.display === '') {
                        container.style.display = 'block';
                        renderCalendar(currentMonth, currentYear);
                        toggleBtn.innerHTML = '<i data-lucide="calendar"></i> Ocultar Cronograma';
                    } else {
                        container.style.display = 'none';
                        toggleBtn.innerHTML = '<i data-lucide="calendar"></i> Ver Cronograma de Actividades';
                    }
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            }
            
            // Configurar navegación del calendario
            var prevBtn = document.getElementById('cal-prev');
            var nextBtn = document.getElementById('cal-next');
            
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    if (currentMonth === 0) {
                        currentMonth = 11;
                        currentYear--;
                    } else {
                        currentMonth--;
                    }
                    renderCalendar(currentMonth, currentYear);
                });
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    if (currentMonth === 11) {
                        currentMonth = 0;
                        currentYear++;
                    } else {
                        currentMonth++;
                    }
                    renderCalendar(currentMonth, currentYear);
                });
            }

            // ===== VALIDACIÓN DE SEDES CON ENCARGADO =====

            // Event listeners para selects de sede en el modal NUEVO
            var sedeSelect = document.getElementById('sede_id');
            if (sedeSelect) {
                sedeSelect.addEventListener('change', function() {
                    var responsableInput = document.getElementById('responsable');
                    cargarDatosEncargadoYValidar(
                        this, 
                        responsableInput, 
                        'error-sede-modal-nueva-actividad',
                        'btn-guardar-actividad'
                    );
                });
                
                // Trigger inicial si ya hay valor
                if (sedeSelect.value) {
                    var responsableInput = document.getElementById('responsable');
                    cargarDatosEncargadoYValidar(
                        sedeSelect, 
                        responsableInput, 
                        'error-sede-modal-nueva-actividad',
                        'btn-guardar-actividad'
                    );
                } else {
                    // Deshabilitar botón por defecto si no hay sede seleccionada
                    var submitBtn = document.getElementById('btn-guardar-actividad');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.style.opacity = '0.5';
                        submitBtn.style.cursor = 'not-allowed';
                    }
                }
            }

            // Event listeners para selects de sede en el modal EDITAR
            var editSedeSelect = document.getElementById('edit_sede_id');
            if (editSedeSelect) {
                editSedeSelect.addEventListener('change', function() {
                    var responsableInput = document.getElementById('edit_responsable');
                    cargarDatosEncargadoYValidar(
                        this, 
                        responsableInput, 
                        'error-sede-modal-editar-guia',
                        'btn-actualizar-actividad'
                    );
                });
                
                // Trigger inicial si ya hay valor
                if (editSedeSelect.value) {
                    var responsableInput = document.getElementById('edit_responsable');
                    cargarDatosEncargadoYValidar(
                        editSedeSelect, 
                        responsableInput, 
                        'error-sede-modal-editar-guia',
                        'btn-actualizar-actividad'
                    );
                }
            }

            // ===== VALIDACIÓN PREVIA AL SUBMIT =====

            // Validación para formulario NUEVO
            var formNueva = document.getElementById('form-nueva-actividad');
            if (formNueva) {
                formNueva.addEventListener('submit', function(e) {
                    var sedeSelect = document.getElementById('sede_id');
                    var encargadoId = sedeSelect.options[sedeSelect.selectedIndex].getAttribute('data-encargado-id');
                    var encargadoNombre = sedeSelect.options[sedeSelect.selectedIndex].getAttribute('data-encargado-nombre');
                    
                    if (!encargadoId || !encargadoNombre) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Sede sin encargado',
                            text: 'La sede seleccionada no tiene un encargado asignado. Por favor, seleccione otra sede o asigne un encargado primero.',
                            confirmButtonColor: '#e74c3c'
                        });
                        return false;
                    }
                });
            }

            // Validación para formulario EDITAR
            var formEditar = document.getElementById('form-editar-guia');
            if (formEditar) {
                formEditar.addEventListener('submit', function(e) {
                    var sedeSelect = document.getElementById('edit_sede_id');
                    var encargadoId = sedeSelect.options[sedeSelect.selectedIndex].getAttribute('data-encargado-id');
                    var encargadoNombre = sedeSelect.options[sedeSelect.selectedIndex].getAttribute('data-encargado-nombre');
                    
                    if (!encargadoId || !encargadoNombre) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Sede sin encargado',
                            text: 'La sede seleccionada no tiene un encargado asignado. Por favor, seleccione otra sede o asigne un encargado primero.',
                            confirmButtonColor: '#e74c3c'
                        });
                        return false;
                    }
                });
            }

            // Modal Nueva Actividad
            var btnNuevaActividad = document.getElementById('btn-nueva-actividad');
            if (btnNuevaActividad) {
                btnNuevaActividad.addEventListener('click', function() {
                    if (planActivo) {
                        Swal.fire('⚠️ Plan activo', 'No se pueden registrar nuevas actividades en un plan activo. Desactive el plan primero.', 'warning');
                        return;
                    }
                    if (planCompletado) {
                        Swal.fire('📋 Plan completado', 'No se pueden registrar nuevas actividades en un plan completado.', 'info');
                        return;
                    }
                    document.getElementById('modal-nueva-actividad').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                    
                    // Validar sede seleccionada al abrir el modal
                    var sedeSelect = document.getElementById('sede_id');
                    if (sedeSelect && sedeSelect.value) {
                        var responsableInput = document.getElementById('responsable');
                        cargarDatosEncargadoYValidar(
                            sedeSelect, 
                            responsableInput, 
                            'error-sede-modal-nueva-actividad',
                            'btn-guardar-actividad'
                        );
                    }
                });
            }

            // Cerrar modales con click fuera
            var modales = document.querySelectorAll('.modal-overlay');
            for (var i = 0; i < modales.length; i++) {
                modales[i].addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                });
            }

            // Cerrar con ESC
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    var modalesAbiertos = document.querySelectorAll('.modal-overlay');
                    for (var i = 0; i < modalesAbiertos.length; i++) {
                        if (modalesAbiertos[i].style.display === 'block') {
                            modalesAbiertos[i].style.display = 'none';
                            document.body.style.overflow = '';
                        }
                    }
                }
            });

            // Filtro de tabla
            var inputFiltro = document.getElementById('input-filtro-tabla');
            if (inputFiltro) {
                inputFiltro.addEventListener('keyup', function() {
                    var filter = this.value.toLowerCase();
                    var rows = document.querySelectorAll('#tabla-body tr');
                    for (var i = 0; i < rows.length; i++) {
                        var text = rows[i].textContent.toLowerCase();
                        rows[i].style.display = text.indexOf(filter) !== -1 ? '' : 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>