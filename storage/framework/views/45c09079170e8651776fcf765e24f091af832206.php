<!DOCTYPE html>
<html lang="es">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Local - Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
</head>

<script>
    function manejarSeleccionFoto(files) {
        if (!files || files.length === 0) return;
        var file = files[0];
        if (!file.type.match(/^image\//)) {
            alert('Por favor selecciona un archivo de imagen válido (JPG, PNG, WEBP, etc.)');
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('img-preview');
            if (preview) preview.src = e.target.result;

            var promptEl = document.getElementById('drop-zone-foto-prompt');
            var previewContainer = document.getElementById('drop-zone-foto-preview');
            if (promptEl) promptEl.style.display = 'none';
            if (previewContainer) previewContainer.style.display = 'flex';

            var nameEl = document.getElementById('foto-nombre-archivo');
            var sizeEl = document.getElementById('foto-tamano-archivo');
            if (nameEl) nameEl.innerText = file.name;
            if (sizeEl) sizeEl.innerText = (file.size / 1024).toFixed(1) + ' KB';

            if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
        };
        reader.readAsDataURL(file);
    }

    function quitarFoto(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        var input = document.getElementById('foto');
        if (input) input.value = '';

        var preview = document.getElementById('img-preview');
        if (preview) preview.src = '<?php echo e(asset("img/user-default.png")); ?>';

        var promptEl = document.getElementById('drop-zone-foto-prompt');
        var previewContainer = document.getElementById('drop-zone-foto-preview');
        if (promptEl) promptEl.style.display = 'flex';
        if (previewContainer) previewContainer.style.display = 'none';

        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
    }

    function manejarSeleccionEditFoto(files) {
        if (!files || files.length === 0) return;
        var file = files[0];
        if (!file.type.match(/^image\//)) {
            alert('Por favor selecciona un archivo de imagen válido');
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('preview-editar');
            if (preview) preview.src = e.target.result;

            var info = document.getElementById('edit-foto-info');
            if (info) info.innerText = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        };
        reader.readAsDataURL(file);
    }
</script>

<body>
<?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('layouts.mensajes', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<div id="main-wrapper">
    <main id="main-content">
        <div class="header-acciones">
            <div>
                <h1>Emisión de Carnets</h1>
                <p style="color: var(--texto-suave);">Creacion, control y actualizacion de carnets bibliotecarios.</p>
            </div> 
        </div>
        <!-- Formulario de Registro -->
        <div class="contenedor-form" id="form-container" style="display: none;">
            <div class="titulo-form">
                <h2>Nuevo Registro de Carnet</h2>
                <button type="button" id="btn-cerrar-form" class="btn-cerrar" style="border:none; background:none; cursor:pointer; color:var(--error);">
                    <i data-lucide="x-circle"></i>
                </button>
            </div>
            <form action="<?php echo e(route('carnets.registrar')); ?>" method="POST" enctype="multipart/form-data" novalidate>
                <?php echo e(csrf_field()); ?>

                <div class="rejilla-form">
                    <div class="grupo-campo">
                        <label for="id-card">Cédula</label>
                        <input required onkeypress="return permite(event, 'num')" type="text" id="id-card" name="id-card" class="entrada" placeholder="V-00.000.000" maxlength="8" minlength="8" >
                    </div>
                    <div class="grupo-campo">
                        <label for="num-carnet">Número de Carnet (Generado)</label>
                        <input type="text" name="num-carnet" id="num-carnet" value="<?php echo isset($codigoAutomatico) ? $codigoAutomatico : ''; ?>" class="entrada" readonly>
                    </div>
                    <div class="grupo-campo ancho-total">
                        <label style="font-weight: 600; display: block; margin-bottom: 6px;">Fotografía del Titular</label>
                        
                        <!-- Contenedor Drag & Drop de Foto -->
                        <div id="drop-zone-foto" style="border: 2px dashed #3498db; border-radius: 12px; padding: 22px 15px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.25s ease;">
                            <input type="file" name="foto" id="foto" accept="image/*" style="display: none;" onchange="manejarSeleccionFoto(this.files)">
                            
                            <!-- Estado inicial: sin foto seleccionada -->
                            <div id="drop-zone-foto-prompt" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;">
                                <div style="width: 52px; height: 52px; border-radius: 50%; background: #e8f4fc; display: flex; align-items: center; justify-content: center; margin-bottom: 4px;">
                                    <i data-lucide="camera" size="26" style="color: #3498db;"></i>
                                </div>
                                <p style="margin: 0; font-weight: 600; color: var(--texto-oscuro); font-size: 0.95rem;">
                                    Arrastra la foto aquí o <span style="color: #3498db; text-decoration: underline;">haz clic para seleccionar</span>
                                </p>
                                <small style="color: var(--texto-suave); font-size: 0.8rem;">Formatos soportados: JPG, PNG, JPEG o WEBP (máx. 5MB)</small>
                            </div>

                            <!-- Estado con foto seleccionada / vista previa -->
                            <div id="drop-zone-foto-preview" style="display: none; align-items: center; justify-content: center; gap: 18px;">
                                <div style="position: relative;">
                                    <img id="img-preview" src="<?php echo e(asset('img/user-default.png')); ?>" alt="Vista previa" style="width: 100px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #3498db; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                    <span style="position: absolute; bottom: 4px; right: 4px; background: #27ae60; color: white; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">✓</span>
                                </div>
                                <div style="text-align: left;">
                                    <strong id="foto-nombre-archivo" style="display: block; color: var(--texto-oscuro); font-size: 0.9rem; max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">foto.jpg</strong>
                                    <small id="foto-tamano-archivo" style="color: var(--texto-suave); display: block; margin-top: 2px; font-size: 0.8rem;">120 KB</small>
                                    <button type="button" onclick="quitarFoto(event)" class="btn btn-gris" style="margin-top: 8px; padding: 4px 10px; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px; color: #e74c3c; border-color: #fca5a5; cursor: pointer;">
                                        <i data-lucide="trash-2" size="14"></i> Cambiar / Quitar foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grupo-campo">
                        <label for="fullname">Nombre y Apellido</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input required onkeypress="return permite(event, 'car')" type="text" id="fullname" name="fullname" class="entrada" placeholder="Ej. Juan Pérez" readonly>
                            <button type="button" id="btn-desbloquear" class="btn btn-gris" style="padding: 10px;" title="Desbloquear para editar nombre">
                                <i data-lucide="lock"></i>
                            </button>
                        </div>
                    </div>
                    <div class="grupo-campo">
                        <label for="telefono">Número de teléfono</label>
                        <input required onkeypress="return permite(event, 'num')" type="text" id="telefono" name="telefono" class="entrada" placeholder="Ej. 0412-1234567" maxlength="11" minlength="11">
                    </div>
                    <div class="grupo-campo">
                        <label for="telefono2">Número de Teléfono secundario</label>
                        <input required onkeypress="return permite(event, 'num')" type="text" id="telefono2" name="telefono2" class="entrada" placeholder="Ej. 0412-1234567" maxlength="11" minlength="11">
                    </div>
                    <div class="grupo-campo">
                        <label for="gmail">Correo Electrónico</label>
                        <input required type="email" id="gmail" name="gmail" class="entrada" placeholder="Ej. libro@gmail.com">
                    </div>
                    <div class="grupo-campo">
                        <label for="profesion">Profesión</label>
                        <input required onkeypress="return permite(event, 'car')" type="text" id="profesion" name="profesion" class="entrada" placeholder="Ej. Docente">
                    </div>
                    <div class="grupo-campo">
                        <label for="exp">Fecha Exp.</label>
                        <input type="date" id="exp" name="exp" class="entrada" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>
                    <div class="grupo-campo">
                        <label for="venc">Fecha Venc.</label>
                        <input type="date" id="venc" name="venc" class="entrada" required readonly>
                    </div>
                    <div class="grupo-campo">
                        <label for="comunidad">Comunidad</label>
                        <select class="entrada" id="ubicacion" name="ubicacion">
                            <option value="">Seleccione...</option>
                            <option value="aristidesb">Aristides Bastidas</option>
                            <option value="bolivar">Bolívar</option>
                            <option value="bruzual">Bruzual</option>
                            <option value="cocorote">Cocorote</option>
                            <option value="independencia">Independecia</option>
                            <option value="joseap">Jose Antonio Páez</option>
                            <option value="la_trinidad">La Trinidad</option>
                            <option value="manuelM">Manuel Monge</option>
                            <option value="nirgua">Nirgua</option>
                            <option value="peña">Peña</option>
                            <option value="san_felipe">San Felipe</option>
                            <option value="sucre">Sucre</option>
                            <option value="urachiche">Urachiche</option>
                            <option value="veroes">Veroes</option>
                        </select>
                    </div>
                    <div class="grupo-campo ancho-total">
                        <label for="direccion">Dirección exacta</label>
                        <textarea id="direccion" name="direccion" class="area-texto" placeholder="Notas adicionales..."></textarea>
                    </div>
                </div>
                <div class="fila-botones">
                    <button type="submit" class="btn btn-azul">
                        <i data-lucide="save"></i>
                        Guardar Registro
                    </button>
                    <button type="reset" id="btn-cancelar" class="btn btn-gris">
                        <i data-lucide="x"></i>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de Resultados -->
        <div class="contenedor-tabla">
            <div class="header-tabla">
                <div class="titulo-seccion">
                    <h3 style="margin:0;">Listado de Carnets Emitidos</h3>
                </div>
                <div class="busqueda-rapida">
                    <i data-lucide="search" size="18" style="color: var(--texto-suave);"></i>
                    <input type="text" id="input-filtro-tabla" placeholder="Buscar por nombre, cédula o nro. de carnet...">
                </div>
                <!-- Botón visible solo si NO es Almacenista (rol 3) -->
                    <?php if(Auth::user()->rol != 3): ?>
                        <button type="button" id="btn-toggle-form" class="btn btn-azul">
                            <i data-lucide="plus-circle"></i>
                            <span>Nuevo Registro</span>
                        </button>
                    <?php endif; ?>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Nro. Carnet</th>
                        <th>Titular</th>
                        <th>Cédula</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <?php if(isset($carnets) && count($carnets) > 0): ?>
                        <?php $__currentLoopData = $carnets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $carnet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($carnet->num_carnet); ?></td>
                                <td><?php echo e(isset($carnet->persona) ? $carnet->persona->nombre : 'N/A'); ?></td>
                                <td><?php echo e(isset($carnet->persona) ? $carnet->persona->cedula : 'N/A'); ?></td>
                                <td><?php echo e(isset($carnet->estado) ? $carnet->estado : 'N/A'); ?></td>
                                <td>
                                    <div style="display: flex; gap: 5px; align-items: center;">
                                        <button type="button" class="btn-icon" title="Ver" 
                                            onclick="verCarnet('<?php echo e($carnet->persona->nombre); ?>', '<?php echo e($carnet->persona->cedula); ?>', '<?php echo e($carnet->num_carnet); ?>', '<?php echo e($carnet->profesion); ?>', '<?php echo e($carnet->fecha_vencimiento); ?>', '<?php echo e($carnet->foto); ?>', '<?php echo e($carnet->id); ?>')">
                                            <i data-lucide="eye" style="color: #0984e3;"></i>
                                        </button>
                                        <?php if(in_array(Auth::user()->rol, [1, 2])): ?>
                                        <button type="button" class="btn-icon" title="Editar" onclick="abrirModalEditarCarnet(<?php echo e($carnet->id); ?>)">
                                            <i data-lucide="edit-3" style="color: #00b894;"></i>
                                        </button>
                                        <?php endif; ?>
                                        <?php if(in_array(Auth::user()->rol, [1, 2])): ?>
                                        <button type="button" class="btn-icon" title="Renovar" onclick="renovarCarnet(<?php echo e($carnet->id); ?>)">
                                            <i data-lucide="refresh-cw" style="color: #6c5ce7;"></i>
                                        </button>
                                        <?php endif; ?>
                                      <!-- Botón Bloquear / Desbloquear -->
                                        <?php if(isset($carnet->estado) && strtolower($carnet->estado) == 'bloqueado'): ?>
                                            <?php if(Auth::user()->rol == 1): ?>
                                                <button type="button" class="btn-icon" title="Desbloquear Carnet" onclick="desbloquearCarnet(<?php echo e($carnet->id); ?>)">
                                                    <i data-lucide="unlock" style="color: #28a745;"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <button type="button" class="btn-icon" title="Imprimir" onclick="window.open('/carnet/imprimir/<?php echo e($carnet->id); ?>', '_blank', 'width=450,height=600')">
                                            <i data-lucide="printer" style="color: #636e72;"></i>
                                        </button>
                                         <?php if(in_array(Auth::user()->rol, [1, 2])): ?>
                                        <button type="button" class="btn-icon" title="Eliminar" onclick="eliminarCarnet(<?php echo e($carnet->id); ?>, '<?php echo e(isset($carnet->persona) ? $carnet->persona->nombre : 'esta persona'); ?>', <?php echo e((isset($carnet->persona) && $carnet->persona->prestamos_count > 0) ? 'true' : 'false'); ?>)">
                                            <i data-lucide="trash-2" style="color: #d63031;"></i>
                                        </button>
                                         <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align: center;">No hay registros disponibles.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="paginacion-container">
                <div class="info-paginacion" id="info-paginacion">Registros</div>
                <div class="botones-paginacion">
                    <button class="btn-pag" id="btn-prev" disabled><i data-lucide="chevron-left" size="16"></i> Anterior</button>
                    <div id="numeros-paginas" style="display: flex; gap: 5px;"></div>
                    <button class="btn-pag" id="btn-next">Siguiente <i data-lucide="chevron-right" size="16"></i></button>
                </div>
            </div>
        </div>
    </main>
    <?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>

<!-- Modal de Visualización -->
<div id="modal-ver-carnet" class="modal-personalizado" style="display: none;">
    <div class="modal-contenido">
        <div class="modal-header">
            <h2>Detalles del Carnet</h2>
            <button type="button" class="btn-cerrar" onclick="cerrarModalVer()" style="border:none; background:none; cursor:pointer;">
                <i data-lucide="x-circle" style="color:var(--error);"></i>
            </button>
        </div>
        <div class="modal-body carnet-layout">
            <div class="col-foto">
                <img id="view-foto" src="<?php echo e(asset('img/user-default.png')); ?>" alt="Foto del titular">
            </div>
            <div class="col-info">
                <p><strong>Nombre:</strong> <span id="view-nombre"></span></p>
                <p><strong>Cédula:</strong> <span id="view-cedula"></span></p>
                <p><strong>Nro. Carnet:</strong> <span id="view-num"></span></p>
                <p><strong>Profesión:</strong> <span id="view-profesion"></span></p>
                <p><strong>Vence el:</strong> <span id="view-venc"></span></p>
                <p><strong>Estado:</strong> <span id="view-estado" class="estado-badge"></span></p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-icon" title="Imprimir" id="btn-imprimir-modal">
                <i data-lucide="printer" style="color: #636e72;"></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal de Edición de Carnet -->
<div id="modal-editar-carnet" class="contenedor-form" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); z-index:1000; width:90%; max-width:700px; max-height:90vh; overflow-y:auto; background:white; border-radius:16px; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
    <div class="titulo-form">
        <h2>Editar Carnet</h2>
        <button type="button" class="btn-cerrar" onclick="cerrarModalEdicion()">
            <i data-lucide="x-circle"></i>
        </button>
    </div>    
    <form id="form-modal-editar-carnet" method="POST" enctype="multipart/form-data" style="padding:0 20px 20px 20px;">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" name="_method" value="PUT">
        <div class="rejilla-form">
            <div class="grupo-campo">
                <label>Cédula</label>
                <input type="text" name="id-card" class="entrada" readonly>
            </div>
            <div class="grupo-campo">
                <label>Número de Carnet</label>
                <input type="text" name="num-carnet" class="entrada" readonly>
            </div>
            <div class="grupo-campo">
                <label>Nombre y Apellido</label>
                <input type="text" name="fullname" class="entrada" readonly>
            </div>
            <div class="grupo-campo">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="entrada" required>
            </div>
            <div class="grupo-campo">
                <label>Teléfono 2</label>
                <input type="text" name="telefono2" class="entrada">
            </div>
            <div class="grupo-campo">
                <label>Correo Electrónico</label>
                <input type="email" name="gmail" class="entrada" required>
            </div>
            <div class="grupo-campo">
                <label>Profesión</label>
                <input type="text" name="profesion" class="entrada" required>
            </div>
            <div class="grupo-campo">
                <label>Fecha Emisión</label>
                <input type="date" name="exp" class="entrada">
            </div>
            <div class="grupo-campo">
                <label>Fecha Vencimiento</label>
                <input type="date" name="venc" class="entrada">
            </div>
            <div class="grupo-campo">
                <label>Comunidad</label>
                <select name="ubicacion" class="entrada">
                    <option value="">Seleccione...</option>
                    <option value="aristidesb">Aristides Bastidas</option>
                    <option value="bolivar">Bolívar</option>
                    <option value="bruzual">Bruzual</option>
                    <option value="cocorote">Cocorote</option>
                    <option value="independencia">Independecia</option>
                    <option value="joseap">Jose Antonio Páez</option>
                    <option value="la_trinidad">La Trinidad</option>
                    <option value="manuelM">Manuel Monge</option>
                    <option value="nirgua">Nirgua</option>
                    <option value="peña">Peña</option>
                    <option value="san_felipe">San Felipe</option>
                    <option value="sucre">Sucre</option>
                    <option value="urachiche">Urachiche</option>
                    <option value="veroes">Veroes</option>
                </select>
            </div>
            <div class="grupo-campo ancho-total">
                <label>Dirección</label>
                <textarea name="direccion" class="area-texto" rows="2"></textarea>
            </div>
            <div class="grupo-campo ancho-total">
                <label style="font-weight: 600; display: block; margin-bottom: 6px;">Fotografía del Titular</label>
                
                <div id="drop-zone-edit-foto" style="border: 2px dashed #3498db; border-radius: 12px; padding: 16px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.25s ease;">
                    <input type="file" name="foto" id="edit-foto-input" accept="image/*" style="display: none;" onchange="manejarSeleccionEditFoto(this.files)">
                    
                    <div style="display: flex; align-items: center; justify-content: center; gap: 16px;">
                        <img id="preview-editar" src="<?php echo e(asset('img/user-default.png')); ?>" alt="Vista previa" style="width: 85px; height: 105px; object-fit: cover; border-radius: 8px; border: 2px solid #3498db; box-shadow: 0 3px 8px rgba(0,0,0,0.1);">
                        
                        <div style="text-align: left;">
                            <p style="margin: 0; font-weight: 600; color: var(--texto-oscuro); font-size: 0.9rem;">
                                Haz clic o arrastra para cambiar la foto
                            </p>
                            <small id="edit-foto-info" style="color: var(--texto-suave); display: block; margin-top: 3px; font-size: 0.8rem;">
                                Formato JPG, PNG o WEBP (máx. 5MB)
                            </small>
                            <span class="btn btn-gris" style="margin-top: 8px; padding: 4px 10px; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="camera" size="14"></i> Seleccionar nueva imagen
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="fila-botones">
            <button type="button" class="btn btn-gris" onclick="cerrarModalEdicion()">
                <i data-lucide="x"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-azul">
                <i data-lucide="save"></i> Actualizar
            </button>
        </div>
    </form>
</div>

<style>
    .modal-personalizado {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6); z-index: 10001; display: flex; align-items: center; justify-content: center;
    }
    .modal-contenido { background: white; padding: 20px; border-radius: 12px; width: 90%; max-width: 500px; }
    .modal-header { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    .carnet-layout { display: flex; gap: 20px; padding: 20px 0; }
    .col-foto img { width: 120px; height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid var(--primario); }
    .col-info p { margin-bottom: 8px; font-size: 14px; }
    .btn-cerrar-modal { background: none; border: none; cursor: pointer; color: var(--error); }
</style>

<script src="<?php echo e(asset('js/plantilla.js')); ?>?v=<?php echo e(filemtime(public_path('js/plantilla.js'))); ?>"></script>
<script src="<?php echo e(asset('js/paginacion.js')); ?>"></script>
<script src="<?php echo e(asset('js/validaciones.js')); ?>"></script>
<script src="<?php echo e(asset('js/mostrar_ocultar_formularios.js')); ?>"></script>

<script>
    lucide.createIcons();

    // Evento para el botón de desbloquear el campo Nombre y Apellido en el formulario
    document.getElementById('btn-desbloquear').addEventListener('click', function() {
        var input = document.getElementById('fullname');
        if (input.readOnly) {
            input.readOnly = false;
            this.innerHTML = '<i data-lucide="unlock"></i>';
            this.classList.replace('btn-gris', 'btn-azul');
            this.title = 'Bloquear campo';
            input.focus();
        } else {
            input.readOnly = true;
            this.innerHTML = '<i data-lucide="lock"></i>';
            this.classList.replace('btn-azul', 'btn-gris');
            this.title = 'Desbloquear para editar nombre';
        }
        lucide.createIcons();
    });

    // Función para Bloquear / Desbloquear un carnet desde la tabla
    function cambiarEstadoCarnet(id, estadoActual) {
        var esBloqueado = (estadoActual === 'bloqueado');
        var nuevoEstado = esBloqueado ? 'activo' : 'bloqueado';
        var accionTexto = esBloqueado ? 'desbloquear' : 'bloquear';

        Swal.fire({
            title: '¿Deseas ' + accionTexto + ' este carnet?',
            text: 'El carnet pasará a estar ' + nuevoEstado + '.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: esBloqueado ? '#28a745' : '#d63031',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, ' + accionTexto,
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(function(result) {
            if (result.isConfirmed) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/carnets/' + id + '/cambiar-estado';

                var token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(token);

                var estadoInput = document.createElement('input');
                estadoInput.type = 'hidden';
                estadoInput.name = 'estado';
                estadoInput.value = nuevoEstado;
                form.appendChild(estadoInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function renovarCarnet(id) {
        Swal.fire({
            title: '¿Renovar carnet?',
            text: "Se actualizarán SOLO las fechas de emisión y vencimiento (+5 años).",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, renovar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(function(result) {
            if (result.isConfirmed) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/carnets/' + id + '/renovar';
                var token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(token);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
        function desbloquearCarnet(id) {
        Swal.fire({
            title: '¿Desbloquear carnet?',
            text: 'El carnet volverá a estar activo en el sistema.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, desbloquear',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(function(result) {
            if (result.isConfirmed) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/carnets/' + id + '/desbloquear'; // Asegúrate de que esta ruta coincida con tu web.php

                var token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(token);

                var estadoInput = document.createElement('input');
                estadoInput.type = 'hidden';
                estadoInput.name = 'estado';
                estadoInput.value = 'activo';
                form.appendChild(estadoInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function eliminarCarnet(id, nombre, tienePrestamosPendientes) {
        if (tienePrestamosPendientes) {
            Swal.fire({
                title: 'No se puede eliminar',
                text: nombre + ' tiene préstamos pendientes por devolver. Debe registrarse la devolución antes de eliminar el carnet.',
                icon: 'error',
                confirmButtonColor: '#0984e3',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se eliminará permanentemente de la Biblioteca Dr. Félix Pifano",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar',
            reverseButtons: true
        }).then(function(result) {
            if (result.isConfirmed) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/carnets/eliminar/' + id;
                var token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(token);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function verCarnet(nombre, cedula, num, profesion, venc, foto, id) { 
        document.getElementById('view-nombre').innerText = nombre;
        document.getElementById('view-cedula').innerText = cedula;
        document.getElementById('view-num').innerText = num;
        document.getElementById('view-profesion').innerText = profesion;
        document.getElementById('view-venc').innerText = venc;
        document.getElementById('view-estado').innerText = 'ACTIVO';
        
        const btnImprimir = document.getElementById('btn-imprimir-modal');
        btnImprimir.onclick = function() {
            window.open('/carnet/imprimir/' + id, '_blank', 'width=450,height=600');
        };

        var viewFoto = document.getElementById('view-foto');
        if (foto && foto != '') {
            viewFoto.src = '/img/carnets/' + foto;
        } else {
            viewFoto.src = '<?php echo e(asset('img/user-default.png')); ?>';
        }
        document.getElementById('modal-ver-carnet').style.display = 'flex';
    }

    function cerrarModalVer() {
        document.getElementById('modal-ver-carnet').style.display = 'none';
    }

    function abrirModalEditarCarnet(id) {
        fetch('/carnets/' + id + '/datos')
            .then(function(response) { return response.json(); })
            .then(function(data) {
                Swal.close();
                if (data.success) {
                    var carnet = data.carnet;
                    var persona = carnet.persona;
                    var form = document.getElementById('form-modal-editar-carnet');
                    form.action = '/carnets/' + id;
                    
                    form.querySelector('[name="id-card"]').value = persona.cedula;
                    form.querySelector('[name="num-carnet"]').value = carnet.num_carnet;
                    form.querySelector('[name="fullname"]').value = persona.nombre;
                    form.querySelector('[name="telefono"]').value = persona.telefono || '';
                    form.querySelector('[name="telefono2"]').value = persona.telefono2 || '';
                    form.querySelector('[name="gmail"]').value = persona.correo || '';
                    form.querySelector('[name="profesion"]').value = carnet.profesion;
                    form.querySelector('[name="exp"]').value = carnet.fecha_emision;
                    form.querySelector('[name="venc"]').value = carnet.fecha_vencimiento;
                    form.querySelector('[name="direccion"]').value = persona.direccion || '';
                    
                    var selectComunidad = form.querySelector('[name="ubicacion"]');
                    if (persona.comunidad) {
                        for(var i = 0; i < selectComunidad.options.length; i++) {
                            if(selectComunidad.options[i].value === persona.comunidad) {
                                selectComunidad.selectedIndex = i;
                                break;
                            }
                        }
                    }
                    
                    var previewImg = document.getElementById('preview-editar');
                    if (carnet.foto) {
                        previewImg.src = '/img/carnets/' + carnet.foto;
                    } else {
                        previewImg.src = '<?php echo e(asset('img/user-default.png')); ?>';
                    }
                    
                    document.getElementById('modal-editar-carnet').style.display = 'block';
                    var overlay = document.getElementById('modal-overlay');
                    if (overlay) {
                        overlay.style.display = 'block';
                    }
                    lucide.createIcons();
                }
            })
            .catch(function(error) {
                Swal.close();
                Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
            });
    }

    function cerrarModalEdicion() {
        document.getElementById('modal-editar-carnet').style.display = 'none';
        var overlay = document.getElementById('modal-overlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Drag & Drop para Foto en Formulario de Registro
        var dropZoneFoto = document.getElementById('drop-zone-foto');
        var inputFoto = document.getElementById('foto');
        if (dropZoneFoto && inputFoto) {
            dropZoneFoto.addEventListener('click', function(e) {
                if (e.target.closest('button')) return;
                inputFoto.click();
            });
            ['dragenter', 'dragover'].forEach(function(ev) {
                dropZoneFoto.addEventListener(ev, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZoneFoto.style.borderColor = '#27ae60';
                    dropZoneFoto.style.background = '#f0fdf4';
                }, false);
            });
            ['dragleave', 'drop'].forEach(function(ev) {
                dropZoneFoto.addEventListener(ev, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZoneFoto.style.borderColor = '#3498db';
                    dropZoneFoto.style.background = '#f8fafc';
                }, false);
            });
            dropZoneFoto.addEventListener('drop', function(e) {
                var dt = e.dataTransfer;
                if (dt && dt.files && dt.files.length > 0) {
                    inputFoto.files = dt.files;
                    manejarSeleccionFoto(dt.files);
                }
            }, false);
        }

        // Drag & Drop para Foto en Modal de Edición
        var dropZoneEditFoto = document.getElementById('drop-zone-edit-foto');
        var inputEditFoto = document.getElementById('edit-foto-input');
        if (dropZoneEditFoto && inputEditFoto) {
            dropZoneEditFoto.addEventListener('click', function(e) {
                if (e.target.closest('button')) return;
                inputEditFoto.click();
            });
            ['dragenter', 'dragover'].forEach(function(ev) {
                dropZoneEditFoto.addEventListener(ev, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZoneEditFoto.style.borderColor = '#27ae60';
                    dropZoneEditFoto.style.background = '#f0fdf4';
                }, false);
            });
            ['dragleave', 'drop'].forEach(function(ev) {
                dropZoneEditFoto.addEventListener(ev, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZoneEditFoto.style.borderColor = '#3498db';
                    dropZoneEditFoto.style.background = '#f8fafc';
                }, false);
            });
            dropZoneEditFoto.addEventListener('drop', function(e) {
                var dt = e.dataTransfer;
                if (dt && dt.files && dt.files.length > 0) {
                    inputEditFoto.files = dt.files;
                    manejarSeleccionEditFoto(dt.files);
                }
            }, false);
        }

        var formEditar = document.getElementById('form-modal-editar-carnet');
        if (formEditar) {
            formEditar.addEventListener('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = this.action.split('/').pop();
                
                Swal.fire({ title: 'Actualizando...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                
                fetch('/carnets/' + id, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success) {
                        Swal.fire('¡Éxito!', data.message, 'success');
                        cerrarModalEdicion();
                        setTimeout(function() { location.reload(); }, 1500);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(function() { Swal.fire('Error', 'Ocurrió un error al actualizar', 'error'); });
            });
        }
    });

    document.getElementById('id-card').addEventListener('blur', function() {
        let cedula = this.value;
        if (cedula.length > 4) { 
            fetch(`/buscar-persona/${cedula}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'encontrado') {
                        document.getElementById('fullname').value = data.datos.nombre;
                        document.getElementById('ubicacion').value = data.datos.comunidad;
                        document.getElementById('direccion').value = data.datos.direccion;
                        document.getElementById('telefono').value = data.datos.telefono;
                        document.getElementById('telefono2').value = data.datos.telefono2;
                        document.getElementById('gmail').value = data.datos.correo;
                        
                        if (typeof notificarEncontrado === 'function') {
                            notificarEncontrado();
                        }
                    } else {
                        document.getElementById('fullname').value = '';
                        document.getElementById('ubicacion').value = '';
                        document.getElementById('direccion').value = '';
                        document.getElementById('telefono').value = '';
                        document.getElementById('telefono2').value = '';
                        document.getElementById('gmail').value = '';
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });

    // Calcular fecha de vencimiento automáticamente
    document.addEventListener('DOMContentLoaded', function() {
        var inputExp = document.getElementById('exp');
        var inputVenc = document.getElementById('venc');
        
        function calcularVencimiento() {
            if (inputExp && inputExp.value) {
                var fecha = new Date(inputExp.value);
                fecha.setFullYear(fecha.getFullYear() + 5);
                var fechaVenc = fecha.toISOString().split('T')[0];
                if (inputVenc) inputVenc.value = fechaVenc;
            }
        }
        
        if (inputExp) {
            inputExp.addEventListener('change', calcularVencimiento);
            calcularVencimiento();
        }
    });
</script>

<?php if(session('carnet_existente')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Carnet ya existente',
            html: '<div style="text-align:left;">' +
                      '<p><strong>Nombre:</strong> <?php echo e(session("carnet_existente")["nombre"]); ?></p>' +
                      '<p><strong>Carnet N°:</strong> <?php echo e(session("carnet_existente")["num_carnet"]); ?></p>' +
                      '<p>Esta persona ya tiene un carnet asignado.</p>' +
                      '<p>Debe renovarlo.</p>' +
                  '</div>',
            icon: 'warning',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            showCancelButton: false,
        });
    });
</script>
<?php endif; ?>
</body>
</html>