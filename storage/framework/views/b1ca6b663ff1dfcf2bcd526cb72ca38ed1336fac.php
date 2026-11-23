<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($reporte['titulo']); ?> - Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
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

        /* Panel de resumen (contadores de colores) */
        .resumen-items {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin: 15px 0;
        }
        .resumen-item {
            flex: 1;
            min-width: 140px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            text-align: center;
        }
        .resumen-item .valor {
            font-size: 1.6rem;
            font-weight: 700;
            display: block;
            color: var(--primario);
        }
        .resumen-item .label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .resumen-item.rojo {
            background: #fee2e2;
            border-color: #f87171;
        }
        .resumen-item.rojo .valor { color: #991b1b; }
        .resumen-item.amarillo {
            background: #fef3c7;
            border-color: #fbbf24;
        }
        .resumen-item.amarillo .valor { color: #92400e; }
        .resumen-item.verde {
            background: #d1fae5;
            border-color: #34d399;
        }
        .resumen-item.verde .valor { color: #065f46; }
        .resumen-item.azul {
            background: #dbeafe;
            border-color: #60a5fa;
        }
        .resumen-item.azul .valor { color: #1e40af; }

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

        .resumen-reporte {
            padding: 14px 5px 4px;
            font-weight: 600;
            color: var(--texto-suave);
        }

        /* Al imprimir: ocultar todo lo que no sea el reporte en sí */
        @media  print {
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
    <?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div id="main-wrapper">
        <main id="main-content">

            <?php echo $__env->make('layouts.mensajes', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <div class="header-acciones">
                <div>
                    <h1><?php echo e($reporte['titulo']); ?></h1>
                    <p style="color: var(--texto-suave);">Biblioteca Dr. Félix Pifano · Generado el <?php echo e(date('d/m/Y h:i A')); ?></p>
                </div>
            </div>

            
            <div class="caja-filtros no-imprimir">
                <form method="GET" action="<?php echo e(route('prestamo.reportes')); ?>">
                    <div class="campo">
                        <label for="tipo-select">Tipo de reporte</label>
                        <select name="tipo" id="tipo-select" onchange="this.form.submit()">
                            <option value="general"     <?php echo e(request('tipo', 'general') === 'general'     ? 'selected' : ''); ?>>📋 Listado general</option>
                            <option value="vencidos"    <?php echo e(request('tipo') === 'vencidos'    ? 'selected' : ''); ?>>🔴 Préstamos vencidos</option>
                            <option value="sancionados" <?php echo e(request('tipo') === 'sancionados' ? 'selected' : ''); ?>>⚠️ Con sanción</option>
                            <option value="por_lector"  <?php echo e(request('tipo') === 'por_lector'  ? 'selected' : ''); ?>>👤 Por lector</option>
                            <option value="por_libro"   <?php echo e(request('tipo') === 'por_libro'   ? 'selected' : ''); ?>>📖 Por libro</option>
                        </select>
                    </div>

                    <?php if(request('tipo', 'general') === 'general'): ?>
                        <div class="campo">
                            <label for="desde">Desde</label>
                            <input type="date" name="desde" id="desde" value="<?php echo e(request('desde')); ?>">
                        </div>
                        <div class="campo">
                            <label for="hasta">Hasta</label>
                            <input type="date" name="hasta" id="hasta" value="<?php echo e(request('hasta')); ?>">
                        </div>
                        <div class="campo">
                            <label for="estatus">Estatus</label>
                            <select name="estatus" id="estatus">
                                <option value="">Todos</option>
                                <option value="pendiente" <?php echo e(request('estatus') === 'pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                                <option value="entregado" <?php echo e(request('estatus') === 'entregado' ? 'selected' : ''); ?>>Entregado</option>
                            </select>
                        </div>
                    <?php endif; ?>

                   
                    <div class="campo">
                        <a href="<?php echo e(route('prestamo.reportes', ['tipo' => request('tipo', 'general')])); ?>" class="btn btn-gris">
                            <i data-lucide="x"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>

           

            
            
            <div class="contenedor-tabla">
                <div class="header-tabla">
                    <div class="titulo-seccion"><h3>Resultados</h3></div>
                    <div class="no-imprimir" style="display:flex; gap:10px; margin-left:auto; flex-wrap:wrap;">
                        <button type="button" class="btn btn-azul" onclick="window.print()">
                            <i data-lucide="printer"></i> Imprimir / PDF
                        </button>
                        <a href="<?php echo e($urlCsv); ?>" class="btn btn-gris">
                            <i data-lucide="file-down"></i> Excel
                        </a>
                        <a href="<?php echo e(route('prestamo.index')); ?>" class="btn btn-gris">
                            <i data-lucide="arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <?php if($reporte['vacio']): ?>
                    <div style="text-align: center; padding: 40px;">
                        <i data-lucide="inbox" size="48" style="margin-bottom: 10px; color: #9ca3af;"></i>
                        <p>No hay préstamos que cumplan con este reporte.</p>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <?php $__currentLoopData = $reporte['columnas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $columna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="<?php echo e(in_array($i, $reporte['numericas']) ? 'celda-num' : ''); ?>"><?php echo e($columna); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $reporte['grupos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grupo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($grupo['nombre'] !== null): ?>
                                    <tr class="fila-grupo">
                                        <td colspan="<?php echo e(count($reporte['columnas'])); ?>">
                                            <?php echo e($reporte['etiquetaGrupo']); ?>: <?php echo e($grupo['nombre']); ?>

                                            <?php if($grupo['nota']): ?>
                                                <span>— <?php echo e($grupo['nota']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php $__currentLoopData = $grupo['filas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fila): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <?php $__currentLoopData = $fila; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $celda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <td class="
                                                <?php echo e(in_array($i, $reporte['numericas']) ? 'celda-num' : ''); ?>

                                                <?php if(is_string($celda)): ?>
                                                    <?php if(strpos($celda, '🔴') !== false || strpos($celda, '⚠️') !== false): ?>
                                                        celda-alerta
                                                    <?php elseif(strpos($celda, '🟡') !== false): ?>
                                                        celda-aviso
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            ">
                                                <?php if(is_string($celda) && strpos($celda, '🔴') !== false): ?>
                                                    <span class="badge-estatus badge-rojo"><?php echo e(str_replace('🔴 ', '', $celda)); ?></span>
                                                <?php elseif(is_string($celda) && strpos($celda, '🟡') !== false): ?>
                                                    <span class="badge-estatus badge-amarillo"><?php echo e(str_replace('🟡 ', '', $celda)); ?></span>
                                                <?php elseif(is_string($celda) && strpos($celda, '🟢') !== false): ?>
                                                    <span class="badge-estatus badge-verde"><?php echo e(str_replace('🟢 ', '', $celda)); ?></span>
                                                <?php elseif(is_string($celda) && strpos($celda, '⚠️') !== false): ?>
                                                    <span class="badge-estatus badge-rojo"><?php echo e($celda); ?></span>
                                                <?php elseif(is_string($celda) && strpos($celda, '✅') !== false): ?>
                                                    <span class="badge-estatus badge-verde"><?php echo e($celda); ?></span>
                                                <?php else: ?>
                                                    <?php echo e($celda); ?>

                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <div class="resumen-reporte"><?php echo e($reporte['resumen']); ?></div>
                <?php endif; ?>
            </div>

        </main>
        <?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        });
    </script>
    <script src="<?php echo e(asset('js/plantilla.js')); ?>"></script>
</body>
</html>