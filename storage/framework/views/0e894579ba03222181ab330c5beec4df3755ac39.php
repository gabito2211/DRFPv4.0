<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Inventario - Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
    <style>
        /* Estilos básicos para el Modal */
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
        }
        .modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 800px;
            margin: 2% auto;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-height: 90vh;
            overflow-y: auto;
            padding: 20px;
        }
        /* Estilos para el Modal de Ver */
        .modal-ver-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 2001;
            backdrop-filter: blur(3px);
        }
        .modal-ver-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 700px;
            margin: 3% auto;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-height: 85vh;
            overflow-y: auto;
            padding: 20px;
        }
        .ver-detalle-item {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 0;
        }
        .ver-detalle-label {
            font-weight: 600;
            width: 140px;
            flex-shrink: 0;
            color: #2c3e50;
        }
        .ver-detalle-valor {
            color: #34495e;
            word-break: break-word;
        }
        .ver-estado-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .ver-estado-bueno { background: #d1ecf1; color: #0c5460; }
        .ver-estado-deteriorado { background: #f8d7da; color: #721c24; }
        .ver-estado-almacen { background: #fff3cd; color: #856404; }
        .ubicacion-almacen { color: #856404; font-weight: 600; }
        
        /* Estilos para el contador de resultados */
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
        .contador-resultados strong {
            font-size: 1.2rem;
            color: #0d47a1;
        }
        .contador-resultados .icono-libro {
            font-size: 1.5rem;
        }
        .contador-resultados-vacio {
            background: #fff3cd;
            border-left-color: #ffc107;
        }

        /* Asegura que las alertas de SweetAlert2 siempre queden por encima
           de los modales de esta vista (z-index 2000/2001). */
        .swal-encima-modal {
            z-index: 3000 !important;
        }
    </style>
</head>
<body>
    <?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div id="main-wrapper">
    <main id="main-content">

        <div class="header-acciones">
            <div>
                <h1>Gestión e Inventario</h1>
                <p style="color: var(--texto-suave);">Registro y control de libros en el inventario.</p>
            </div> 
        </div>
        
        <?php echo $__env->make('layouts.mensajes', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <?php if($errors->any()): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // El modal de registro ya fue abierto (ver script principal más abajo).
                    // Mostramos la alerta ENCIMA de él y solo cuando el usuario la cierra
                    // dejamos al usuario de vuelta en el modal para que corrija los datos.
                    Swal.fire({
                        title: 'No se pudo procesar la solicitud',
                        html: `<ul style="text-align:left; margin:0; padding-left:20px;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(str_contains($error, 'cota') || str_contains($error, 'has already been taken')): ?>
                                    <li><strong>La cota / clasificación introducida ya se encuentra registrada en el sistema.</strong></li>
                                <?php else: ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>`,
                        icon: 'warning',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Entendido',
                        // Aseguramos que la alerta quede SIEMPRE por encima del modal (z-index 2000)
                        customClass: { container: 'swal-encima-modal' }
                    });
                });
            </script>
        <?php endif; ?>

        <div class="contenedor-tabla">
            <div class="header-tabla">
                <div class="titulo-seccion"><h3>Listado de Libros</h3></div>
                <form action="<?php echo e(route('inventario.index')); ?>" method="GET" class="contenedor-filtros-busqueda" style="display: flex; align-items: center; gap: 10px; flex: 1; max-width: 600px;">
                    <div class="busqueda-rapida" style="flex: 1; margin: 0;">
                        <i data-lucide="search"></i>
                        <input type="text" name="search" value="<?php echo e(isset($buscar) ? $buscar : ''); ?>" placeholder="Buscar por título, autor o cota...">
                    </div>

                    <?php if(isset($puedeFiltrarSede) && $puedeFiltrarSede): ?>
                        <div class="filtro-sede-wrapper" style="display: flex; align-items: center; gap: 6px; background: #f8fafc; padding: 7px 12px; border-radius: 8px; border: 1px solid var(--borde); white-space: nowrap;">
                            <i data-lucide="building-2" size="18" style="color: var(--primario);"></i>
                            <select name="sede_id" onchange="this.form.submit()" style="border: none; background: transparent; outline: none; font-size: 0.85rem; color: var(--texto-oscuro); cursor: pointer; font-weight: 500;">
                                <option value="">Todas las sedes</option>
                                <?php $__currentLoopData = $sedes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>" <?php echo e((isset($sedeFiltro) && (string)$sedeFiltro === (string)$s->id) ? 'selected' : ''); ?>>
                                        <?php echo e($s->biblioteca); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if((isset($buscar) && $buscar) || (isset($sedeFiltro) && $sedeFiltro)): ?>
                        <a href="<?php echo e(route('inventario.index')); ?>" class="btn btn-gris" style="padding: 7px 10px; font-size: 0.8rem; text-decoration: none; border-radius: 8px;" title="Limpiar filtros">
                            <i data-lucide="x"></i>
                        </a>
                    <?php endif; ?>
                </form>
                <button type="button" class="btn btn-gris" onclick="abrirModalReportes()"><i data-lucide="file-bar-chart-2"></i> Reportes</button>
                
                <!-- Botón EXPORTAR SQL (Disponible para todas las sedes) -->
                <button type="button" class="btn btn-verde" style="background-color: #27ae60; color: white; display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer;" onclick="abrirModalExportarSql()" title="Exportar inventario a archivo SQL">
                    <i data-lucide="download"></i> Exportar SQL
                </button>

                <?php if(in_array(Auth::user()->rol, [1, 3])): ?>
                <!-- Botón IMPORTAR SQL -->
                <button type="button" class="btn btn-naranja" style="background-color: #e67e22; color: white; display: inline-flex; align-items: center; gap: 6px;" onclick="abrirModalImportarSql()" title="Importar inventario desde archivo SQL">
                    <i data-lucide="upload"></i> Importar SQL
                </button>

                <button type="button" id="btn-toggle-form" class="btn btn-azul" onclick="abrirModalRegistro()"><i data-lucide="plus-circle"></i> Nuevo Registro</button>
                <?php endif; ?>
            </div>

            <!-- CONTADOR DE RESULTADOS -->
            <?php if((isset($buscar) && $buscar) || (isset($sedeFiltro) && $sedeFiltro)): ?>
                <?php 
                    $sedeSeleccionadaObj = !empty($sedeFiltro) ? $sedes->where('id', (int)$sedeFiltro)->first() : null;
                 ?>
                <?php if(isset($cantidadResultados) && $cantidadResultados > 0): ?>
                    <div class="contador-resultados">
                        <span class="icono-libro">📚</span>
                        <span>Hay <strong><?php echo e($cantidadResultados); ?></strong> libro(s)
                            <?php if(!empty($buscar)): ?> con el término "<strong><?php echo e($buscar); ?></strong>"<?php endif; ?>
                            <?php if($sedeSeleccionadaObj): ?> en <strong><?php echo e($sedeSeleccionadaObj->biblioteca); ?></strong><?php endif; ?>
                        </span>
                        <a href="<?php echo e(route('inventario.index')); ?>" style="margin-left: auto; font-size: 0.85rem; color: var(--primario); text-decoration: underline;">Limpiar filtros</a>
                    </div>
                <?php else: ?>
                    <div class="contador-resultados contador-resultados-vacio">
                        <span class="icono-libro">🔍</span>
                        <span>No se encontraron libros
                            <?php if(!empty($buscar)): ?> con el término "<strong><?php echo e($buscar); ?></strong>"<?php endif; ?>
                            <?php if($sedeSeleccionadaObj): ?> en <strong><?php echo e($sedeSeleccionadaObj->biblioteca); ?></strong><?php endif; ?>
                        </span>
                        <a href="<?php echo e(route('inventario.index')); ?>" style="margin-left: auto; font-size: 0.85rem; color: #e74c3c; text-decoration: underline;">Limpiar filtros</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <table id="tabla-inventario">
                <thead>
                    <tr>
                        <th>Cota</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Sede</th>
                        <th>Ubicación física</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <?php $__empty_1 = true; $__currentLoopData = $inventarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($item->cota); ?></strong></td>
                        <td><?php echo e($item->titulo); ?></td>
                        <td><?php echo e($item->autor); ?></td>
                        <td>
                            <?php 
                                $nombreSede = $item->sede ? $item->sede->biblioteca : ($item->nombre_sede ?: ($item->sede_id ? 'Sede #'.$item->sede_id : 'Sin Sede'));
                                $comunidadSede = $item->sede ? $item->sede->comunidad : null;
                             ?>
                            <span style="font-weight: 600; color: var(--primario, #2c3e50);">
                                <?php echo e($nombreSede); ?>

                            </span>
                            <?php if($comunidadSede): ?>
                                <br><small style="color: var(--texto-suave);">(<?php echo e($comunidadSede); ?>)</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($item->estanteria): ?>
                                <strong>Est. <?php echo e($item->estanteria->numero); ?></strong>
                                <br><small style="color: var(--texto-suave);">
                                    Pasillo <?php echo e($item->estanteria->pasillo ? $item->estanteria->pasillo->numero : '—'); ?>

                                    <?php if($item->estanteria->pasillo && $item->estanteria->pasillo->sala): ?>
                                        · <?php echo e($item->estanteria->pasillo->sala->nombre); ?>

                                    <?php endif; ?>
                                </small>
                            <?php elseif($item->estado === 'almacen'): ?>
                                <span class="ubicacion-almacen">En almacén</span>
                            <?php else: ?>
                                <span style="color: var(--texto-suave);">Sin asignar</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center; display:flex; gap:10px; justify-content:center;">
                            <!-- Botón HISTORIAL DE DAÑOS -->
                            <button type="button" class="btn-icon" 
                                onclick="abrirModalHistorial('<?php echo e($item->cota); ?>', '<?php echo e(addslashes($item->titulo)); ?>')"
                                style="border:none; background:none; cursor:pointer; color:var(--secundario);"
                                title="Historial de daños">
                                <i data-lucide="clipboard-list" style="color: #e67e22;"></i>
                            </button>
                            <!-- Botón VER -->
                            <button type="button" class="btn-icon" 
                                onclick="abrirModalVer(<?php echo e(json_encode($item)); ?>)"
                                style="border:none; background:none; cursor:pointer; color:var(--secundario);"
                                title="Ver detalles">
                                <i data-lucide="eye" style="color: #0984e3;"></i>
                            </button>
                            <?php if(in_array(Auth::user()->rol, [1, 3])): ?>
                            <!-- Botón EDITAR -->
                            <button type="button" class="btn-icon" 
                                onclick="abrirModalEditar(<?php echo e(json_encode($item)); ?>)"
                                style="border:none; background:none; cursor:pointer; color:var(--primario);"
                                title="Editar libro">
                                <i data-lucide="edit-3" style="color: #00b894;"></i>
                            </button>

                            <!-- Botón ELIMINAR -->
                            <button type="button" 
                                    class="btn-icon" 
                                    onclick="confirmarEliminarLibro('<?php echo e($item->cota); ?>', '<?php echo e(addslashes($item->titulo)); ?>')"
                                    style="color:var(--error); border:none; background:none; cursor:pointer;"
                                    title="Eliminar libro">
                                <i data-lucide="trash-2"></i>
                            </button>
                            <?php endif; ?>
                            <form id="form-eliminar-<?php echo e($item->cota); ?>" 
                                  action="<?php echo e(route('inventario.destroy', $item->cota)); ?>" 
                                  method="POST" 
                                  style="display:none;">
                                <?php echo e(csrf_field()); ?>

                                <?php echo e(method_field('DELETE')); ?>

                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            <i data-lucide="book-open" size="48" style="margin-bottom: 10px; color: #9ca3af;"></i>
                            <p>No hay libros registrados en el inventario</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="paginacion-container">
    <div class="info-paginacion" id="info-paginacion">
        Mostrando registros
    </div>
    <div class="botones-paginacion">
        <button class="btn-pag" id="btn-prev" disabled>
            <i data-lucide="chevron-left" size="16"></i> Anterior
        </button>
        <div id="numeros-paginas" style="display: flex; gap: 5px;">
            <!-- Los números se generan dinámicamente mediante paginacion.js -->
        </div>
        <button class="btn-pag" id="btn-next">
            Siguiente <i data-lucide="chevron-right" size="16"></i>
        </button>
    </div>
</div>
        </div>
    </main>
    <?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>

<!-- MODAL DE REGISTRO -->
<div id="modal-registro" class="modal-overlay">
    <div class="modal-content">
        <div class="titulo-form">
            <h2>Registrar Nuevo Libro</h2>
            <button type="button" id="btn-cerrar-form" class="btn-icon" style="color:var(--error);" onclick="cerrarModalRegistro()">
                <i data-lucide="x-circle"></i>
            </button>
        </div>
        <form action="<?php echo e(route('inventario.store')); ?>" method="POST">
            <?php echo e(csrf_field()); ?>

            <div class="rejilla-form">
                <div class="grupo-campo ancho-total">
                    <label>Título del Libro</label>
                    <input name="titulo" required type="text" class="entrada" placeholder="Ej. Don Quijote">
                </div>
                <div class="grupo-campo">
                    <label>Autor(es)</label>
                    <div style="display: flex; gap: 5px;">
                        <div style="flex: 1; display: flex; flex-direction: column; gap: 5px;">
                            <input type="text" id="buscar-autor" class="entrada" data-busca-select="autor" placeholder="Buscar autor..." autocomplete="off">
                            <select name="autor" id="autor" class="entrada" required>
                                <option value="">Seleccione un autor...</option>
                                <?php $__currentLoopData = $autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autorItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($autorItem->nombre); ?>"><?php echo e($autorItem->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-gris" title="Registrar nuevo autor" onclick="abrirModalNuevoAutor('autor')">
                            <i data-lucide="plus" size="18"></i>
                        </button>
                    </div>
                </div>
                <div class="grupo-campo">
                    <label>Volumen</label>
                    <input name="volumen" required type="number" class="entrada" max="5" min="0">
                </div>
                <div class="grupo-campo">
                    <label>Cota / Clasificación</label>
                    <input name="cota" required type="text" class="entrada" placeholder="Ej. 863.3">
                </div>
                <div class="grupo-campo">
                    <label>Categoría</label>
                    <select name="categoria" class="entrada" required>
                        <option value="">Seleccione...</option>
                        <option value="Generalidades">Generalidades</option>
                        <option value="filosofia_y_teologia">Filosofia y teologia</option>
                        <option value="religion">Religion</option>
                        <option value="ciencias_sociales">Ciencias Sociales</option>
                        <option value="lengua">Lengua</option>
                        <option value="ciencias_materiales_y_matematicas">Ciencias Materiales y Matematicas</option>
                        <option value="tegnologias">Tegnologias</option>
                        <option value="arte">Arte</option>
                        <option value="literatura_y_retorica">Literatura y Retorica</option>
                    </select>
                </div>
                <div class="grupo-campo">
                    <label>Sede</label>
                    <select name="ubicacion" class="entrada" required>
                        <option value="">Seleccione una sede...</option>
                        <?php $__currentLoopData = $sedes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sede->id); ?>" <?php if($sede->id == (Auth::user()->sede_id ?: 1)): ?> selected <?php endif; ?>>
                                <?php echo e($sede->comunidad); ?> - <?php echo e($sede->biblioteca); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="grupo-campo">
                    <label>Ubicación física (estantería)</label>
                    <div style="display: flex; gap: 5px;">
                        <select name="estanteria_id" id="estanteria_id" class="entrada">
                            <option value="">Sin asignar</option>
                            <?php $__currentLoopData = $estanterias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estanteria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($estanteria->id); ?>">
                                    Estantería <?php echo e($estanteria->numero); ?> (<?php echo e($estanteria->genero); ?>) — Pasillo <?php echo e($estanteria->pasillo ? $estanteria->pasillo->numero : '—'); ?> — <?php echo e($estanteria->pasillo && $estanteria->pasillo->sala ? $estanteria->pasillo->sala->nombre : 'Sin sala'); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-gris" title="Registrar nueva estantería" onclick="abrirModalNuevaEstanteria('estanteria_id')">
                            <i data-lucide="plus" size="18"></i>
                        </button>
                    </div>
                    <small style="color: var(--texto-suave); display: block; margin-top: 4px;">Déjela en "Sin asignar" si el libro está en almacén.</small>
                </div>
                <div class="grupo-campo">
                    <label>Ingreso del Libro</label>
                    <input name="ingreso" type="text" class="entrada" placeholder="Ej. Donación 2024">
                </div>
                <div class="grupo-campo"> 
                    <label>Estado del Libro</label>
                    <div style="display: flex; gap: 20px; margin-top: 5px;">
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="radio" name="estado" value="bueno" onchange="verificarEstadoRegistro()" checked>
                            Bueno
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="radio" name="estado" value="deteriorado" onchange="verificarEstadoRegistro()">
                            Deteriorado
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="radio" name="estado" value="almacen" onchange="verificarEstadoRegistro()">
                            En almacén
                        </label>
                    </div>
                </div>

                <div class="grupo-campo" id="contenedor-dias-prestamo-registro">
                    <label>Límite de días para préstamo (opcional)</label>
                    <input name="dias_prestamo" type="number" class="entrada" min="1" max="90" 
                        placeholder="Ej. 7 (vacío = plazo estándar)">
                    <small style="color: #6c757d; display:block; margin-top:4px;">
                        Si se define, este libro solo podrá prestarse por este número máximo de días.
                    </small>
                </div>

                <!-- Campos que se muestran solo cuando está deteriorado -->
                <div id="contenedor-deterioro-registro" style="display: none;">
                    <div class="grupo-campo ancho-total">
                        <label>Detalles del deterioro</label>
                        <textarea name="observaciones" id="observaciones" class="entrada" rows="3" placeholder="Describa el tipo de deterioro..."></textarea>
                    </div>
                </div>
            </div>
            <div class="fila-botones">
                <button type="submit" class="btn btn-azul"><i data-lucide="save"></i> Guardar Registro</button>
                <button type="reset" class="btn btn-gris">Limpiar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DE EDICIÓN -->
<div id="modal-editar" class="modal-overlay">
    <div class="modal-content">
        <div class="titulo-form">
            <h2>Editar Libro</h2>
            <button type="button" onclick="cerrarModalEditar()" class="btn-icon" style="color:var(--error);">
                <i data-lucide="x-circle"></i>
            </button>
        </div>
        
        <form id="form-editar" method="POST">
            <?php echo e(csrf_field()); ?>

            <?php echo e(method_field('PUT')); ?>

            
            <div class="rejilla-form">
                <div class="grupo-campo ancho-total">
                    <label>Título del Libro</label>
                    <input name="titulo" id="edit-titulo" required type="text" class="entrada">
                </div>
                <div class="grupo-campo">
                    <label>Autor(es)</label>
                    <div style="display: flex; gap: 5px;">
                        <div style="flex: 1; display: flex; flex-direction: column; gap: 5px;">
                            <input type="text" id="buscar-edit-autor" class="entrada" data-busca-select="edit-autor" placeholder="Buscar autor..." autocomplete="off">
                            <select name="autor" id="edit-autor" class="entrada" required>
                                <option value="">Seleccione un autor...</option>
                                <?php $__currentLoopData = $autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autorItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($autorItem->nombre); ?>"><?php echo e($autorItem->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-gris" title="Registrar nuevo autor" onclick="abrirModalNuevoAutor('edit-autor')">
                            <i data-lucide="plus" size="18"></i>
                        </button>
                    </div>
                </div>
                <div class="grupo-campo">
                    <label>Volumen</label>
                    <input name="volumen" id="edit-volumen" required type="number" class="entrada" min="0" max="5">
                </div>
                <div class="grupo-campo">
                    <label>Cota / Clasificación</label>
                    <input name="cota" id="edit-cota" required type="text" class="entrada entrada-readonly" readonly>
                    <small class="nota-campo">La cota no se puede modificar</small>
                </div>
                <div class="grupo-campo">
                    <label>Categoría</label>
                    <select name="categoria" id="edit-categoria" class="entrada" required>
                        <option value="literatura">Literatura</option>
                        <option value="historia">Historia</option>
                        <option value="medicina">Medicina</option>
                        <option value="ciencia">Ciencia</option>
                        <option value="arte">Arte</option>
                        <option value="tecnologia">Tecnología</option>
                        <option value="educacion">Educación</option>
                    </select>
                </div>
                <div class="grupo-campo">
                    <label>Sede</label>
                    <select name="ubicacion" id="edit-ubicacion" class="entrada" required>
                        <?php $__currentLoopData = $sedes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sede->id); ?>">
                                <?php echo e($sede->comunidad); ?> - <?php echo e($sede->biblioteca); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="grupo-campo">
                    <label>Ubicación física (estantería)</label>
                    <div style="display: flex; gap: 5px;">
                        <select name="estanteria_id" id="edit-estanteria_id" class="entrada">
                            <option value="">Sin asignar</option>
                            <?php $__currentLoopData = $estanterias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estanteria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($estanteria->id); ?>">
                                    Estantería <?php echo e($estanteria->numero); ?> (<?php echo e($estanteria->genero); ?>) — Pasillo <?php echo e($estanteria->pasillo ? $estanteria->pasillo->numero : '—'); ?> — <?php echo e($estanteria->pasillo && $estanteria->pasillo->sala ? $estanteria->pasillo->sala->nombre : 'Sin sala'); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-gris" title="Registrar nueva estantería" onclick="abrirModalNuevaEstanteria('edit-estanteria_id')">
                            <i data-lucide="plus" size="18"></i>
                        </button>
                    </div>
                    <small style="color: var(--texto-suave); display: block; margin-top: 4px;">Déjela en "Sin asignar" si el libro está en almacén.</small>
                </div>
                <div class="grupo-campo">
                    <label>Ingreso del Libro</label>
                    <input name="ingreso" id="edit-ingreso" type="text" class="entrada">
                </div>
                <div class="grupo-campo">
                    <label>Estado del Libro</label>
                    <div style="display: flex; gap: 20px; margin-top: 5px;">
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="radio" name="estado" value="bueno" onchange="verificarEstadoEdicion()">
                            Bueno
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="radio" name="estado" value="deteriorado" onchange="verificarEstadoEdicion()">
                            Deteriorado
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="radio" name="estado" value="almacen" onchange="verificarEstadoEdicion()">
                            En almacén
                        </label>
                    </div>
                </div>

                <div class="grupo-campo" id="contenedor-dias-prestamo-edicion">
                    <label>Límite de días para préstamo (opcional)</label>
                    <input name="dias_prestamo" id="edit-dias_prestamo" type="number" class="entrada" 
                        min="1" max="90" placeholder="Ej. 7 (vacío = plazo estándar)">
                    <small style="color: #6c757d; display:block; margin-top:4px;">
                        Si se define, este libro solo podrá prestarse por este número máximo de días.
                    </small>
                </div>

                <!-- Campos que se muestran solo cuando está deteriorado en edición -->
                <div id="edit-contenedor-deterioro" style="display: none;">
                    <div class="grupo-campo ancho-total">
                        <label>Detalles del deterioro</label>
                        <textarea name="observaciones" id="edit-observaciones" class="entrada" rows="3" placeholder="Describa el tipo de deterioro..."></textarea>
                    </div>
                </div>
            </div>

            <div class="fila-botones">
                <button type="submit" class="btn btn-azul"><i data-lucide="refresh-cw"></i> Actualizar Cambios</button>
                <button type="button" onclick="cerrarModalEditar()" class="btn btn-gris">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL RÁPIDO PARA REGISTRAR UN NUEVO AUTOR SIN SALIR DE INVENTARIO -->
<div id="modal-nuevo-autor" class="modal-overlay">
    <div class="modal-content" style="max-width: 420px;">
        <div class="titulo-form">
            <h2>Nuevo Autor</h2>
            <button type="button" onclick="cerrarModalNuevoAutor()" class="btn-icon" style="color:var(--error);">
                <i data-lucide="x-circle"></i>
            </button>
        </div>

        <form id="form-nuevo-autor" data-async>
            <div class="grupo-campo ancho-total">
                <label for="nuevo-autor-nombre">Nombre del autor</label>
                <input type="text" id="nuevo-autor-nombre" class="entrada" placeholder="Ej. Miguel de Cervantes" required>
                <small id="nuevo-autor-error" style="color: var(--error); display: none;"></small>
            </div>
            <div class="fila-botones">
                <button type="submit" class="btn btn-azul"><i data-lucide="save"></i> Guardar Autor</button>
                <button type="button" onclick="cerrarModalNuevoAutor()" class="btn btn-gris">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL RÁPIDO PARA REGISTRAR UNA NUEVA ESTANTERÍA SIN SALIR DE INVENTARIO -->
<div id="modal-nueva-estanteria" class="modal-overlay">
    <div class="modal-content" style="max-width: 420px;">
        <div class="titulo-form">
            <h2>Nueva Estantería</h2>
            <button type="button" onclick="cerrarModalNuevaEstanteria()" class="btn-icon" style="color:var(--error);">
                <i data-lucide="x-circle"></i>
            </button>
        </div>

        <form id="form-nueva-estanteria" data-async>
            <div class="grupo-campo ancho-total">
                <label for="nueva-estanteria-sala">Sala</label>
                <select id="nueva-estanteria-sala" class="entrada" required>
                    <option value="">Seleccione una sala...</option>
                    <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sala): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sala->id); ?>"><?php echo e($sala->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="grupo-campo ancho-total">
                <label for="nueva-estanteria-pasillo">Pasillo</label>
                <div style="display: flex; gap: 5px;">
                    <select id="nueva-estanteria-pasillo" class="entrada" required>
                        <option value="">Seleccione un pasillo...</option>
                        <?php $__currentLoopData = $pasillos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pasillo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pasillo->id); ?>" data-sala-id="<?php echo e($pasillo->sala_id); ?>"><?php echo e($pasillo->numero); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="btn btn-gris" title="Registrar nuevo pasillo" onclick="abrirModalNuevoPasillo()">
                        <i data-lucide="plus" size="18"></i>
                    </button>
                </div>
            </div>
            <div class="grupo-campo ancho-total">
                <label for="nueva-estanteria-numero">Número de la estantería</label>
                <input type="text" id="nueva-estanteria-numero" class="entrada" placeholder="Ej. 3" required>
            </div>
            <div class="grupo-campo ancho-total">
                <label for="nueva-estanteria-genero">Género / Temática</label>
                <input type="text" id="nueva-estanteria-genero" class="entrada" placeholder="Ej. Literatura" required>
            </div>
            <small id="nueva-estanteria-error" style="color: var(--error); display: none;"></small>
            <div class="fila-botones">
                <button type="submit" class="btn btn-azul"><i data-lucide="save"></i> Guardar Estantería</button>
                <button type="button" onclick="cerrarModalNuevaEstanteria()" class="btn btn-gris">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL RÁPIDO PARA REGISTRAR UN NUEVO PASILLO SIN SALIR DE INVENTARIO -->
<div id="modal-nuevo-pasillo" class="modal-overlay">
    <div class="modal-content" style="max-width: 420px;">
        <div class="titulo-form">
            <h2>Nuevo Pasillo</h2>
            <button type="button" onclick="cerrarModalNuevoPasillo()" class="btn-icon" style="color:var(--error);">
                <i data-lucide="x-circle"></i>
            </button>
        </div>

        <form id="form-nuevo-pasillo" data-async>
            <div class="grupo-campo ancho-total">
                <label for="nuevo-pasillo-sala">Sala</label>
                <select id="nuevo-pasillo-sala" class="entrada" required>
                    <option value="">Seleccione una sala...</option>
                    <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sala): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sala->id); ?>"><?php echo e($sala->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="grupo-campo ancho-total">
                <label for="nuevo-pasillo-numero">Número del pasillo</label>
                <input type="text" id="nuevo-pasillo-numero" class="entrada" placeholder="Ej. 2" required>
            </div>
            <small id="nuevo-pasillo-error" style="color: var(--error); display: none;"></small>
            <div class="fila-botones">
                <button type="submit" class="btn btn-azul"><i data-lucide="save"></i> Guardar Pasillo</button>
                <button type="button" onclick="cerrarModalNuevoPasillo()" class="btn btn-gris">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DE VER -->
<div id="modal-ver" class="modal-ver-overlay">
    <div class="modal-ver-content">
        <div class="titulo-form">
            <h2>Detalles del Libro</h2>
            <button type="button" onclick="cerrarModalVer()" class="btn-icon" style="color:var(--error);">
                <i data-lucide="x-circle"></i>
            </button>
        </div>
        
        <div id="ver-contenido">
            <!-- Los datos se llenan dinámicamente con JavaScript -->
        </div>

        <div class="fila-botones" style="margin-top: 20px;">
            <button type="button" onclick="cerrarModalVer()" class="btn btn-gris">
                <i data-lucide="x"></i> Cerrar
            </button>
        </div>
    </div>
</div>

<!-- MODAL HISTORIAL DE DAÑOS -->
<div id="modal-historial" class="modal-ver-overlay">
    <div class="modal-ver-content" style="max-width: 750px;">
        <div class="titulo-form">
            <h2>Historial de daños / reparaciones</h2>
            <button type="button" onclick="cerrarModalHistorial()" class="btn-icon" style="color:var(--error);">
                <i data-lucide="x-circle"></i>
            </button>
        </div>
        <div style="margin-bottom: 10px; color: var(--texto-suave);">
            <strong>Libro:</strong> <span id="hist-libro-titulo"></span> (<span id="hist-libro-cota"></span>)
        </div>
        <div id="historial-contenido" style="max-height: 60vh; overflow-y: auto; padding-right: 5px;">
            <!-- Se llena dinámicamente -->
        </div>
        <div class="fila-botones" style="margin-top: 15px;">
            <button type="button" onclick="cerrarModalHistorial()" class="btn btn-gris">
                <i data-lucide="x"></i> Cerrar
            </button>
        </div>
    </div>
</div>

<!-- MODAL DE REPORTES -->
<div id="modal-reportes" class="modal-overlay">
    <div class="modal-content" style="max-width: 480px;">
        <div class="titulo-form">
            <h2>Reportes de Inventario</h2>
            <button type="button" class="btn-icon" style="color:var(--error);" onclick="cerrarModalReportes()">
                <i data-lucide="x-circle"></i>
            </button>
        </div>

        <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:16px;">
            <button type="button" class="btn btn-gris" style="text-align:left;" onclick="seleccionarReporte('general')">
                <i data-lucide="list"></i> Listado general de libros
            </button>
            <button type="button" class="btn btn-gris" style="text-align:left;" onclick="seleccionarReporte('categoria')">
                <i data-lucide="tag"></i> Libros por categoría
            </button>
            <button type="button" class="btn btn-gris" style="text-align:left;" onclick="seleccionarReporte('autor')">
                <i data-lucide="user"></i> Libros por autor
            </button>
            <button type="button" class="btn btn-gris" style="text-align:left;" onclick="seleccionarReporte('stock')">
                <i data-lucide="alert-triangle"></i> Pocos ejemplares o sin stock
            </button>
            <button type="button" class="btn btn-gris" style="text-align:left;" onclick="seleccionarReporte('prestados')">
                <i data-lucide="trending-up"></i> Libros más prestados
            </button>
        </div>

        <!-- Filtro: categoría -->
        <form id="form-reporte-categoria" action="<?php echo e(route('inventario.reportes')); ?>" method="GET" style="display:none;">
            <input type="hidden" name="tipo" value="categoria">
            <div class="grupo-campo">
                <label>Categoría (opcional, deja vacío para ver todas)</label>
                <select name="categoria" class="entrada">
                    <option value="">Todas las categorías</option>
                    <option value="literatura">Literatura</option>
                    <option value="historia">Historia</option>
                    <option value="medicina">Medicina</option>
                    <option value="ciencia">Ciencia</option>
                    <option value="arte">Arte</option>
                    <option value="tecnologia">Tecnología</option>
                    <option value="educacion">Educación</option>
                </select>
            </div>
            <div class="fila-botones">
                <button type="button" class="btn btn-gris" onclick="cerrarModalReportes()">Cancelar</button>
                <button type="submit" class="btn btn-azul"><i data-lucide="file-bar-chart-2"></i> Generar</button>
            </div>
        </form>

        <!-- Filtro: autor -->
        <form id="form-reporte-autor" action="<?php echo e(route('inventario.reportes')); ?>" method="GET" style="display:none;">
            <input type="hidden" name="tipo" value="autor">
            <div class="grupo-campo">
                <label>Autor (opcional, deja vacío para ver todos)</label>
                <select name="autor" class="entrada">
                    <option value="">Todos los autores</option>
                    <?php $__currentLoopData = $autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autorItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($autorItem->nombre); ?>"><?php echo e($autorItem->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="fila-botones">
                <button type="button" class="btn btn-gris" onclick="cerrarModalReportes()">Cancelar</button>
                <button type="submit" class="btn btn-azul"><i data-lucide="file-bar-chart-2"></i> Generar</button>
            </div>
        </form>

        <!-- Filtro: stock bajo -->
        <form id="form-reporte-stock" action="<?php echo e(route('inventario.reportes')); ?>" method="GET" style="display:none;">
            <input type="hidden" name="tipo" value="stock">
            <div class="grupo-campo">
                <label>Considerar "pocos ejemplares" cuando los disponibles sean:</label>
                <select name="umbral" class="entrada">
                    <option value="0">0 (solo sin stock)</option>
                    <option value="1">1 o menos</option>
                    <option value="2" selected>2 o menos</option>
                    <option value="3">3 o menos</option>
                    <option value="5">5 o menos</option>
                </select>
            </div>
            <div class="fila-botones">
                <button type="button" class="btn btn-gris" onclick="cerrarModalReportes()">Cancelar</button>
                <button type="submit" class="btn btn-azul"><i data-lucide="file-bar-chart-2"></i> Generar</button>
            </div>
        </form>

        <!-- Filtro: más prestados -->
        <form id="form-reporte-prestados" action="<?php echo e(route('inventario.reportes')); ?>" method="GET" style="display:none;">
            <input type="hidden" name="tipo" value="prestados">
            <div class="grupo-campo">
                <label>Mostrar los</label>
                <select name="limite" class="entrada">
                    <option value="10">10 más prestados</option>
                    <option value="20" selected>20 más prestados</option>
                    <option value="50">50 más prestados</option>
                    <option value="100">100 más prestados</option>
                </select>
            </div>
            <div class="fila-botones">
                <button type="button" class="btn btn-gris" onclick="cerrarModalReportes()">Cancelar</button>
                <button type="submit" class="btn btn-azul"><i data-lucide="file-bar-chart-2"></i> Generar</button>
            </div>
        </form>

        <!-- Reporte sin filtro (listado general): se envía directo -->
        <form id="form-reporte-general" action="<?php echo e(route('inventario.reportes')); ?>" method="GET" style="display:none;">
            <input type="hidden" name="tipo" value="general">
        </form>
    </div>
</div>

<!-- MODAL DE EXPORTAR SQL -->
<div id="modal-exportar-sql" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 490px;">
        <div class="titulo-form">
            <h2>Exportar Inventario (SQL)</h2>
            <button type="button" class="btn-icon" style="color:var(--error);" onclick="cerrarModalExportarSql()">
                <i data-lucide="x-circle"></i>
            </button>
        </div>

        <form action="<?php echo e(route('inventario.exportar.sql')); ?>" method="GET" id="form-exportar-sql">
            <p style="font-size: 0.88rem; color: var(--texto-suave); margin-bottom: 15px;">
                Selecciona el plazo de tiempo de los registros que deseas exportar a base de datos SQL.
            </p>

            <div class="grupo-campo" style="margin-bottom: 14px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px;">Plazo de tiempo / Registros:</label>
                <select name="plazo" id="select-plazo-exportar" class="entrada" onchange="cambiarPlazoExportar(this.value)" style="width: 100%;">
                    <option value="todo">🌐 Todo el catálogo (sin límite de fecha)</option>
                    <option value="semana">📅 Última semana (últimos 7 días)</option>
                    <option value="mes">🗓️ Último mes (últimos 30 días)</option>
                    <option value="trimestre">📊 Último trimestre (últimos 90 días)</option>
                    <option value="personalizado">🎯 Rango de fechas personalizado...</option>
                </select>
            </div>

            <!-- Campos de rango de fechas personalizado (ocultos inicialmente) -->
            <div id="contenedor-fechas-personalizadas" style="display: none; background: #f8fafc; border: 1px solid var(--borde); border-radius: 8px; padding: 12px; margin-bottom: 14px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 4px;">Fecha desde:</label>
                        <input type="date" name="fecha_desde" class="entrada" style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 4px;">Fecha hasta:</label>
                        <input type="date" name="fecha_hasta" class="entrada" style="width: 100%;">
                    </div>
                </div>
            </div>

            <?php if(isset($puedeFiltrarSede) && $puedeFiltrarSede): ?>
            <div class="grupo-campo" style="margin-bottom: 14px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px;">Sede a exportar:</label>
                <select name="sede_id" class="entrada" style="width: 100%;">
                    <option value="">Todas las sedes</option>
                    <?php $__currentLoopData = $sedes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" <?php echo e((isset($sedeFiltro) && (string)$sedeFiltro === (string)$s->id) ? 'selected' : ''); ?>>
                            <?php echo e($s->biblioteca); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php endif; ?>

            <div style="background: #eef9f1; border-left: 4px solid #27ae60; padding: 10px 12px; border-radius: 4px; font-size: 0.82rem; color: #1e7e34; margin-bottom: 18px;">
                <strong>Nota:</strong> Se descargará el archivo <code>.sql</code> inmediatamente y se conservará una copia en la carpeta de respaldos del sistema.
            </div>

            <div class="fila-botones" style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="cerrarModalExportarSql()" class="btn btn-gris">
                    <i data-lucide="x"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-verde" style="background-color: #27ae60; color: white;" onclick="cerrarModalExportarSql()">
                    <i data-lucide="download"></i> Descargar SQL
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DE IMPORTAR SQL -->
<div id="modal-importar-sql" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 520px;">
        <div class="titulo-form">
            <h2>Importar Inventario (SQL)</h2>
            <button type="button" class="btn-icon" style="color:var(--error);" onclick="cerrarModalImportarSql()">
                <i data-lucide="x-circle"></i>
            </button>
        </div>

        <form action="<?php echo e(route('inventario.importar.sql')); ?>" method="POST" enctype="multipart/form-data" id="form-importar-sql">
            <?php echo e(csrf_field()); ?>


            <p style="font-size: 0.88rem; color: var(--texto-suave); margin-bottom: 15px;">
                Selecciona o arrastra el archivo <code>.sql</code> de exportación de inventario. Los libros serán incorporados o actualizados en la base de datos automáticamente.
            </p>

            <!-- Zona Drag and Drop -->
            <div id="drop-zone-sql" style="border: 2px dashed #3498db; border-radius: 12px; padding: 25px 15px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease; margin-bottom: 15px;">
                <input type="file" name="archivo_sql" id="input-archivo-sql" accept=".sql" style="display: none;" required onchange="manejarSeleccionSql(this.files)">
                <div id="drop-zone-contenido">
                    <i data-lucide="upload-cloud" size="44" style="color: #3498db; margin-bottom: 8px;"></i>
                    <p style="font-weight: 600; color: var(--texto-oscuro); margin-bottom: 4px;">
                        Arrastra tu archivo .sql aquí o <span style="color: #3498db; text-decoration: underline;">explora tu equipo</span>
                    </p>
                    <small style="color: var(--texto-suave);">Solo archivos con formato .sql</small>
                </div>
                <div id="drop-zone-preview" style="display: none; align-items: center; justify-content: center; gap: 10px; margin-top: 5px;">
                    <i data-lucide="file-text" size="24" style="color: #27ae60;"></i>
                    <div style="text-align: left;">
                        <strong id="sql-nombre-archivo" style="display: block; color: var(--texto-oscuro); font-size: 0.9rem;"></strong>
                        <small id="sql-tamano-archivo" style="color: var(--texto-suave);"></small>
                    </div>
                    <button type="button" onclick="quitarArchivoSql(event)" style="background: none; border: none; color: #e74c3c; cursor: pointer; margin-left: 10px;" title="Quitar archivo">
                        <i data-lucide="trash-2" size="18"></i>
                    </button>
                </div>
            </div>

            <div style="background: #fff8e1; border-left: 4px solid #f39c12; padding: 10px 12px; border-radius: 4px; font-size: 0.82rem; color: #7f6000; margin-bottom: 18px;">
                <strong>Aviso:</strong> Se guardará una copia de respaldo en el sistema. Los registros existentes con la misma Cota se actualizarán con los datos del archivo.
            </div>

            <div class="fila-botones" style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="cerrarModalImportarSql()" class="btn btn-gris">
                    <i data-lucide="x"></i> Cancelar
                </button>
                <button type="submit" id="btn-confirmar-importar" class="btn btn-naranja" style="background-color: #e67e22; color: white;" disabled>
                    <i data-lucide="upload"></i> Procesar Importación
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
    // ============================================================
    // EXPORTACIÓN SQL (MODAL)
    // ============================================================
    function abrirModalExportarSql() {
        document.getElementById('modal-exportar-sql').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
    }

    function cerrarModalExportarSql() {
        document.getElementById('modal-exportar-sql').style.display = 'none';
        document.body.style.overflow = '';
    }

    function cambiarPlazoExportar(valor) {
        var contenedor = document.getElementById('contenedor-fechas-personalizadas');
        if (contenedor) {
            contenedor.style.display = (valor === 'personalizado') ? 'block' : 'none';
        }
    }
    // ============================================================
    // IMPORTACIÓN SQL (MODAL Y DRAG & DROP)
    // ============================================================
    function abrirModalImportarSql() {
        document.getElementById('modal-importar-sql').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
    }

    function cerrarModalImportarSql() {
        document.getElementById('modal-importar-sql').style.display = 'none';
        document.body.style.overflow = '';
    }

    function manejarSeleccionSql(files) {
        if (!files || files.length === 0) return;
        var file = files[0];
        if (!file.name.toLowerCase().match(/\.sql$/)) {
            alert('Por favor selecciona un archivo con extensión .sql');
            return;
        }
        document.getElementById('drop-zone-contenido').style.display = 'none';
        document.getElementById('drop-zone-preview').style.display = 'flex';
        document.getElementById('sql-nombre-archivo').innerText = file.name;
        document.getElementById('sql-tamano-archivo').innerText = (file.size / 1024).toFixed(1) + ' KB';
        document.getElementById('btn-confirmar-importar').disabled = false;
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
    }

    function quitarArchivoSql(e) {
        if (e) e.stopPropagation();
        document.getElementById('input-archivo-sql').value = '';
        document.getElementById('drop-zone-contenido').style.display = 'block';
        document.getElementById('drop-zone-preview').style.display = 'none';
        document.getElementById('btn-confirmar-importar').disabled = true;
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var dropZone = document.getElementById('drop-zone-sql');
        var inputSql = document.getElementById('input-archivo-sql');
        if (dropZone && inputSql) {
            dropZone.addEventListener('click', function() {
                inputSql.click();
            });
            ['dragenter', 'dragover'].forEach(function(eventName) {
                dropZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.style.borderColor = '#27ae60';
                    dropZone.style.background = '#f0fdf4';
                }, false);
            });
            ['dragleave', 'drop'].forEach(function(eventName) {
                dropZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.style.borderColor = '#3498db';
                    dropZone.style.background = '#f8fafc';
                }, false);
            });
            dropZone.addEventListener('drop', function(e) {
                var dt = e.dataTransfer;
                if (dt && dt.files && dt.files.length > 0) {
                    inputSql.files = dt.files;
                    manejarSeleccionSql(dt.files);
                }
            }, false);
        }
    });
    // ============================================================
    // REPORTES
    // ============================================================
    function abrirModalReportes() {
        // Ocultar todos los formularios de filtro y mostrar solo los botones
        ['general', 'categoria', 'autor', 'stock', 'prestados'].forEach(function(tipo) {
            document.getElementById('form-reporte-' + tipo).style.display = 'none';
        });
        document.getElementById('modal-reportes').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
    }

    function cerrarModalReportes() {
        document.getElementById('modal-reportes').style.display = 'none';
        document.body.style.overflow = '';
    }

    function seleccionarReporte(tipo) {
        if (tipo === 'general') {
            // No tiene filtro: se genera directamente
            document.getElementById('form-reporte-general').submit();
            return;
        }
        document.getElementById('form-reporte-' + tipo).style.display = 'block';
    }

    // Función para abrir/cerrar el modal de registro
    function abrirModalRegistro() {
        if (typeof limpiarFiltroSelect === 'function') limpiarFiltroSelect('autor');
        document.getElementById('modal-registro').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
    }

    function cerrarModalRegistro() {
        document.getElementById('modal-registro').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Función para mostrar/ocultar campo de deterioro en registro
    function verificarEstadoRegistro() {
        const estado = document.querySelector('input[name="estado"]:checked');
        const contenedor = document.getElementById('contenedor-deterioro-registro');
        const observaciones = document.getElementById('observaciones');
        const contenedorDias = document.getElementById('contenedor-dias-prestamo-registro');
        
        if (estado && estado.value === 'deteriorado') {
            contenedor.style.display = 'block';
            observaciones.required = true;
        } else {
            contenedor.style.display = 'none';
            observaciones.required = false;
            observaciones.value = '';
        }

        if (contenedorDias) {
            if (estado && estado.value === 'bueno') {
                contenedorDias.style.display = 'block';
            } else {
                contenedorDias.style.display = 'none';
                const inputDias = contenedorDias.querySelector('input[name="dias_prestamo"]');
                if (inputDias) inputDias.value = '';
            }
        }
    }

    // Función para mostrar/ocultar campo de deterioro en edición
    function verificarEstadoEdicion() {
        const estado = document.querySelector('#modal-editar input[name="estado"]:checked');
        const contenedor = document.getElementById('edit-contenedor-deterioro');
        const observaciones = document.getElementById('edit-observaciones');
        const contenedorDias = document.getElementById('contenedor-dias-prestamo-edicion');
        
        if (estado && estado.value === 'deteriorado') {
            contenedor.style.display = 'block';
            observaciones.required = true;
        } else {
            contenedor.style.display = 'none';
            observaciones.required = false;
            observaciones.value = '';
        }

        if (contenedorDias) {
            if (estado && estado.value === 'bueno') {
                contenedorDias.style.display = 'block';
            } else {
                contenedorDias.style.display = 'none';
                const inputDias = document.getElementById('edit-dias_prestamo');
                if (inputDias) inputDias.value = '';
            }
        }
    }

    // Función para abrir modal de ver
    function abrirModalVer(item) {
    const contenido = document.getElementById('ver-contenido');
    const estadoClass = item.estado === 'bueno'
        ? 'ver-estado-bueno'
        : (item.estado === 'almacen' ? 'ver-estado-almacen' : 'ver-estado-deteriorado');
    const estadoLabel = item.estado === 'almacen'
        ? 'En almacén'
        : (item.estado ? item.estado.charAt(0).toUpperCase() + item.estado.slice(1) : '');
    
    // Buscar el nombre de la sede (puedes pasar un array de sedes o hacer una búsqueda)
    // Para simplificar, mostraremos el ID, pero lo ideal es pasar los datos
    const nombreSede = item.sede ? `${item.sede.comunidad} - ${item.sede.biblioteca}` : item.ubicacion;

    const ubicacionFisica = item.estanteria
        ? `Estantería ${item.estanteria.numero} (${item.estanteria.genero}) · Pasillo ${item.estanteria.pasillo ? item.estanteria.pasillo.numero : '—'} · ${item.estanteria.pasillo && item.estanteria.pasillo.sala ? item.estanteria.pasillo.sala.nombre : 'Sin sala'}`
        : (item.estado === 'almacen' ? 'En almacén' : 'Sin asignar');
    
    contenido.innerHTML = `
        <div class="ver-detalle-item">
            <span class="ver-detalle-label">Cota:</span>
            <span class="ver-detalle-valor"><strong>${item.cota}</strong></span>
        </div>
        <div class="ver-detalle-item">
            <span class="ver-detalle-label">Título:</span>
            <span class="ver-detalle-valor">${item.titulo}</span>
        </div>
        <div class="ver-detalle-item">
            <span class="ver-detalle-label">Autor(es):</span>
            <span class="ver-detalle-valor">${item.autor}</span>
        </div>
        <div class="ver-detalle-item">
            <span class="ver-detalle-label">Volumen:</span>
            <span class="ver-detalle-valor">${item.volumen || 'No especificado'}</span>
        </div>
        <div class="ver-detalle-item">
            <span class="ver-detalle-label">Categoría:</span>
            <span class="ver-detalle-valor">${item.categoria}</span>
        </div>
        <div class="ver-detalle-item">
            <span class="ver-detalle-label">Sede:</span>
            <span class="ver-detalle-valor">${nombreSede}</span>
        </div>
        <div class="ver-detalle-item">
            <span class="ver-detalle-label">Ubicación física:</span>
            <span class="ver-detalle-valor">${ubicacionFisica}</span>
        </div>
        <div class="ver-detalle-item">
            <span class="ver-detalle-label">Ingreso:</span>
            <span class="ver-detalle-valor">${item.ingreso || 'No especificado'}</span>
        </div>
        <div class="ver-detalle-item">
            <span class="ver-detalle-label">Estado:</span>
            <span class="ver-detalle-valor">
                <span class="ver-estado-badge ${estadoClass}">
                    ${estadoLabel}
                </span>
            </span>
        </div>
        ${item.dias_prestamo ? `
        <div class="ver-detalle-item">
            <span class="ver-detalle-label">Límite de préstamo:</span>
            <span class="ver-detalle-valor">${item.dias_prestamo} días máximo</span>
        </div>` : ''}
        <div class="ver-detalle-item" style="border-bottom: none;">
            <span class="ver-detalle-label">Observaciones:</span>
            <span class="ver-detalle-valor">${item.observaciones || 'Sin observaciones'}</span>
        </div>
    `;
    
    document.getElementById('modal-ver').style.display = 'block';
    document.body.style.overflow = 'hidden';
    lucide.createIcons();
}

    function cerrarModalVer() {
        document.getElementById('modal-ver').style.display = 'none';
        document.body.style.overflow = '';
    }

    function abrirModalHistorial(cota, titulo) {
        document.getElementById('hist-libro-cota').textContent = cota;
        document.getElementById('hist-libro-titulo').textContent = titulo;
        var cont = document.getElementById('historial-contenido');
        cont.innerHTML = '<p style="text-align:center; color: var(--texto-suave);">Cargando...</p>';
        document.getElementById('modal-historial').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();

        fetch('/inventario/' + encodeURIComponent(cota) + '/historial')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (!data || data.error) {
                    cont.innerHTML = '<p style="text-align:center; color: var(--texto-suave);">No se pudo cargar el historial.</p>';
                    return;
                }
                if (!data.historial || data.historial.length === 0) {
                    cont.innerHTML = '<p style="text-align:center; color: var(--texto-suave);">Sin daños registrados para este libro.</p>';
                    return;
                }
                var html = '';
                for (var i = 0; i < data.historial.length; i++) {
                    var h = data.historial[i];
                    var estadoBadge = '';
                    if (h.estado === 'pendiente') {
                        estadoBadge = '<span style="background: #dc3545; color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px;">Pendiente</span>';
                    } else if (h.estado === 'reparado') {
                        estadoBadge = '<span style="background: #28a745; color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px;">Reparado</span>';
                    } else if (h.estado === 'pagado') {
                        estadoBadge = '<span style="background: #17a2b8; color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px;">Pagado</span>';
                    } else {
                        estadoBadge = '<span style="background: #6c757d; color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px;">' + (h.estado || 'Resuelto') + '</span>';
                    }
                    var tipo = h.tipo_evento === 'reparacion' ? 'Reparación' : (h.tipo_evento === 'pago' ? 'Pago' : (h.tipo_evento === 'dano' ? 'Daño' : (h.tipo_evento || 'Evento')));
                    html += '<div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 12px; margin-bottom: 10px; background: #fafafa;">';
                    html += '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;"><strong>' + tipo + '</strong> ' + estadoBadge + '</div>';
                    html += '<div style="font-size:13px; color:#333; margin-bottom:4px;"><strong>Descripción:</strong> ' + (h.descripcion || '-') + '</div>';
                    if (h.solucion) {
                        html += '<div style="font-size:13px; color:#333; margin-bottom:4px;"><strong>Solución / Resolución:</strong> ' + h.solucion + '</div>';
                    }
                    if (h.persona) {
                        html += '<div style="font-size:12px; color:var(--texto-suave);">Responsable/Usuario: ' + h.persona + (h.cedula ? ' (' + h.cedula + ')' : '') + '</div>';
                    }
                    if (h.prestamo_id) {
                        html += '<div style="font-size:12px; color:var(--texto-suave);">Préstamo: #' + h.prestamo_id + (h.sancion_id ? ' / Sanción: #' + h.sancion_id : '') + '</div>';
                    }
                    html += '<div style="font-size:12px; color:var(--texto-suave); margin-top:4px;">Fecha evento: ' + (h.fecha_evento || '-') + (h.fecha_resolucion ? ' | Fecha resolución: ' + h.fecha_resolucion : '') + '</div>';
                    html += '</div>';
                }
                cont.innerHTML = html;
                if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
            })
            .catch(function() {
                cont.innerHTML = '<p style="text-align:center; color: var(--texto-suave);">Error al cargar el historial.</p>';
            });
    }

    function cerrarModalHistorial() {
        document.getElementById('modal-historial').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Función para abrir modal de editar
    function abrirModalEditar(item) {
    if (typeof limpiarFiltroSelect === 'function') limpiarFiltroSelect('edit-autor');
    document.getElementById('edit-titulo').value = item.titulo || '';
    asegurarOpcionAutor('edit-autor', item.autor || '');
    document.getElementById('edit-autor').value = item.autor || '';
    document.getElementById('edit-volumen').value = item.volumen || 0;
    document.getElementById('edit-cota').value = item.cota || '';
    document.getElementById('edit-categoria').value = item.categoria || '';
    document.getElementById('edit-ubicacion').value = item.ubicacion || ''; // Esto ahora seleccionará el ID correcto
    document.getElementById('edit-estanteria_id').value = item.estanteria_id || '';
    document.getElementById('edit-ingreso').value = item.ingreso || '';
    document.getElementById('edit-observaciones').value = item.observaciones || '';
    document.getElementById('edit-dias_prestamo').value = item.dias_prestamo || '';
        
        // Seleccionar el estado
        const radios = document.querySelectorAll('#modal-editar input[name="estado"]');
        radios.forEach(radio => {
            radio.checked = radio.value === item.estado;
        });
        
        // Mostrar/ocultar deterioro y límite de días según el estado
        verificarEstadoEdicion();
        
        const form = document.getElementById('form-editar');
        form.action = `/inventario/${item.cota}`;
        
        document.getElementById('modal-editar').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
    }

    function cerrarModalEditar() {
        document.getElementById('modal-editar').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Función para confirmar eliminación (usa el mismo SweetAlert2 del resto del sistema)
    function confirmarEliminarLibro(cota, titulo) {
        confirmar(
            '¿Eliminar libro?',
            `¿Estás seguro de que deseas eliminar el libro "${titulo}" (Cota: ${cota})? Esta acción no se puede deshacer.`,
            function() {
                document.getElementById(`form-eliminar-${cota}`).submit();
            }
        );
    }

    // Cerrar modales con tecla ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModalRegistro();
            cerrarModalEditar();
            cerrarModalVer();
            cerrarModalHistorial();
            cerrarModalNuevoAutor();
            cerrarModalNuevaEstanteria();
            cerrarModalNuevoPasillo();
            cerrarModalReportes();
        }
    });

    // ============================================================
    // AUTORES: registrar uno nuevo sin salir del formulario de Inventario
    // ============================================================

    // Si el autor guardado en el registro (item.autor) no existe como
    // <option> en el select (por ejemplo, data histórica que no coincide
    // exactamente con el catálogo), se agrega temporalmente para no dejar
    // el campo en blanco al editar.
    function asegurarOpcionAutor(selectId, nombreAutor) {
        if (!nombreAutor) return;
        const select = document.getElementById(selectId);
        if (!select) return;

        const existe = Array.from(select.options).some(opt => opt.value === nombreAutor);
        if (!existe) {
            const option = document.createElement('option');
            option.value = nombreAutor;
            option.textContent = nombreAutor + ' (no está en el catálogo de autores)';
            select.appendChild(option);
        }
    }

    let selectDestinoAutor = 'autor';

    function abrirModalNuevoAutor(selectId) {
        selectDestinoAutor = selectId;
        document.getElementById('nuevo-autor-nombre').value = '';
        document.getElementById('nuevo-autor-error').style.display = 'none';
        document.getElementById('modal-nuevo-autor').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
        document.getElementById('nuevo-autor-nombre').focus();
    }

    function cerrarModalNuevoAutor() {
        document.getElementById('modal-nuevo-autor').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('form-nuevo-autor').addEventListener('submit', function(event) {
        event.preventDefault();

        var nombreInput = document.getElementById('nuevo-autor-nombre');
        var nombre = nombreInput.value.trim();
        var errorEl = document.getElementById('nuevo-autor-error');
        errorEl.style.display = 'none';
        errorEl.textContent = '';

        if (!nombre) return;

        var submitBtn = event.target.querySelector('button[type="submit"]');
        if (submitBtn) btnCarga(submitBtn);

        fetch('<?php echo e(route("autores.store-ajax")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ nombre: nombre })
        })
        .then(function(response) {
            return response.text().then(function(text) {
                var data = null;
                try { data = JSON.parse(text); } catch(e) { data = null; }
                return { status: response.status, data: data };
            });
        })
        .then(function(result) {
            if (submitBtn) btnLibre(submitBtn);
            var autorNombre = null;
            if (result.data && result.data.autor && result.data.autor.nombre) {
                autorNombre = result.data.autor.nombre;
            }

            // Si no viene en JSON pero autor ya existe en algún select (raro) también consideramos éxito
            if (autorNombre) {
                ['autor', 'edit-autor'].forEach(function(selectId) {
                    var select = document.getElementById(selectId);
                    if (!select) return;
                    var exists = false;
                    for (var i = 0; i < select.options.length; i++) {
                        if (select.options[i].value === autorNombre) { exists = true; break; }
                    }
                    if (!exists) {
                        var option = document.createElement('option');
                        option.value = autorNombre;
                        option.textContent = autorNombre;
                        select.appendChild(option);
                    }
                });
                var destino = document.getElementById(selectDestinoAutor);
                if (destino) destino.value = autorNombre;
                if (typeof limpiarFiltroSelect === 'function') limpiarFiltroSelect(selectDestinoAutor);
                errorEl.style.display = 'none';
                errorEl.textContent = '';
                cerrarModalNuevoAutor();
                if (typeof mostrarInfo === 'function') {
                    mostrarInfo('Autor "' + autorNombre + '" registrado y seleccionado.');
                }
                return;
            }

            // Si status 2xx pero no hay autor en respuesta, revisar si ya fue agregado por otro lado
            if (result.status >= 200 && result.status < 300) {
                // Intentar añadir con el nombre escrito
                var nombreA = nombre;
                ['autor', 'edit-autor'].forEach(function(selectId) {
                    var select = document.getElementById(selectId);
                    if (!select) return;
                    var exists = false;
                    for (var i = 0; i < select.options.length; i++) {
                        if (select.options[i].value === nombreA) { exists = true; break; }
                    }
                    if (!exists) {
                        var option = document.createElement('option');
                        option.value = nombreA;
                        option.textContent = nombreA;
                        select.appendChild(option);
                    }
                });
                var destino2 = document.getElementById(selectDestinoAutor);
                if (destino2) destino2.value = nombreA;
                if (typeof limpiarFiltroSelect === 'function') limpiarFiltroSelect(selectDestinoAutor);
                errorEl.style.display = 'none';
                errorEl.textContent = '';
                cerrarModalNuevoAutor();
                if (typeof mostrarInfo === 'function') {
                    mostrarInfo('Autor "' + nombreA + '" registrado y seleccionado.');
                }
                return;
            }

            // Errores reales
            if (result.data && result.data.message) {
                errorEl.textContent = result.data.message;
                errorEl.style.display = 'block';
                return;
            }
            errorEl.textContent = 'No se pudo registrar el autor.';
            errorEl.style.display = 'block';
        })
        .catch(function(err) {
            if (submitBtn) btnLibre(submitBtn);
            // A veces el fetch falla pero el registro ya ocurrió
            var nombreB = nombre;
            ['autor', 'edit-autor'].forEach(function(selectId) {
                var select = document.getElementById(selectId);
                if (!select) return;
                var exists = false;
                for (var i = 0; i < select.options.length; i++) {
                    if (select.options[i].value === nombreB) { exists = true; break; }
                }
                if (!exists) {
                    var option = document.createElement('option');
                    option.value = nombreB;
                    option.textContent = nombreB;
                    select.appendChild(option);
                }
            });
            var destino3 = document.getElementById(selectDestinoAutor);
            if (destino3) destino3.value = nombreB;
            if (typeof limpiarFiltroSelect === 'function') limpiarFiltroSelect(selectDestinoAutor);
            errorEl.style.display = 'none';
            errorEl.textContent = '';
            cerrarModalNuevoAutor();
            if (typeof mostrarInfo === 'function') {
                mostrarInfo('Autor "' + nombreB + '" registrado y seleccionado.');
            }
        });
    });


    // ============================================================
    // ESTANTERÍAS: registrar una nueva sin salir del formulario de Inventario
    // ============================================================

    let selectDestinoEstanteria = 'estanteria_id';
    var pasilloOptionsCache = [];

    // Filtra el select de pasillos según la sala elegida (cascada como en el módulo de ubicaciones)
    function filtrarPasillosPorSala() {
        var pasilloSelect = document.getElementById('nueva-estanteria-pasillo');
        var salaId = document.getElementById('nueva-estanteria-sala').value;

        if (!pasilloOptionsCache.length) {
            pasilloOptionsCache = Array.prototype.slice.call(pasilloSelect.options);
        }

        pasilloSelect.innerHTML = '';
        pasilloOptionsCache.forEach(function(opt) {
            if (!opt.value) {
                pasilloSelect.appendChild(opt.cloneNode(true));
            } else if (!salaId || opt.getAttribute('data-sala-id') === salaId) {
                pasilloSelect.appendChild(opt.cloneNode(true));
            }
        });
    }

    document.getElementById('nueva-estanteria-sala').addEventListener('change', function() {
        filtrarPasillosPorSala();
        document.getElementById('nueva-estanteria-pasillo').value = '';
    });

    function abrirModalNuevaEstanteria(selectId) {
        selectDestinoEstanteria = selectId;
        document.getElementById('nueva-estanteria-sala').value = '';
        document.getElementById('nueva-estanteria-pasillo').value = '';
        filtrarPasillosPorSala();
        document.getElementById('nueva-estanteria-numero').value = '';
        document.getElementById('nueva-estanteria-genero').value = '';
        document.getElementById('nueva-estanteria-error').style.display = 'none';
        document.getElementById('nueva-estanteria-error').textContent = '';
        document.getElementById('modal-nueva-estanteria').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
        document.getElementById('nueva-estanteria-sala').focus();
    }

    function cerrarModalNuevaEstanteria() {
        document.getElementById('modal-nueva-estanteria').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('form-nueva-estanteria').addEventListener('submit', function(event) {
        event.preventDefault();

        var pasilloId = document.getElementById('nueva-estanteria-pasillo').value;
        var numero = document.getElementById('nueva-estanteria-numero').value.trim();
        var genero = document.getElementById('nueva-estanteria-genero').value.trim();
        var errorEl = document.getElementById('nueva-estanteria-error');
        errorEl.style.display = 'none';
        errorEl.textContent = '';

        if (!pasilloId || !numero || !genero) {
            errorEl.textContent = 'Debe seleccionar la sala y el pasillo, y completar el número y el género de la estantería.';
            errorEl.style.display = 'block';
            return;
        }

        var submitBtn = event.target.querySelector('button[type="submit"]');
        if (submitBtn) btnCarga(submitBtn);

        fetch('<?php echo e(route("estanterias.store-ajax")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                pasillo_id: pasilloId,
                numero: numero,
                genero: genero
            })
        })
        .then(function(response) {
            return response.text().then(function(text) {
                var data = null;
                try { data = JSON.parse(text); } catch(e) { data = null; }
                return { status: response.status, data: data };
            });
        })
        .then(function(result) {
            if (submitBtn) btnLibre(submitBtn);

            if (result.data && result.data.success && result.data.estanteria) {
                var estanteria = result.data.estanteria;
                var etiqueta = 'Estantería ' + estanteria.numero + ' (' + estanteria.genero + ') — Pasillo ' + (estanteria.pasillo_numero || '—') + ' — ' + (estanteria.sala_nombre || 'Sin sala');

                ['estanteria_id', 'edit-estanteria_id'].forEach(function(selectId) {
                    var select = document.getElementById(selectId);
                    if (!select) return;
                    var exists = false;
                    for (var i = 0; i < select.options.length; i++) {
                        if (String(select.options[i].value) === String(estanteria.id)) { exists = true; break; }
                    }
                    if (!exists) {
                        var option = document.createElement('option');
                        option.value = estanteria.id;
                        option.textContent = etiqueta;
                        select.appendChild(option);
                    }
                });

                var destino = document.getElementById(selectDestinoEstanteria);
                if (destino) destino.value = estanteria.id;

                cerrarModalNuevaEstanteria();
                if (typeof mostrarInfo === 'function') {
                    mostrarInfo('Estantería "' + estanteria.numero + '" registrada y seleccionada.');
                }
                return;
            }

            // Errores reales
            if (result.data && result.data.message) {
                errorEl.textContent = result.data.message;
                errorEl.style.display = 'block';
                return;
            }
            errorEl.textContent = 'No se pudo registrar la estantería.';
            errorEl.style.display = 'block';
        })
        .catch(function(err) {
            if (submitBtn) btnLibre(submitBtn);
            errorEl.textContent = 'No se pudo conectar con el servidor. Intente nuevamente.';
            errorEl.style.display = 'block';
        });
    });


    // ============================================================
    // PASILLOS: registrar uno nuevo desde el modal de estanterías
    // ============================================================

    function abrirModalNuevoPasillo() {
        document.getElementById('nuevo-pasillo-sala').value = '';
        document.getElementById('nuevo-pasillo-numero').value = '';
        document.getElementById('nuevo-pasillo-error').style.display = 'none';
        document.getElementById('nuevo-pasillo-error').textContent = '';
        document.getElementById('modal-nuevo-pasillo').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
        document.getElementById('nuevo-pasillo-sala').focus();
    }

    function cerrarModalNuevoPasillo() {
        document.getElementById('modal-nuevo-pasillo').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('form-nuevo-pasillo').addEventListener('submit', function(event) {
        event.preventDefault();

        var salaId = document.getElementById('nuevo-pasillo-sala').value;
        var numero = document.getElementById('nuevo-pasillo-numero').value.trim();
        var errorEl = document.getElementById('nuevo-pasillo-error');
        errorEl.style.display = 'none';
        errorEl.textContent = '';

        if (!salaId || !numero) {
            errorEl.textContent = 'Debe seleccionar la sala e indicar el número del pasillo.';
            errorEl.style.display = 'block';
            return;
        }

        var submitBtn = event.target.querySelector('button[type="submit"]');
        if (submitBtn) btnCarga(submitBtn);

        fetch('<?php echo e(route("pasillos.store-ajax")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                sala_id: salaId,
                numero: numero
            })
        })
        .then(function(response) {
            return response.text().then(function(text) {
                var data = null;
                try { data = JSON.parse(text); } catch(e) { data = null; }
                return { status: response.status, data: data };
            });
        })
        .then(function(result) {
            if (submitBtn) btnLibre(submitBtn);

            if (result.data && result.data.success && result.data.pasillo) {
                var pasillo = result.data.pasillo;
                var pasilloSelect = document.getElementById('nueva-estanteria-pasillo');

                // Preparar el select de pasillos del modal de estantería:
                // filtrar por la sala del nuevo pasillo y agregarlo si falta
                document.getElementById('nueva-estanteria-sala').value = pasillo.sala_id;
                filtrarPasillosPorSala();

                var existe = false;
                for (var i = 0; i < pasilloSelect.options.length; i++) {
                    if (String(pasilloSelect.options[i].value) === String(pasillo.id)) { existe = true; break; }
                }
                if (!existe) {
                    var option = document.createElement('option');
                    option.value = pasillo.id;
                    option.textContent = pasillo.numero;
                    option.setAttribute('data-sala-id', pasillo.sala_id);
                    pasilloSelect.appendChild(option);
                    if (pasilloOptionsCache.length) {
                        pasilloOptionsCache.push(option.cloneNode(true));
                    }
                }
                pasilloSelect.value = pasillo.id;

                cerrarModalNuevoPasillo();
                if (typeof mostrarInfo === 'function') {
                    mostrarInfo('Pasillo "' + pasillo.numero + '" registrado y seleccionado en la sala "' + (pasillo.sala_nombre || '—') + '".');
                }
                return;
            }

            // Errores reales
            if (result.data && result.data.message) {
                errorEl.textContent = result.data.message;
                errorEl.style.display = 'block';
                return;
            }
            errorEl.textContent = 'No se pudo registrar el pasillo.';
            errorEl.style.display = 'block';
        })
        .catch(function(err) {
            if (submitBtn) btnLibre(submitBtn);
            errorEl.textContent = 'No se pudo conectar con el servidor. Intente nuevamente.';
            errorEl.style.display = 'block';
        });
    });


    // Cerrar modales haciendo clic fuera
    document.querySelectorAll('.modal-overlay, .modal-ver-overlay').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                this.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    });

    // Inicializar Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
        
        // Inicializar estado del formulario de registro
        verificarEstadoRegistro();

        // Selects de autores como buscadores
        if (typeof prepararSelectBuscable === 'function') {
            prepararSelectBuscable('autor');
            prepararSelectBuscable('edit-autor');
        }

        <?php if($errors->any()): ?>
            // Si el formulario de registro fue reenviado con errores de validación,
            // se vuelve a abrir el modal automáticamente para que el usuario los vea.
            abrirModalRegistro();
        <?php endif; ?>
    });
</script>

<script src="<?php echo e(asset('js/plantilla.js')); ?>?v=<?php echo e(filemtime(public_path('js/plantilla.js'))); ?>"></script>
<script src="<?php echo e(asset('js/paginacion.js')); ?>"></script>
<script src="<?php echo e(asset('js/validaciones.js')); ?>"></script>
<script src="<?php echo e(asset('js/inventario.js')); ?>"></script>

</body> 
</html>