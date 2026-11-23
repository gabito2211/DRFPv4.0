<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Ubicación - Salas | Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/registrar_visitas_modal.css')); ?>">
    <style>
        .detalle-lista {
            list-style: none;
            margin: 8px 0 0 0;
            padding: 0;
        }
        .detalle-lista li {
            padding: 8px 12px;
            border: 1px solid var(--borde);
            border-radius: 6px;
            margin-bottom: 6px;
            background: var(--fondo-sitio);
            color: var(--texto-suave);
            font-size: 0.9rem;
        }
        .detalle-lista li small {
            color: var(--texto-claro);
        }
        .detalle-vacio {
            color: var(--texto-claro);
            font-size: 0.9rem;
        }
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
        .seccion-titulo {
            font-size: 1rem;
            font-weight: 600;
            color: var(--texto-oscuro);
            margin: 10px 0 5px 0;
            padding-bottom: 6px;
            border-bottom: 1px solid var(--borde);
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
                    <h1>Ubicación - Salas</h1>
                    <p style="color: var(--texto-suave);">Maestro de salas de la biblioteca y los pasillos que contienen.</p>
                </div>
            </div>

            <!-- FORMULARIO DE REGISTRO -->
            <div id="form-container" class="contenedor-form" style="max-width: 100%; display: none;">
                <div class="titulo-form">
                    <h2>Nueva Sala</h2>
                    <button type="button" id="btn-cerrar-form" style="background:none; border:none; color:var(--error); cursor:pointer;">
                        <i data-lucide="x-circle"></i>
                    </button>
                </div>

                <form method="POST" action="<?php echo e(route('ubicacion.salas.store')); ?>">
                    <?php echo e(csrf_field()); ?>

                    <div class="rejilla-form">
                        <div class="grupo-campo">
                            <label for="nombre">Nombre de la sala</label>
                            <input required name="nombre" type="text" id="nombre" class="entrada" maxlength="255" placeholder="Ej. Sala Principal">
                        </div>
                        <div class="grupo-campo ancho-total">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="area-texto" placeholder="Descripción de la sala..."></textarea>
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
                    <h3>Listado de Salas</h3>
                    <div class="busqueda-rapida">
                        <i data-lucide="search" size="18" style="color: var(--texto-suave);"></i>
                        <input type="text" id="input-filtro-tabla" placeholder="Buscar por nombre o descripción">
                    </div>
                    <button id="btn-toggle-form" class="btn btn-azul">
                        <i data-lucide="plus-circle"></i>
                        <span>Nueva Sala</span>
                    </button>
                </div>

                <table id="tabla-salas">
                    <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th style="width: 110px;">Pasillos</th>
                            <th style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        <?php $__empty_1 = true; $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sala): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($sala->id); ?></strong></td>
                            <td><?php echo e($sala->nombre); ?></td>
                            <td><?php echo e($sala->descripcion ?: 'Sin descripción'); ?></td>
                            <td><span class="count-badge"><?php echo e($sala->pasillos_count); ?></span></td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <button type="button" class="btn-icon" title="Ver detalles" onclick="verSala(<?php echo e($sala->id); ?>)">
                                        <i data-lucide="eye"></i>
                                    </button>
                                    <button type="button" class="btn-icon" title="Editar" onclick="editarSala(<?php echo e($sala->id); ?>)">
                                        <i data-lucide="edit-3" style="color: #00b894;"></i>
                                    </button>
                                    <button type="button" class="btn-icon" title="Eliminar"
                                        data-nombre="<?php echo e($sala->nombre); ?>"
                                        data-pasillos="<?php echo e($sala->pasillos_count); ?>"
                                        onclick="eliminarSala(<?php echo e($sala->id); ?>, this)">
                                        <i data-lucide="trash-2" style="color: #d63031;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--texto-claro);">No hay salas registradas.</td>
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

    <!-- MODAL EDITAR SALA -->
    <div id="modal-editar-sala" class="modal-personalizado" style="display: none;">
        <div class="modal-contenido" style="max-width: 560px;">
            <div class="modal-header">
                <h2>Editar Sala</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModal('modal-editar-sala')">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form id="form-editar-sala" method="POST">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="_method" value="PUT">
                <div class="rejilla-form">
                    <div class="grupo-campo ancho-total">
                        <label>Nombre de la sala</label>
                        <input type="text" id="edit-nombre" name="nombre" class="entrada" maxlength="255" required>
                    </div>
                    <div class="grupo-campo ancho-total">
                        <label>Descripción</label>
                        <textarea id="edit-descripcion" name="descripcion" class="area-texto"></textarea>
                    </div>
                </div>
                <div class="fila-botones">
                    <button type="button" class="btn btn-gris" onclick="cerrarModal('modal-editar-sala')">Cancelar</button>
                    <button type="submit" class="btn btn-azul"><i data-lucide="save"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL VER SALA -->
    <div id="modal-ver-sala" class="modal-personalizado" style="display: none;">
        <div class="modal-contenido" style="max-width: 640px;">
            <div class="modal-header">
                <h2>Detalles de la Sala</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModal('modal-ver-sala')">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="ver-grid">
                <div class="ver-item">
                    <span>ID</span>
                    <span id="ver-id">-</span>
                </div>
                <div class="ver-item">
                    <span>Nombre</span>
                    <span id="ver-nombre">-</span>
                </div>
                <div class="ver-item ancho-total">
                    <span>Descripción</span>
                    <span id="ver-descripcion">-</span>
                </div>
            </div>
            <div class="seccion-titulo">Pasillos de la sala</div>
            <ul class="detalle-lista" id="ver-pasillos"></ul>
            <div class="fila-botones">
                <button type="button" class="btn btn-gris" onclick="cerrarModal('modal-ver-sala')">
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

        function editarSala(id) {
            fetch(`/ubicacion/salas/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-nombre').value = data.nombre || '';
                    document.getElementById('edit-descripcion').value = data.descripcion || '';
                    document.getElementById('form-editar-sala').action = `/ubicacion/salas/${id}`;
                    document.getElementById('modal-editar-sala').style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => console.error('Error:', error));
        }

        function verSala(id) {
            fetch(`/ubicacion/salas/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('ver-id').textContent = data.id;
                    document.getElementById('ver-nombre').textContent = data.nombre || '-';
                    document.getElementById('ver-descripcion').textContent = data.descripcion || 'Sin descripción';

                    const lista = document.getElementById('ver-pasillos');
                    lista.innerHTML = '';

                    if (!data.pasillos || data.pasillos.length === 0) {
                        const vacio = document.createElement('li');
                        vacio.className = 'detalle-vacio';
                        vacio.textContent = 'Esta sala no tiene pasillos registrados.';
                        lista.appendChild(vacio);
                    } else {
                        data.pasillos.forEach(function (pasillo) {
                            const item = document.createElement('li');
                            const total = pasillo.estanterias ? pasillo.estanterias.length : 0;
                            item.innerHTML = '<strong>Pasillo ' + pasillo.numero + '</strong> ' +
                                '<small>(' + total + ' estantería' + (total === 1 ? '' : 's') + ')</small>';
                            lista.appendChild(item);
                        });
                    }

                    document.getElementById('modal-ver-sala').style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => console.error('Error:', error));
        }

        function eliminarSala(id, boton) {
            const nombre = boton.dataset.nombre;
            const pasillos = boton.dataset.pasillos;

            let mensaje = 'Se eliminará la sala "' + nombre + '".';
            if (parseInt(pasillos) > 0) {
                mensaje += ' También se eliminarán sus pasillos y estanterías asociadas.';
            }

            confirmar('¿Eliminar sala?', mensaje, function () {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/ubicacion/salas/${id}`;

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
