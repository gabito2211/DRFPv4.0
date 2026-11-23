<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Ubicación - Estanterías | Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/registrar_visitas_modal.css')); ?>">
    <style>
        .ver-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .ver-grid .ancho-total {
            grid-column: 1 / -1;
        }
        .ver-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .ver-item span:first-child {
            font-size: 0.8rem;
            color: var(--texto-claro);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .ver-item span:last-child {
            color: var(--texto-oscuro);
            word-break: break-word;
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
                    <h1>Ubicación - Estanterías</h1>
                    <p style="color: var(--texto-suave);">Maestro de estanterías por pasillo y su género literario.</p>
                </div>
            </div>

            <!-- FORMULARIO DE REGISTRO -->
            <div id="form-container" class="contenedor-form" style="max-width: 100%; display: none;">
                <div class="titulo-form">
                    <h2>Nueva Estantería</h2>
                    <button type="button" id="btn-cerrar-form" style="background:none; border:none; color:var(--error); cursor:pointer;">
                        <i data-lucide="x-circle"></i>
                    </button>
                </div>

                <form method="POST" action="<?php echo e(route('ubicacion.estanterias.store')); ?>">
                    <?php echo e(csrf_field()); ?>

                    <div class="rejilla-form">
                        <div class="grupo-campo">
                            <label for="numero">Número de la estantería</label>
                            <input required name="numero" type="text" id="numero" class="entrada" maxlength="50" placeholder="Ej. 001">
                        </div>
                        <div class="grupo-campo">
                            <label for="genero">Género</label>
                            <input required name="genero" type="text" id="genero" class="entrada" maxlength="100" list="lista-generos" placeholder="Ej. Geografía">
                            <datalist id="lista-generos">
                                <option value="Geografía">
                                <option value="Cuento">
                                <option value="Clásicos">
                                <option value="Historia">
                                <option value="Ciencia">
                                <option value="Literatura">
                                <option value="Referencia">
                                <option value="Infantil">
                                <option value="Juvenil">
                                <option value="Poesía">
                                <option value="Teatro">
                                <option value="Diccionarios">
                                <option value="Enciclopedias">
                                <option value="Otros">
                            </datalist>
                        </div>
                        <div class="grupo-campo ancho-total">
                            <label for="pasillo_id">Pasillo</label>
                            <select name="pasillo_id" id="pasillo_id" class="entrada" required>
                                <option value="">Seleccione un pasillo...</option>
                                <?php $__currentLoopData = $pasillos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pasillo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($pasillo->id); ?>">
                                        Pasillo <?php echo e($pasillo->numero); ?><?php if($pasillo->sala): ?> — <?php echo e($pasillo->sala->nombre); ?><?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="fila-botones">
                        <button type="submit" class="btn btn-azul">
                            <i data-lucide="save"></i> Guardar Registro
                        </button>
                        <button type="reset" class="btn btn-gris" id="btn-cancelar">Cancelar</button>
                    </div>
                </form>
            </div>

            <!-- TABLA DE REGISTROS -->
            <div class="contenedor-tabla">
                <div class="titulo-form">
                    <h3>Listado de Estanterías</h3>
                    <div class="busqueda-rapida">
                        <i data-lucide="search" size="18" style="color: var(--texto-suave);"></i>
                        <input type="text" id="input-filtro-tabla" placeholder="Buscar por número, género o pasillo">
                    </div>
                    <button id="btn-toggle-form" class="btn btn-azul">
                        <i data-lucide="plus-circle"></i>
                        <span>Nueva Estantería</span>
                    </button>
                </div>

                <table id="tabla-estanterias">
                    <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>Número</th>
                            <th>Género</th>
                            <th>Pasillo</th>
                            <th>Sala</th>
                            <th style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        <?php $__empty_1 = true; $__currentLoopData = $estanterias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estanteria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($estanteria->id); ?></strong></td>
                            <td><?php echo e($estanteria->numero); ?></td>
                            <td><?php echo e($estanteria->genero); ?></td>
                            <td><?php echo e($estanteria->pasillo ? $estanteria->pasillo->numero : 'Sin pasillo'); ?></td>
                            <td><?php echo e($estanteria->pasillo && $estanteria->pasillo->sala ? $estanteria->pasillo->sala->nombre : 'Sin sala'); ?></td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <button type="button" class="btn-icon" title="Ver detalles" onclick="verEstanteria(<?php echo e($estanteria->id); ?>)">
                                        <i data-lucide="eye"></i>
                                    </button>
                                    <button type="button" class="btn-icon" title="Editar" onclick="editarEstanteria(<?php echo e($estanteria->id); ?>)">
                                        <i data-lucide="edit-3" style="color: #00b894;"></i>
                                    </button>
                                    <button type="button" class="btn-icon" title="Eliminar"
                                        data-numero="<?php echo e($estanteria->numero); ?>"
                                        onclick="eliminarEstanteria(<?php echo e($estanteria->id); ?>, this)">
                                        <i data-lucide="trash-2" style="color: #d63031;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--texto-claro);">No hay estanterías registradas.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="paginacion-container">
                    <div class="info-paginacion" id="info-paginacion">Mostrando registros</div>
                    <div class="botones-paginacion">
                        <button class="btn-pag" id="btn-prev" disabled>
                            <i data-lucide="chevron-left" size="16"></i> Anterior
                        </button>
                        <div id="numeros-paginas" style="display: flex; gap: 5px;"></div>
                        <button class="btn-pag" id="btn-next">
                            Siguiente <i data-lucide="chevron-right" size="16"></i>
                        </button>
                    </div>
                </div>
            </div>
        </main>
        <?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <!-- MODAL EDITAR ESTANTERÍA -->
    <div id="modal-editar-estanteria" class="modal-personalizado" style="display: none;">
        <div class="modal-contenido" style="max-width: 560px;">
            <div class="modal-header">
                <h2>Editar Estantería</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModal('modal-editar-estanteria')">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form id="form-editar-estanteria" method="POST">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="_method" value="PUT">
                <div class="rejilla-form">
                    <div class="grupo-campo">
                        <label>Número de la estantería</label>
                        <input type="text" id="edit-numero" name="numero" class="entrada" maxlength="50" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Género</label>
                        <input type="text" id="edit-genero" name="genero" class="entrada" maxlength="100" list="lista-generos" required>
                    </div>
                    <div class="grupo-campo ancho-total">
                        <label>Pasillo</label>
                        <select id="edit-pasillo_id" name="pasillo_id" class="entrada" required>
                            <option value="">Seleccione un pasillo...</option>
                            <?php $__currentLoopData = $pasillos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pasillo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pasillo->id); ?>">
                                    Pasillo <?php echo e($pasillo->numero); ?><?php if($pasillo->sala): ?> — <?php echo e($pasillo->sala->nombre); ?><?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="fila-botones">
                    <button type="button" class="btn btn-gris" onclick="cerrarModal('modal-editar-estanteria')">Cancelar</button>
                    <button type="submit" class="btn btn-azul"><i data-lucide="save"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL VER ESTANTERÍA -->
    <div id="modal-ver-estanteria" class="modal-personalizado" style="display: none;">
        <div class="modal-contenido" style="max-width: 600px;">
            <div class="modal-header">
                <h2>Detalles de la Estantería</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModal('modal-ver-estanteria')">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="ver-grid">
                <div class="ver-item">
                    <span>ID</span>
                    <span id="ver-id">-</span>
                </div>
                <div class="ver-item">
                    <span>Número</span>
                    <span id="ver-numero">-</span>
                </div>
                <div class="ver-item">
                    <span>Género</span>
                    <span id="ver-genero">-</span>
                </div>
                <div class="ver-item">
                    <span>Pasillo</span>
                    <span id="ver-pasillo">-</span>
                </div>
                <div class="ver-item ancho-total">
                    <span>Sala</span>
                    <span id="ver-sala">-</span>
                </div>
            </div>
            <div class="fila-botones">
                <button type="button" class="btn btn-gris" onclick="cerrarModal('modal-ver-estanteria')">
                    <i data-lucide="x"></i> Cerrar
                </button>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('js/plantilla.js')); ?>"></script>
    <script src="<?php echo e(asset('js/paginacion.js')); ?>"></script>
    <script src="<?php echo e(asset('js/mostrar_ocultar_formularios.js')); ?>"></script>
    <script>
        function cerrarModal(id) {
            document.getElementById(id).style.display = 'none';
            document.body.style.overflow = '';
        }

        function editarEstanteria(id) {
            fetch(`/ubicacion/estanterias/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-numero').value = data.numero || '';
                    document.getElementById('edit-genero').value = data.genero || '';
                    document.getElementById('edit-pasillo_id').value = data.pasillo_id || '';
                    document.getElementById('form-editar-estanteria').action = `/ubicacion/estanterias/${id}`;
                    document.getElementById('modal-editar-estanteria').style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => console.error('Error:', error));
        }

        function verEstanteria(id) {
            fetch(`/ubicacion/estanterias/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('ver-id').textContent = data.id;
                    document.getElementById('ver-numero').textContent = data.numero || '-';
                    document.getElementById('ver-genero').textContent = data.genero || '-';
                    document.getElementById('ver-pasillo').textContent = data.pasillo ? data.pasillo.numero : 'Sin pasillo';
                    document.getElementById('ver-sala').textContent = (data.pasillo && data.pasillo.sala) ? data.pasillo.sala.nombre : 'Sin sala';

                    document.getElementById('modal-ver-estanteria').style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => console.error('Error:', error));
        }

        function eliminarEstanteria(id, boton) {
            const numero = boton.dataset.numero;

            confirmar('¿Eliminar estantería?', 'Se eliminará la estantería "' + numero + '".', function () {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/ubicacion/estanterias/${id}`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            });
        }

        window.onclick = function (event) {
            if (event.target.classList.contains('modal-personalizado')) {
                event.target.style.display = 'none';
                document.body.style.overflow = '';
            }
        };

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal-personalizado').forEach(function (modal) {
                    modal.style.display = 'none';
                });
                document.body.style.overflow = '';
            }
        });
    </script>
</body>
</html>
