<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Bitácora del Sistema - Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
</head>
<body>

    <?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div id="main-wrapper">
        <main id="main-content">

            <?php echo $__env->make('layouts.mensajes', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

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
                <form method="GET" action="<?php echo e(route('bitacora.index')); ?>" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
                    <div class="grupo-campo">
                        <label for="user_id">Usuario</label>
                        <select name="user_id" id="user_id" class="entrada">
                            <option value="">Todos los usuarios</option>
                            <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($usuario->id); ?>" <?php echo e(request('user_id') == $usuario->id ? 'selected' : ''); ?>>
                                    <?php echo e($usuario->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="grupo-campo">
                        <label for="modulo">Módulo</label>
                        <select name="modulo" id="modulo" class="entrada">
                            <option value="">Todos los módulos</option>
                            <?php $__currentLoopData = $modulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($modulo); ?>" <?php echo e(request('modulo') == $modulo ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($modulo)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="grupo-campo">
                        <label for="accion">Acción</label>
                        <select name="accion" id="accion" class="entrada">
                            <option value="">Todas las acciones</option>
                            <?php $__currentLoopData = $acciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($accion); ?>" <?php echo e(request('accion') == $accion ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($accion)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="grupo-campo">
                        <label for="fecha_desde">Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" class="entrada" value="<?php echo e(request('fecha_desde')); ?>">
                    </div>

                    <div class="grupo-campo">
                        <label for="fecha_hasta">Hasta</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" class="entrada" value="<?php echo e(request('fecha_hasta')); ?>">
                    </div>

                    <div class="grupo-campo" style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-azul">
                            <i data-lucide="search"></i> Filtrar
                        </button>
                        <a href="<?php echo e(route('bitacora.index')); ?>" class="btn btn-gris">
                            <i data-lucide="rotate-ccw"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <div class="contenedor-tabla">
                <div class="titulo-form">
                    <h3>Registro de Actividad</h3>
                    <span style="color: var(--texto-suave); font-size: 0.9rem;">
                        <?php echo e($bitacoras->total()); ?> registros encontrados
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
                            <?php $__empty_1 = true; $__currentLoopData = $bitacoras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="white-space: nowrap;"><?php echo e($registro->created_at->format('d/m/Y H:i:s')); ?></td>
                                <td><?php echo e($registro->user_name); ?></td>
                                <td>
                                    <span class="badge" style="padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
                                        <?php if(in_array($registro->accion, ['login', 'create'])): ?>
                                            background: #e6f4ea; color: #137333;
                                        <?php elseif(in_array($registro->accion, ['logout'])): ?>
                                            background: #fff3e0; color: #e65100;
                                        <?php elseif(in_array($registro->accion, ['login-fallido'])): ?>
                                            background: #fdecea; color: #b71c1c;
                                        <?php elseif(in_array($registro->accion, ['update'])): ?>
                                            background: #e3f2fd; color: #1565c0;
                                        <?php elseif(in_array($registro->accion, ['delete'])): ?>
                                            background: #fce8e6; color: #c5221f;
                                        <?php elseif(in_array($registro->accion, ['backup'])): ?>
                                            background: #e0f2f1; color: #00695c;
                                        <?php elseif(in_array($registro->accion, ['backup-fallido'])): ?>
                                            background: #fff8e1; color: #b71c1c;
                                        <?php else: ?>
                                            background: #f3e5f5; color: #7b1fa2;
                                        <?php endif; ?>
                                    ">
                                        <?php echo e($registro->accion); ?>

                                    </span>
                                </td>
                                <td><?php echo e(ucfirst($registro->modulo)); ?></td>
                                <td style="max-width: 400px; word-wrap: break-word;"><?php echo e($registro->descripcion); ?></td>
                                <td style="font-size: 0.8rem; color: var(--texto-suave);"><?php echo e($registro->ip_address); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--texto-suave); padding: 40px;">
                                    No se encontraron registros con los filtros seleccionados.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="paginacion-container" style="margin-top: 20px;">
                    <div class="info-paginacion">
                        Mostrando <?php echo e($bitacoras->firstItem()); ?> a <?php echo e($bitacoras->lastItem()); ?> de <?php echo e($bitacoras->total()); ?> registros
                    </div>
                    <div class="botones-paginacion">
                        <?php echo e($bitacoras->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>

        </main>

        <?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <script src="<?php echo e(asset('js/plantilla.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
