<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Sistema Local - Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/registrar_visitas_modal.css')); ?>">
</head>
<body>
    <!-- Navegación lateral -->
    <?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div id="main-wrapper">
        <main id="main-content">
            <?php echo $__env->make('layouts.mensajes', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="header-acciones">
                <div>
                    <h1>Gestión de Autores</h1>
                    <p style="color: var(--texto-suave);">Control de autores para libros.</p>
                </div> 
            </div>
            <!-- Contenedor del Formulario (Oculto por defecto) -->
            <div id="form-container" class="contenedor-form" style="max-width: 100%; display: none;">
                <div class="titulo-form">
                    <h2>Nuevo Autor</h2>
                    <button type="button" id="btn-cerrar-form" style="background:none; border:none; color:var(--error); cursor:pointer;">
                        <i data-lucide="x-circle"></i>
                    </button>
                </div>

                <form method="POST" action="<?php echo e(route('autores.store')); ?>" id="attendance-form">
                    <?php echo e(csrf_field()); ?>


                    <div class="grupo-campo">
                        <label for="nombre">Nombre del autor</label>
                        <input required name="nombre" type="text" id="nombre" class="entrada" placeholder="Ej. Gabriel García Márquez">
                    </div>

                    <div class="fila-botones">
                        <button type="submit" class="btn btn-azul">
                            <i data-lucide="save"></i> Guardar Registro
                        </button>
                        <button type="reset" class="btn btn-gris" id="btn-cancelar">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
            <!-- Tabla de Registros -->
            <div class="contenedor-tabla">
                <div class="titulo-form">
                    <h3>Listado de Autores</h3>
                    <div class="busqueda-rapida">
                        <i data-lucide="search" size="18" style="color: var(--texto-suave);"></i>
                        <input type="text" id="input-filtro-tabla" placeholder="Buscar por nombre">
                    </div>
                    <button id="btn-toggle-form" class="btn btn-azul">
                        <i data-lucide="plus-circle"></i>
                        <span>Nuevo Registro</span>
                    </button>
                </div>
                <table id="tabla-autores">
                    <thead>
                        <tr>
                            <th>Autor</th>
                            <th>Libros registrados</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        <?php $__currentLoopData = $autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($autor->nombre); ?></td>
                            <td><?php echo e($autor->libros()->count()); ?></td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <button type="button" class="btn-icon" title="Editar" onclick="editarAutor(<?php echo e($autor->id); ?>)">
                                        <i data-lucide="edit-3" style="color: #00b894;"></i>
                                    </button>
                                    <button type="button" class="btn-icon" title="Eliminar" onclick="eliminarAutor(<?php echo e($autor->id); ?>)">
                                        <i data-lucide="trash-2" style="color: #d63031;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <!-- Controles de Paginación -->
                <div class="paginacion-container">
                    <div class="info-paginacion" id="info-paginacion">
                        Mostrando registros del 1 al <?php echo e(count($autores)); ?>

                    </div>
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
        <!-- Pie de página -->
        <?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <!-- Modal de Editar Autor -->
    <div id="modal-editar-autor" class="modal-personalizado" style="display: none;">
        <div class="modal-contenido" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Editar Autor</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModalEditar()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form id="form-editar-autor" method="POST">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="_method" value="PUT">
                <div class="rejilla-form">
                    <div class="grupo-campo ancho-total">
                        <label>Nombre del autor</label>
                        <input type="text" id="edit-nombre" name="nombre" class="entrada" required>
                    </div>
                </div>
                <div class="fila-botones">
                    <button type="button" class="btn btn-gris" onclick="cerrarModalEditar()">Cancelar</button>
                    <button type="submit" class="btn btn-azul">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editarAutor(id) {
            fetch(`/autores/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-nombre').value = data.nombre;
                    document.getElementById('form-editar-autor').action = `/autores/${id}`;
                    document.getElementById('modal-editar-autor').style.display = 'flex';
                })
                .catch(error => console.error('Error:', error));
        }

        function cerrarModalEditar() {
            document.getElementById('modal-editar-autor').style.display = 'none';
        }

        function eliminarAutor(id) {
            confirmar('¿Estás seguro?', 'Se eliminará permanentemente este autor. Solo es posible si no tiene libros registrados en el inventario.', function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/autores/${id}`;

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

        window.onclick = function(event) {
            if (event.target.classList.contains('modal-personalizado')) {
                event.target.style.display = 'none';
            }
        }
    </script>

    <script src="<?php echo e(asset('js/plantilla.js')); ?>"></script>
    <script src="<?php echo e(asset('js/paginacion.js')); ?>"></script>
    <script src="<?php echo e(asset('js/validaciones.js')); ?>"></script>
    <script src="<?php echo e(asset('js/mostrar_ocultar_formularios.js')); ?>"></script>
</body>
</html>
