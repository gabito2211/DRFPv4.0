<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Planes Vacacionales - Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
    <style>
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
            max-width: 500px;
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
            gap: 15px;
        }
        .modal-content .grupo-campo-completo {
            grid-column: 1 / -1;
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

        .count-badge {
            background: var(--primario);
            color: white;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .plan-selector {
            margin-bottom: 30px;
            background: var(--fondo-sitio);
            padding: 20px;
            border-radius: 8px;
            max-width: 1100px;
            width: 100%;
        }

        .grafico-container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 1100px;
            width: 100%;
            margin-bottom: 30px;
            min-height: 200px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--texto-suave);
        }

        button:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .btn-accion-plan:disabled {
            opacity: 0.4 !important;
        }

        #mensaje-estado-plan {
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 4px;
            background: #f8f9fa;
            display: inline-block;
            margin-top: 8px;
        }

        #btn-editar-plan:disabled,
        #btn-eliminar-plan:disabled,
        #btn-activar-plan:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
        }

        @keyframes  spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primario);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .lista-actividades {
            margin-top: 15px;
            border-top: 1px solid #e9ecef;
            padding-top: 15px;
        }
        .lista-actividades .actividad-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            border-bottom: 1px solid #f1f3f5;
            font-size: 0.9rem;
        }
        .lista-actividades .actividad-item:last-child {
            border-bottom: none;
        }
        .lista-actividades .actividad-nombre {
            font-weight: 500;
        }
        .lista-actividades .actividad-fecha {
            color: var(--texto-suave);
        }

        .btn-ver-historial {
            background: transparent;
            border: none;
            color: #0984e3;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .btn-ver-historial:hover {
            background: #e3f2fd;
        }

        #btn-ver-historial-selector {
            background: #f0f0f0;
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #ddd;
            color: #6c5ce7;
        }
        #btn-ver-historial-selector:hover {
            background: #e8e8e8;
        }

        #btn-activar-plan {
            background: #00b894;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            display: none;
        }
        #btn-activar-plan:hover:not(:disabled) {
            background: #00a381;
        }

        /* Botón de Reportes - morado */
        .btn-reporte {
            background: #6c5ce7;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .btn-reporte:hover {
            background: #5b4bd6;
            color: white;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .modal-content .rejilla-form {
                grid-template-columns: 1fr;
            }
            .plan-selector > div {
                flex-direction: column;
            }
            .grafico-container > div {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body> 

    <?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div id="main-wrapper">
        <main id="main-content">

            <?php echo $__env->make('layouts.mensajes', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <div class="header-acciones">
                <div>
                    <h1>Planes Vacacionales</h1>
                    <p style="color: var(--texto-suave);">Gestión de planes vacacionales y sus actividades.</p>
                </div>
            </div>

            <div class="fila-botones" style="margin-bottom: 25px; max-width: 1100px; width: 100%;">
                <button id="btn-nuevo-plan" class="btn btn-verde">
                    <i data-lucide="calendar-plus"></i> Nuevo Plan Vacacional
                </button>

                <a href="<?php echo e(route('reportes.plan.index')); ?>" class="btn btn-reporte" title="Ver reportes de planes vacacionales">
                    <i data-lucide="file-bar-chart"></i> Reportes
                </a>
            </div>

            <div class="plan-selector">
                <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 250px;">
                        <label for="plan_vacacional_id" style="font-weight: 600; display: block; margin-bottom: 5px;">Seleccionar Plan Vacacional</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <select name="plan_vacacional_id" id="plan_vacacional_id" class="entrada" required style="flex: 1;">
                                <option value="">-- Seleccione un plan --</option>
                                <?php $__currentLoopData = $planesActivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php 
                                        $estadoPlan = $plan->estado;
                                        if ($estadoPlan !== 'inactivo' && $estadoPlan !== 'completado') {
                                            $hasta = \Carbon\Carbon::parse($plan->hasta);
                                            if ($hasta->isPast()) {
                                                $estadoPlan = 'completado';
                                            }
                                        }
                                     ?>
                                    <option value="<?php echo e($plan->id); ?>" <?php echo e($plan->estado == 'activo' ? 'selected' : ''); ?> data-estado="<?php echo e($estadoPlan); ?>">
                                        <?php echo e($plan->nombre); ?> (<?php echo e(\Carbon\Carbon::parse($plan->desde)->format('d/m/Y')); ?> - 
                                        <?php echo e(\Carbon\Carbon::parse($plan->hasta)->format('d/m/Y')); ?>)
                                        <?php if($estadoPlan == 'activo'): ?> 🔴 <?php endif; ?>
                                        <?php if($estadoPlan == 'completado'): ?> ✅ <?php endif; ?>
                                        <?php if($estadoPlan == 'inactivo'): ?> ⛔ <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="button" id="btn-ir-actividades" class="btn btn-azul" title="Gestionar actividades del plan">
                                <i data-lucide="arrow-right"></i> Gestionar Actividades
                            </button>
                        </div>
                        <div id="mensaje-estado-plan" style="margin-top: 8px; font-size: 0.9rem;">
                            <span style="color: var(--texto-suave);">Seleccione un plan para ver su estado</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grafico-container" id="grafico-container">
                <h3 style="margin-bottom: 20px;">
                    <i data-lucide="calendar-range" style="color: var(--primario);"></i> 
                    Resumen de Plan Vacacional
                    <span id="plan-actual-nombre" style="font-weight: normal; font-size: 0.9rem; color: var(--texto-suave);">
                        <?php if($planVigente): ?> - <?php echo e($planVigente->nombre); ?> <?php endif; ?>
                    </span>
                </h3>
                
                <?php if($planVigente): ?>
                <div id="plan-detalles" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                    <div>
                        <p style="margin: 5px 0;"><strong>Desde:</strong> <span id="detalle-desde"><?php echo e(\Carbon\Carbon::parse($planVigente->desde)->format('d/m/Y')); ?></span></p>
                        <p style="margin: 5px 0;"><strong>Hasta:</strong> <span id="detalle-hasta"><?php echo e(\Carbon\Carbon::parse($planVigente->hasta)->format('d/m/Y')); ?></span></p>
                        <p style="margin: 5px 0;"><strong>Días de duración:</strong> <span id="detalle-dias"><?php echo e(\Carbon\Carbon::parse($planVigente->desde)->diffInDays(\Carbon\Carbon::parse($planVigente->hasta)) + 1); ?></span> días</p>
                        <p style="margin: 5px 0;"><strong>Total de actividades:</strong> <span id="detalle-total"><?php echo e($planVigente->actividades->count()); ?></span></p>
                        <p style="margin: 5px 0;"><strong>Estado:</strong> 
                            <span id="detalle-estado">
                                <?php if($planVigente->estado == 'activo'): ?>
                                    <span class="badge-estatus badge-activo">🔴 Activo</span>
                                <?php elseif($planVigente->estado == 'completado'): ?>
                                    <span class="badge-estatus badge-completado">✅ Completado</span>
                                <?php else: ?>
                                    <span class="badge-estatus badge-inactivo">Inactivo</span>
                                <?php endif; ?>
                            </span>
                        </p>
                        <?php if($planVigente->estado == 'inactivo'): ?>
                        <div style="margin-top: 10px;">
                            <button onclick="activarPlan(<?php echo e($planVigente->id); ?>)" class="btn btn-verde" style="padding: 8px 16px; font-size: 0.9rem;">
                                <i data-lucide="play-circle"></i> Activar Plan
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 8px; padding: 10px;">
                        <div style="text-align: center;">
                            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primario);" id="detalle-total-grande">
                                <?php echo e($planVigente->actividades->count()); ?>

                            </div>
                            <div style="color: var(--texto-suave);">Actividades programadas</div>
                        </div>
                    </div>
                </div>

                <div id="lista-actividades-container" class="lista-actividades" style="display: none;">
                    <h4 style="margin-bottom: 10px;">📋 Lista de Actividades</h4>
                    <div id="lista-actividades"></div>
                </div>
                <?php else: ?>
                <div class="empty-state" id="empty-state">
                    <i data-lucide="calendar" size="48" style="display: block; margin: 0 auto 15px; width: 48px; height: 48px; color: #ddd;"></i>
                    <p style="font-size: 1.1rem;">No hay un plan vacacional vigente en este momento.</p>
                    <p style="font-size: 0.9rem;">Cree un nuevo plan usando el botón "Nuevo Plan Vacacional".</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="contenedor-tabla" style="max-width: 1100px; width: 100%;">
                <div class="titulo-form">
                    <h3>Historial de Planes Vacacionales</h3>
                    <div class="busqueda-rapida">
                        <i data-lucide="search"></i>
                        <input type="text" id="input-filtro-tabla" class="input-busqueda" placeholder="Buscar plan...">
                    </div>
                </div>

                <table id="tabla-planes">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Días</th>
                            <th>Actividades</th>
                            <th>Estado</th>
                            <th class="th-acciones" style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        <?php $__currentLoopData = $planesActivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php 
                            $estadoPlan = $plan->estado;
                            if ($estadoPlan !== 'inactivo' && $estadoPlan !== 'completado') {
                                $hasta = \Carbon\Carbon::parse($plan->hasta);
                                if ($hasta->isPast()) {
                                    $estadoPlan = 'completado';
                                }
                            }
                         ?>
                        <tr>
                            <td><strong><?php echo e($plan->nombre); ?></strong></td>
                            <td><?php echo e(\Carbon\Carbon::parse($plan->desde)->format('d/m/Y')); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($plan->hasta)->format('d/m/Y')); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($plan->desde)->diffInDays(\Carbon\Carbon::parse($plan->hasta)) + 1); ?></td>
                            <td><span class="count-badge"><?php echo e($plan->actividades->count()); ?></span></td>
                            <td>
                                <?php if($estadoPlan == 'activo'): ?>
                                    <span class="badge-estatus badge-activo">Activo</span>
                                <?php elseif($estadoPlan == 'completado'): ?>
                                    <span class="badge-estatus badge-completado">Completado</span>
                                <?php else: ?>
                                    <span class="badge-estatus badge-inactivo">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="td-acciones" style="text-align: center;">
                                <div class="tabla-acciones">
                                    <?php if($estadoPlan == 'completado'): ?>
                                        <!-- Completado: solo ver actividades y reporte -->
                                        <button class="btn-icon btn-accion-plan" title="Ver Actividades" onclick="irActividades(<?php echo e($plan->id); ?>)">
                                            <i data-lucide="eye" style="color: #0984e3;"></i>
                                        </button>
                                        <button class="btn-icon btn-accion-plan" title="Editar" disabled>
                                            <i data-lucide="edit-3" style="color: #b2bec3;"></i>
                                        </button>
                                        <button class="btn-icon btn-accion-plan" title="Eliminar" disabled>
                                            <i data-lucide="trash-2" style="color: #b2bec3;"></i>
                                        </button>
                                        <button class="btn-icon btn-accion-plan" title="Activar" disabled>
                                            <i data-lucide="play-circle" style="color: #b2bec3;"></i>
                                        </button>
                                        <a href="<?php echo e(route('reportes.plan.index', ['plan_id' => $plan->id])); ?>" 
                                           class="btn-icon btn-accion-plan" 
                                           title="Ver reporte de este plan">
                                            <i data-lucide="file-bar-chart" style="color: #6c5ce7;"></i>
                                        </a>
                                    <?php elseif($estadoPlan == 'activo'): ?>
                                        <!-- Activo: ver actividades y reporte -->
                                        <button class="btn-icon btn-accion-plan" title="Gestionar Actividades" onclick="irActividades(<?php echo e($plan->id); ?>)">
                                            <i data-lucide="eye" style="color: #0984e3;"></i>
                                        </button>
                                        <button class="btn-icon btn-accion-plan" title="Editar" disabled>
                                            <i data-lucide="edit-3" style="color: #b2bec3;"></i>
                                        </button>
                                        <button class="btn-icon btn-accion-plan" title="Eliminar" disabled>
                                            <i data-lucide="trash-2" style="color: #b2bec3;"></i>
                                        </button>
                                        <button class="btn-icon btn-accion-plan" title="Activar" disabled>
                                            <i data-lucide="play-circle" style="color: #b2bec3;"></i>
                                        </button>
                                        <a href="<?php echo e(route('reportes.plan.index', ['plan_id' => $plan->id])); ?>" 
                                           class="btn-icon btn-accion-plan" 
                                           title="Ver reporte de este plan">
                                            <i data-lucide="file-bar-chart" style="color: #6c5ce7;"></i>
                                        </a>
                                    <?php else: ?>
                                        <!-- Inactivo: todas las acciones disponibles + reporte -->
                                        <button class="btn-icon btn-accion-plan" title="Gestionar Actividades" onclick="irActividades(<?php echo e($plan->id); ?>)">
                                            <i data-lucide="eye" style="color: #0984e3;"></i>
                                        </button>
                                        <button class="btn-icon btn-accion-plan" title="Editar" onclick="editarPlan(<?php echo e($plan->id); ?>)">
                                            <i data-lucide="edit-3" style="color: #00b894;"></i>
                                        </button>
                                        <button class="btn-icon btn-accion-plan" title="Eliminar" onclick="eliminarPlan(<?php echo e($plan->id); ?>)">
                                            <i data-lucide="trash-2" style="color: #e74c3c;"></i>
                                        </button>
                                        <button class="btn-icon btn-accion-plan" title="Activar" onclick="activarPlan(<?php echo e($plan->id); ?>)">
                                            <i data-lucide="play-circle" style="color: #00b894;"></i>
                                        </button>
                                        <a href="<?php echo e(route('reportes.plan.index', ['plan_id' => $plan->id])); ?>" 
                                           class="btn-icon btn-accion-plan" 
                                           title="Ver reporte de este plan">
                                            <i data-lucide="file-bar-chart" style="color: #6c5ce7;"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </main>

        <?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <!-- MODALES -->
    <div id="modal-plan-vacacional" class="modal-overlay">
        <div class="modal-content">
            <div class="titulo-form">
                <h2>Nuevo Plan Vacacional</h2>
                <button type="button" class="btn-icon" onclick="cerrarModalPlan()" style="color: var(--error);">
                    <i data-lucide="x-circle"></i>
                </button>
            </div>
            <form id="form-plan-vacacional" method="POST" action="<?php echo e(route('planes.store')); ?>">
                <?php echo e(csrf_field()); ?>

                <div class="rejilla-form">
                    <div class="grupo-campo grupo-campo-completo">
                        <label for="nombre_plan">Nombre del Plan Vacacional</label>
                        <input type="text" name="nombre" id="nombre_plan" class="entrada" required placeholder="Ej: Plan Vacacional 2026">
                    </div>
                    <div class="grupo-campo">
                        <label for="desde_plan">Desde</label>
                        <input type="date" name="desde" id="desde_plan" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label for="hasta_plan">Hasta</label>
                        <input type="date" name="hasta" id="hasta_plan" class="entrada" required>
                    </div>
                </div>
                <div class="fila-botones">
                    <button type="button" class="btn btn-gris" onclick="cerrarModalPlan()">Cancelar</button>
                    <button type="submit" class="btn btn-verde">
                        <i data-lucide="save"></i> Crear Plan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-editar-plan" class="modal-overlay">
        <div class="modal-content">
            <div class="titulo-form">
                <h2>Editar Plan Vacacional</h2>
                <button type="button" class="btn-icon" onclick="cerrarModalEditarPlan()" style="color: var(--error);">
                    <i data-lucide="x-circle"></i>
                </button>
            </div>
            <form id="form-editar-plan" method="POST">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="_method" value="PUT">
                <div class="rejilla-form">
                    <div class="grupo-campo grupo-campo-completo">
                        <label for="edit_nombre_plan">Nombre del Plan Vacacional</label>
                        <input type="text" name="edit_nombre_plan" id="edit_nombre_plan" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label for="edit_desde_plan">Desde</label>
                        <input type="date" name="edit_desde_plan" id="edit_desde_plan" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label for="edit_hasta_plan">Hasta</label>
                        <input type="date" name="edit_hasta_plan" id="edit_hasta_plan" class="entrada" required>
                    </div>
                    <div class="grupo-campo grupo-campo-completo">
                        <label>
                            <input type="checkbox" name="edit_activo_plan" id="edit_activo_plan" value="on"> 
                            Activo
                        </label>
                    </div>
                </div>
                <div class="fila-botones">
                    <button type="button" class="btn btn-gris" onclick="cerrarModalEditarPlan()">Cancelar</button>
                    <button type="submit" class="btn btn-azul">
                        <i data-lucide="save"></i> Actualizar Plan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?php echo e(asset('js/plantilla.js')); ?>?v=<?php echo e(filemtime(public_path('js/plantilla.js'))); ?>"></script>
    <script src="<?php echo e(asset('js/paginacion.js')); ?>"></script> 
    <script src="<?php echo e(asset('js/actividades.js')); ?>"></script>  

</body>
</html>