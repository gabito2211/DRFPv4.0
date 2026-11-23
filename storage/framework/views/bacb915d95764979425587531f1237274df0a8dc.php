<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitas Guiadas - Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Estilos para el Modal de Ver - SEDES */
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

        /* Estilos específicos para SEDES */
        .ver-sede-titulo-seccion {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 20px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0e0e0;
        }

        .ver-sede-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .ver-sede-grid .ver-detalle-item {
            border-bottom: none;
            padding: 8px 0;
        }

        .ver-sede-grid .ver-detalle-label {
            width: auto;
            min-width: 100px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .ver-sede-grid .ver-detalle-valor {
            font-weight: 500;
        }

        .ver-sede-grid-full {
            grid-column: 1 / -1;
        }

        /* Asegura que las alertas de SweetAlert2 siempre queden por encima de los modales */
        .swal-encima-modal {
            z-index: 3000 !important;
        }

        /* Estilo para el contenedor del botón de eliminar en la tabla */
        .btn-eliminar-sede {
            color: var(--error);
            border: none;
            background: none;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .btn-eliminar-sede:hover {
            background-color: rgba(220, 53, 69, 0.1);
        }
    </style>
</head>
<body>
    <?php echo $__env->make('layouts.mensajes', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div id="main-wrapper">
        <main id="main-content">
            <div class="header-acciones" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; width: 100%; max-width: 1100px;">
                <div>
                    <h1>Sedes Bibliotecarias</h1>
                    <p style="color: var(--texto-suave);">Control de grupos, instituciones y recorridos programados.</p>
                </div>
            </div>

            <!-- FORMULARIO DE REGISTRO -->
            <div id="form-container" class="contenedor-form" style="display: none; max-width: 1100px; margin-bottom: 30px;">
                <div class="titulo-form" style="display: flex; justify-content: space-between;">
                    <h2>Nueva Sede Bibliotecaria</h2>
                    <button type="button" id="btn-cerrar-form" style="border:none; background:none; cursor:pointer; color:var(--error);">
                        <i data-lucide="x-circle"></i>
                    </button>
                </div>

                <form action="<?php echo e(route('sedes.store')); ?>" method="POST">
                    <?php echo e(csrf_field()); ?>

                    <div class="rejilla-form">
                        <!-- Campos de SEDE -->
                        <div class="grupo-campo">
                            <label for="comunidad">Comunidad</label>
                            <select class="entrada" name="comunidad" required>
                                <option value="">Seleccione...</option>
                                <option value="aristides-bastidas">Arístides Bastidas</option>
                                <option value="bolivar">Bolívar</option>
                                <option value="bruzual">Bruzual</option>
                                <option value="cocorote">Cocorote</option>
                                <option value="independencia">Independencia</option>
                                <option value="jose-antonio-paez">José Antonio Páez</option>
                                <option value="la-trinidad">La Trinidad</option>
                                <option value="manuel-monge">Manuel Monge</option>
                                <option value="nirgua">Nirgua</option>
                                <option value="pena">Peña</option>
                                <option value="paez">Paez</option>
                                <option value="san-felipe">San Felipe</option>
                                <option value="sucre">Sucre</option>
                                <option value="urachiche">Urachiche</option>
                                <option value="veroes">Veroes</option>
                            </select>
                        </div>
                        <div class="grupo-campo">
                            <label for="biblioteca">Biblioteca</label>
                            <input type="text" class="entrada" name="biblioteca" placeholder="Nombre de la Biblioteca" required>
                        </div>

                        <!-- Dirección de la SEDE -->
                        <div class="grupo-campo ancho-total">
                            <label for="direccion">Dirección exacta</label>
                            <textarea name="direccion" class="area-texto" placeholder="Dirección de la sede..." required></textarea>
                        </div>

                        <!-- Campos del ENCARGADO -->
                        <div class="grupo-campo">
                            <label for="cedula">Cédula del Encargado</label>
                            <input required name="cedula" onkeypress="return permite(event, 'num')" type="text" id="cedula" class="entrada" placeholder="V-00.000.000" maxlength="8" minlength="8">
                        </div>

                        <div class="grupo-campo">
                            <label for="nombre">Nombre y Apellido</label>
                            <input required name="nombre" onkeypress="return permite(event, 'car')" type="text" id="nombre" class="entrada" placeholder="Ej. Juan Pérez">
                        </div>

                        <div class="grupo-campo">
                            <label for="telefono">Número de Teléfono</label>
                            <input required onkeypress="return permite(event, 'num')" type="text" id="telefono" name="telefono" class="entrada" placeholder="Ej. 04121234567" maxlength="11" minlength="11">
                        </div>

                        <div class="grupo-campo">
                            <label for="telefono2">Número de Teléfono Secundario</label>
                            <input onkeypress="return permite(event, 'num')" type="text" id="telefono2" name="telefono2" class="entrada" placeholder="Ej. 04127654321" maxlength="11" minlength="11">
                        </div>

                        
                    </div>
                    <div class="fila-botones">
                        <button type="submit" class="btn btn-azul"><i data-lucide="save"></i> Guardar Registro</button>
                        <button type="reset" id="btn-cancelar" class="btn btn-gris">Cancelar</button>
                    </div>
                </form>
            </div>

            <!-- TABLA -->
            <div class="contenedor-tabla" style="max-width: 1100px; width: 100%;">
                <?php if($errors->any()): ?>
                <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; margin: 15px 0; border-radius: 8px; font-family: sans-serif;">
                    <div style="display: flex; align-items: center; gap: 10px; font-weight: bold; margin-bottom: 5px;">
                        <span style="font-size: 1.2rem;">⚠️</span>
                        <span>No se pudo procesar la solicitud:</span>
                    </div>
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(str_contains($error, 'biblioteca') || str_contains($error, 'has already been taken')): ?>
                                <li><strong>El nombre de sede ya está registrado en el sistema</strong></li>
                            <?php elseif(str_contains($error, 'cedula') && str_contains($error, 'unique')): ?>
                                <li><strong>La cédula del encargado ya está registrada en el sistema</strong></li>
                            <?php else: ?>
                                <li><?php echo e($error); ?></li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="titulo-form">
                    <h3>Registro de Bibliotecas</h3>
                    <div class="busqueda-rapida">
                        <i data-lucide="search" size="18"></i>
                        <input type="text" id="input-filtro-tabla" class="input-busqueda" placeholder="Buscar comunidad o biblioteca...">
                    </div>
                    <?php if($esPrincipal): ?>
                        <button id="btn-toggle-form" class="btn btn-azul">
                            <i data-lucide="users-2"></i> Registrar Sede
                        </button>
                    <?php endif; ?>
                </div>

                <table id="tabla-guiadas">
                    <thead>
                        <tr>
                            <th>Municipio</th>
                            <th>Biblioteca</th>
                            <th>Dirección</th>
                            <th>Encargado</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        <?php $__currentLoopData = $sedes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e(ucfirst($sede->comunidad)); ?></strong></td>
                            <td><?php echo e($sede->biblioteca); ?></td>
                            <td><span class="count-badge"><?php echo e($sede->direccion); ?></span></td>
                            <td>
                                <?php if($sede->encargado): ?>
                                    <strong><?php echo e($sede->encargado->nombre); ?></strong>
                                    <br><small style="color: var(--texto-suave);">Cédula: <?php echo e($sede->encargado->cedula); ?></small>
                                <?php else: ?>
                                    <span style="color: var(--texto-suave);">Sin encargado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($sede->encargado): ?>
                                    <?php echo e($sede->encargado->telefono); ?>

                                    <?php if($sede->encargado->telefono2): ?>
                                        <br><small style="color: var(--texto-suave);"><?php echo e($sede->encargado->telefono2); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color: var(--texto-suave);">Sin teléfono</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- BOTÓN VER (solo lectura) -->
                                <button type="button" class="btn-icon" title="Ver detalles" 
                                    onclick="verSede({
                                        comunidad: '<?php echo e($sede->comunidad); ?>',
                                        biblioteca: '<?php echo e($sede->biblioteca); ?>',
                                        direccion: '<?php echo e($sede->direccion); ?>',
                                        encargado: '<?php echo e($sede->encargado ? $sede->encargado->nombre : "Sin encargado"); ?>',
                                        cedula: '<?php echo e($sede->encargado ? $sede->encargado->cedula : ""); ?>',
                                        telefono: '<?php echo e($sede->encargado ? $sede->encargado->telefono : ""); ?>',
                                        telefono2: '<?php echo e($sede->encargado ? $sede->encargado->telefono2 : ""); ?>'
                                    })">
                                    <i data-lucide="eye"></i>
                                </button>

                                <!-- BOTÓN EDITAR (solo principal, o la propia sede) -->
                                <?php if($esPrincipal || (Auth::user()->sede_id && (int)$sede->id === (int)Auth::user()->sede_id)): ?>
                                <button type="button" class="btn-icon" title="Editar" 
                                    onclick="ModalManager.abrir('modal-sedes', {
                                        action: '<?php echo e(url('/sedes')); ?>/<?php echo e($sede->id); ?>',
                                        datos: {
                                            comunidad: '<?php echo e($sede->comunidad); ?>',
                                            biblioteca: '<?php echo e($sede->biblioteca); ?>',
                                            direccion: '<?php echo e($sede->direccion); ?>',
                                            cedula: '<?php echo e($sede->encargado ? $sede->encargado->cedula : ""); ?>',
                                            nombre: '<?php echo e($sede->encargado ? $sede->encargado->nombre : ""); ?>',
                                            telefono: '<?php echo e($sede->encargado ? $sede->encargado->telefono : ""); ?>',
                                            telefono2: '<?php echo e($sede->encargado ? $sede->encargado->telefono2 : ""); ?>'
                                        }
                                    })">
                                    <i data-lucide="edit-3"></i>
                                </button>
                                <?php endif; ?>

                                <?php if($esPrincipal): ?>
                                <!-- BOTÓN ELIMINAR MEJORADO CON SweetAlert2 -->
                                <form id="form-eliminar-<?php echo e($sede->id); ?>" action="<?php echo e(route('sedes.destroy', $sede->id)); ?>" method="POST" style="display: none;">
                                    <?php echo e(csrf_field()); ?>

                                    <?php echo e(method_field('DELETE')); ?>

                                </form>
                                <button type="button" class="btn-eliminar-sede" title="Eliminar" 
                                    onclick="confirmarEliminarSede('<?php echo e($sede->id); ?>', '<?php echo e(addslashes($sede->biblioteca)); ?>', '<?php echo e(addslashes($sede->comunidad)); ?>')">
                                    <i data-lucide="trash-2"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </main>
        <?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <!-- MODAL PARA EDITAR SEDE -->
    <div id="modal-sedes" class="contenedor-form" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; width: 90%; max-width: 600px; box-shadow: 0 0 20px rgba(0,0,0,0.5);">
        <div class="titulo-form" style="display: flex; justify-content: space-between;">
            <h2>Editar Sede Bibliotecaria</h2>
            <button type="button" onclick="ModalManager.cerrar('modal-sedes')" style="border:none; background:none; cursor:pointer; color:var(--error);">
                <i data-lucide="x-circle"></i>
            </button>
        </div>

        <form id="form-modal-sedes" method="POST">
            <?php echo e(csrf_field()); ?>

            <?php echo e(method_field('PUT')); ?>

            <div class="rejilla-form">
                <!-- Campos de SEDE -->
                <div class="grupo-campo">
                    <label>Comunidad</label>
                    <select class="entrada" name="comunidad" required>
                        <option value="aristidesb">Aristides Bastidas</option>
                        <option value="bolivar">Bolívar</option>
                        <option value="cocorote">Cocorote</option>
                        <option value="san_felipe">San Felipe</option>
                    </select>
                </div>
                <div class="grupo-campo">
                    <label>Biblioteca</label>
                    <input type="text" class="entrada" name="biblioteca" required>
                </div>

                <div class="grupo-campo ancho-total">
                    <label>Dirección exacta</label>
                    <textarea name="direccion" class="area-texto" required></textarea>
                </div>

                <!-- Campos del ENCARGADO -->
                <div class="grupo-campo">
                    <label>Cédula</label>
                    <input type="text" id="edit-cedula" name="cedula" class="entrada" required>
                </div>
                <div class="grupo-campo">
                    <label>Nombre y Apellido</label>
                    <input type="text" id="edit-nombre" name="nombre" class="entrada" required>
                </div>
                <div class="grupo-campo">
                    <label>Teléfono</label>
                    <input type="text" id="edit-telefono" name="telefono" class="entrada" required>
                </div>
                <div class="grupo-campo">
                    <label>Teléfono Secundario</label>
                    <input type="text" id="edit-telefono2" name="telefono2" class="entrada">
                </div>

                
            </div>
            <div class="fila-botones">
                <button type="submit" class="btn btn-azul"><i data-lucide="save"></i> Actualizar</button>
                <button type="button" class="btn btn-gris" onclick="ModalManager.cerrar('modal-sedes')">Cancelar</button>
            </div>
        </form>
    </div>

    <!-- MODAL PARA VER SEDE (SOLO LECTURA) CON NUEVOS ESTILOS -->
    <div id="modal-ver-sede" class="modal-ver-overlay">
        <div class="modal-ver-content">
            <div class="titulo-form" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">Detalles de la Sede</h2>
                <button type="button" onclick="cerrarModalVerSede()" style="border:none; background:none; cursor:pointer; color:var(--error); font-size: 1.5rem;">
                    <i data-lucide="x-circle"></i>
                </button>
            </div>

            <!-- Información de la Sede -->
            <div class="ver-sede-grid">
                <div class="ver-detalle-item">
                    <span class="ver-detalle-label">Municipio</span>
                    <span class="ver-detalle-valor" id="ver-comunidad">-</span>
                </div>
                <div class="ver-detalle-item">
                    <span class="ver-detalle-label">Biblioteca</span>
                    <span class="ver-detalle-valor" id="ver-biblioteca">-</span>
                </div>
                <div class="ver-detalle-item ver-sede-grid-full">
                    <span class="ver-detalle-label">Dirección</span>
                    <span class="ver-detalle-valor" id="ver-direccion">-</span>
                </div>
            </div>

            <!-- Separador -->
            <div style="border-top: 2px solid #e0e0e0; margin: 15px 0;"></div>

            <!-- Datos del Encargado -->
            <h3 class="ver-sede-titulo-seccion">👤 Datos del Encargado</h3>

            <div class="ver-sede-grid">
                <div class="ver-detalle-item">
                    <span class="ver-detalle-label">Cédula</span>
                    <span class="ver-detalle-valor" id="ver-cedula">-</span>
                </div>
                <div class="ver-detalle-item">
                    <span class="ver-detalle-label">Nombre</span>
                    <span class="ver-detalle-valor" id="ver-nombre">-</span>
                </div>
                <div class="ver-detalle-item">
                    <span class="ver-detalle-label">Teléfono</span>
                    <span class="ver-detalle-valor" id="ver-telefono">-</span>
                </div>
                <div class="ver-detalle-item">
                    <span class="ver-detalle-label">Teléfono 2</span>
                    <span class="ver-detalle-valor" id="ver-telefono2">-</span>
                </div>
            </div>

            <div class="fila-botones" style="margin-top: 25px; padding-top: 15px; border-top: 1px solid var(--borde);">
                <button type="button" class="btn btn-gris" onclick="cerrarModalVerSede()">
                    <i data-lucide="x"></i> Cerrar
                </button>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('js/plantilla.js')); ?>"></script>
    <script src="<?php echo e(asset('js/paginacion.js')); ?>"></script>
    <script src="<?php echo e(asset('js/mostrar_ocultar_formularios.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if(window.lucide) lucide.createIcons();
        });

        // Definir ModalManager si no existe
        if (typeof ModalManager === 'undefined') {
            const ModalManager = {
                abrir: function(id, options) {
                    const modal = document.getElementById(id);
                    if (!modal) return;
                    
                    if (options && options.datos) {
                        const form = modal.querySelector('form');
                        if (form) {
                            if (options.action) {
                                form.action = options.action;
                            }
                            const datos = options.datos;
                            
                            // Mapeo de campos para el modal
                            const mapeoCampos = {
                                'comunidad': 'comunidad',
                                'biblioteca': 'biblioteca',
                                'direccion': 'direccion',
                                'cedula': 'cedula',
                                'nombre': 'nombre',
                                'telefono': 'telefono',
                                'telefono2': 'telefono2'
                            };
                            
                            for (const [key, value] of Object.entries(datos)) {
                                const nombreCampo = mapeoCampos[key] || key;
                                const input = form.querySelector(`[name="${nombreCampo}"]`);
                                if (input) {
                                    input.value = value || '';
                                }
                            }
                        }
                    }
                    modal.style.display = 'block';
                },
                cerrar: function(id) {
                    const modal = document.getElementById(id);
                    if (modal) {
                        modal.style.display = 'none';
                    }
                }
            };
            window.ModalManager = ModalManager;
        }

        // Función para ver detalles de la sede (solo lectura) con nuevos estilos
        function verSede(datos) {
            // Llenar los datos en el modal
            const campos = {
                'comunidad': 'ver-comunidad',
                'biblioteca': 'ver-biblioteca',
                'direccion': 'ver-direccion',
                'cedula': 'ver-cedula',
                'encargado': 'ver-nombre',
                'telefono': 'ver-telefono',
                'telefono2': 'ver-telefono2'
            };

            for (const [key, value] of Object.entries(datos)) {
                const elementId = campos[key];
                if (elementId) {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.textContent = value || '-';
                    }
                }
            }

            // Mostrar el modal
            document.getElementById('modal-ver-sede').style.display = 'block';
            document.body.style.overflow = 'hidden';
            if (window.lucide) lucide.createIcons();
        }

        // Función para cerrar el modal de ver
        function cerrarModalVerSede() {
            document.getElementById('modal-ver-sede').style.display = 'none';
            document.body.style.overflow = '';
        }

        // Cerrar modal al hacer clic fuera
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('modal-ver-sede');
            if (event.target === modal) {
                cerrarModalVerSede();
            }
        });

        // Cerrar con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cerrarModalVerSede();
            }
        });

        // Función de confirmación para eliminar con SweetAlert2 mejorada
        function confirmarEliminarSede(id, biblioteca, comunidad) {
            Swal.fire({
                title: '¿Eliminar sede?',
                html: `
                    <div style="text-align: left; margin: 10px 0;">
                        <p><strong>Biblioteca:</strong> ${biblioteca}</p>
                        <p><strong>Municipio:</strong> ${comunidad}</p>
                    </div>
                    <p style="color: #dc3545; font-weight: 500; margin-top: 10px;">
                        ⚠️ Esta acción también eliminará el encargado si no está asociado a otra sede.
                    </p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    container: 'swal-encima-modal'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Eliminando...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Enviar el formulario
                    document.getElementById(`form-eliminar-${id}`).submit();
                }
            });
        }

        // Función para mostrar mensaje de éxito después de eliminar
        function mostrarMensajeEliminacionExito(mensaje) {
            Swal.fire({
                title: '¡Eliminado!',
                text: mensaje || 'La sede ha sido eliminada exitosamente.',
                icon: 'success',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar',
                customClass: {
                    container: 'swal-encima-modal'
                }
            });
        }

        // Función para mostrar mensaje de error al eliminar
        function mostrarMensajeEliminacionError(mensaje) {
            Swal.fire({
                title: 'Error al eliminar',
                text: mensaje || 'No se pudo eliminar la sede. Intente nuevamente.',
                icon: 'error',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Aceptar',
                customClass: {
                    container: 'swal-encima-modal'
                }
            });
        }
    </script>
</body>
</html>