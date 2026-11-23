<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos - Biblioteca Dr. Félix Pifano</title>
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
        max-width: 800px;
        margin: 2% auto;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        max-height: 90vh;
        overflow-y: auto;
        padding: 20px;
    }

    .textarea-condiciones {
        width: 100%;
        min-height: 60px;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-family: inherit;
        font-size: 0.95rem;
        resize: vertical;
        transition: border-color 0.2s;
    }
    .textarea-condiciones:focus {
        outline: none;
        border-color: var(--primario);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .select-plazo {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-family: inherit;
        font-size: 0.95rem;
        background: white;
        cursor: pointer;
    }
    .select-plazo:focus {
        outline: none;
        border-color: var(--primario);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .ver-detalle-texto {
        background: #f8fafc;
        padding: 10px 14px;
        border-radius: 6px;
        border-left: 4px solid var(--primario);
        white-space: pre-wrap;
        word-break: break-word;
        margin: 4px 0;
    }
    
    .modal-content .rejilla-form {
        grid-template-columns: 1fr 1fr;
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
    .badge-pendiente {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-entregado {
        background: #d1fae5;
        color: #065f46;
    }
    .badge-vencido {
        background: #fecaca;
        color: #991b1b;
    }

    .btn-entregar:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-azul:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #6c757d;
    }

    .mensaje-disponibilidad {
        margin-top: 5px;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 6px 10px;
        border-radius: 4px;
        background: #f8f9fa;
    }
    .mensaje-disponible {
        color: #28a745;
        background: #d4edda;
    }
    .mensaje-no-disponible {
        color: #dc3545;
        background: #f8d7da;
    }
    .mensaje-error {
        color: #856404;
        background: #fff3cd;
    }

    .mensaje-lector {
        margin-top: 5px;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 6px 10px;
        border-radius: 4px;
        background: #f8f9fa;
    }
    .mensaje-lector-valido {
        color: #28a745;
        background: #d4edda;
    }
    .mensaje-lector-invalido {
        color: #dc3545;
        background: #f8d7da;
    }
    .mensaje-lector-error {
        color: #856404;
        background: #fff3cd;
    }
    .btn-verde {
    background-color: #059669;
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
.btn-verde:hover {
    background-color: #047857;
    color: white;
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
                    <h1>Gestión de Préstamos</h1>
                    <p style="color: var(--texto-suave);">Control de prestamo y devulucion de libros.</p>
                </div> 
        </div>
        
        <!-- Formulario de Registro -->
        <div class="contenedor-form" id="form-container">
            <div class="titulo-form">
                <h2>Registrar Nuevo Préstamo</h2>
                <button type="button" id="btn-cerrar-form" class="btn-icon" style="color:var(--error);">
                    <i data-lucide="x-circle"></i>
                </button>
            </div>
            <form action="<?php echo e(route('prestamo.store')); ?>" method="POST" id="form-prestamo">
                <?php echo e(csrf_field()); ?>

                <div class="rejilla-form">
                    <div class="grupo-campo">
                        <label for="lector_id">Cédula o N° de Carnet del Lector</label>
                        <div style="display: flex; gap: 5px;">
                            <input type="text" name="lector_id" class="entrada" id="lector_id" placeholder="Ej. 12345678 o LIB-2026-003" required>
                            <button type="button" class="btn btn-gris" title="Verificar Carnet" onclick="verificarCarnet(document.getElementById('lector_id').value)">
                                <i data-lucide="search" size="18"></i>
                            </button>
                        </div>
                        <div id="mensaje-lector" class="mensaje-lector"></div>
                        <small style="color: #6b7280; display: block; margin-top: 4px;">Puede buscar por cédula o por número de carnet (debe estar activo y vigente)</small>
                    </div>
                    <div class="grupo-campo">
                        <label for="libro_id">Código del Libro / ISBN</label>
                        <input type="text" name="libro_id" class="entrada" id="libro_id" placeholder="Ej. 804.22" required>
                        <div id="mensaje-disponibilidad" class="mensaje-disponibilidad"></div>
                    </div>
                    <div class="grupo-campo">
                        <label for="fecha_salida">Fecha de Salida</label>
                        <input type="date" name="fecha_salida" class="entrada" id="fecha_salida" required>
                    </div>
                    <div class="grupo-campo">
                        <label for="plazo_prestamo">Plazo de Préstamo</label>
                        <select name="plazo_prestamo" id="plazo_prestamo" class="select-plazo" onchange="togglePlazoPersonalizado(this.value)">
                            <option value="15">15 días</option>
                            <option value="18">18 días</option>
                            <option value="21" selected>21 días</option>
                            <option value="otro">Otro (personalizado)</option>
                        </select>
                        <input type="number" id="plazo_personalizado" class="entrada" 
                               placeholder="Ingrese el número de días" min="1" max="365"
                               style="display:none; margin-top: 8px;">
                    </div>
                    <div class="grupo-campo">
                        <label for="fecha_devolucion">Fecha Estimada de Entrega</label>
                        <input type="date" name="fecha_devolucion" class="entrada" id="fecha_devolucion" readonly required>
                        <small style="color: #6b7280; display: block; margin-top: 4px;">Se calcula automáticamente según el plazo seleccionado</small>
                    </div>
                    <div class="grupo-campo grupo-campo-completo" style="grid-column: 1 / -1;">
                        <label for="condiciones_salida">Condiciones del libro al momento del préstamo</label>
                        <textarea name="condiciones_salida" id="condiciones_salida" class="textarea-condiciones" placeholder="Ej: Buen estado, algunas páginas subrayadas, portada con desgaste leve..."></textarea>
                    </div>
                </div>
                <div class="fila-botones">
                    <button type="submit" class="btn btn-azul" id="btn-guardar-prestamo"><i data-lucide="save"></i> Guardar Registro</button>
                    <button type="reset" class="btn btn-gris">Limpiar</button>
                </div>
            </form>
        </div>

        <div class="contenedor-tabla">
            <div class="header-tabla">
                <div class="titulo-seccion"><h3>Listado de Préstamos</h3></div>
                <form action="<?php echo e(url('/prestamo')); ?>" method="GET" class="busqueda-rapida">
                    <i data-lucide="search"></i>
                    <input type="text" name="search" value="<?php echo e(isset($buscar) ? $buscar : ''); ?>" placeholder="Buscar...">
                </form>

                
                    <a href="<?php echo e(route('prestamo.reportes', ['tipo' => 'general', 'search' => isset($buscar) ? $buscar : ''])); ?>"
                    class="btn btn-verde"
                    title="Ver reportes de préstamos">
                        <i data-lucide="file-bar-chart"></i> Reportes
                    </a>
                <!-- Botón visible únicamente para Administradores (1) y Recepcionistas (2) -->
                    <?php if(Auth::user()->rol != 3): ?>
                        <button type="button" id="btn-toggle-form" class="btn btn-azul"><i data-lucide="plus-circle"></i> Nuevo Registro</button>
                    <?php endif; ?>
            </div>
            <table id="tabla-inventario">
                <thead>
                    <tr> 
                        <th>ID</th>
                        <th>Cedula del lector</th>
                        <th>Código Libro</th>
                        <th>Fecha Salida</th>
                        <th>Fecha Devolución</th>
                        <th>Condiciones Salida</th>
                        <th>Condiciones Regreso</th>
                        <th>Estatus</th>
                        <th class="th-acciones" style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <?php 
                    if(!isset($prestamos)) {
                        $prestamos = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
                    }
                    $hoy = \Carbon\Carbon::today();
                 ?>

                <tbody id="tabla-body">
                    <?php $__empty_1 = true; $__currentLoopData = $prestamos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php 
                        $esVencido = ($item->estatus == 'pendiente') && (\Carbon\Carbon::parse($item->fecha_devolucion)->lt($hoy));
                     ?>
                    <tr>
                        <td><?php echo e($item->id); ?></td>
                        <td><strong><?php echo e($item->lector_id); ?></strong></td>
                        <td><?php echo e($item->libro_id); ?></td>
                        <td><?php echo e($item->fecha_salida); ?></td>
                        <td><?php echo e($item->fecha_devolucion); ?></td>
                        <td style="max-width: 150px; font-size: 0.85rem;">
                            <?php echo e(\Illuminate\Support\Str::limit($item->condiciones_salida, 50) ?: '-'); ?>

                        </td>
                        <td style="max-width: 150px; font-size: 0.85rem;">
                            <?php echo e(\Illuminate\Support\Str::limit($item->condiciones_regreso, 50) ?: '-'); ?>

                        </td>
                        <td>
                            <?php if($esVencido): ?>
                                <span class="badge-estatus badge-vencido" title="Préstamo con fecha de entrega vencida">
                                    Vencido
                                </span>
                            <?php else: ?>
                                <span class="badge-estatus <?php echo e($item->estatus == 'pendiente' ? 'badge-pendiente' : 'badge-entregado'); ?>">
                                    <?php echo e($item->estatus); ?>

                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="td-acciones" style="text-align:center;">
                            <?php if(in_array(Auth::user()->rol, [1, 2])): ?>
                            <div class="tabla-acciones">
                                <?php if($item->estatus == 'pendiente'): ?>
                                    <button type="button" class="btn-icon" 
                                        onclick="abrirModalEditar(<?php echo e(json_encode($item)); ?>)"
                                        style="border:none; background:none; cursor:pointer; color:var(--primario);">
                                        <i data-lucide="pencil"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if($item->estatus == 'pendiente'): ?>
                                    <button type="button" class="btn-icon" 
                                        onclick="abrirModalEntregar(<?php echo e($item->id); ?>, '<?php echo e($item->fecha_devolucion); ?>')"
                                        style="border:none; background:none; cursor:pointer; color:var(--exito);"
                                        title="Registrar entrega">
                                        <i data-lucide="check-circle"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if($item->estatus == 'pendiente'): ?>
                                    <button type="button" class="btn-icon" 
                                        onclick="alert('No se puede eliminar un préstamo pendiente. Primero debe registrar la entrega del libro.')"
                                        style="color:var(--error); border:none; background:none; cursor:pointer; opacity:0.5;"
                                        title="No se puede eliminar préstamos pendientes">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                <?php else: ?>
                                    <form id="form-eliminar-prestamo-<?php echo e($item->id); ?>" action="<?php echo e(route('prestamo.destroy', $item->id)); ?>" method="POST" style="display:none;">
                                        <?php echo e(csrf_field()); ?>

                                        <?php echo e(method_field('DELETE')); ?>

                                    </form>
                                    <button type="button" onclick="confirmarEliminar('form-eliminar-prestamo-<?php echo e($item->id); ?>')" class="btn-icon" style="color:var(--error); border:none; background:none; cursor:pointer;" title="Eliminar préstamo">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px;">
                            <i data-lucide="inbox" size="48" style="margin-bottom: 10px; color: #9ca3af;"></i>
                            <p>No hay préstamos registrados</p>
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

<!-- MODAL DE EDICIÓN -->
<div id="modal-editar" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <div class="titulo-form">
            <h2>Editar Préstamo</h2>
            <button type="button" onclick="cerrarModalEditar()" class="btn-icon" style="color:var(--error);">
                <i data-lucide="x-circle"></i>
            </button>
        </div>
        
        <form id="form-editar" method="POST">
            <?php echo e(csrf_field()); ?>

            <?php echo e(method_field('PUT')); ?>

            
            <div class="rejilla-form">
                <div class="grupo-campo">
                    <label for="edit-lector_id">Cedula del Lector</label>
                    <div style="display: flex; gap: 5px;">
                        <input type="text" name="lector_id" class="entrada" id="edit-lector_id" placeholder="Ej. C-001234" required readonly>
                        <button type="button" class="btn btn-gris" title="Verificar Carnet" onclick="verificarCarnetEdicion(document.getElementById('edit-lector_id').value)">
                            <i data-lucide="search" size="18"></i>
                        </button>
                    </div>
                    <div id="edit-mensaje-lector" class="mensaje-lector"></div>
                </div>
                <div class="grupo-campo">
                    <label for="edit-estatus">Estatus del Préstamo</label>
                    <select name="estatus" id="edit-estatus" class="select-plazo" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="entregado">Entregado</option>
                    </select>
                </div>

                <div class="grupo-campo">
                    <label for="edit-libro_id">Código del Libro / ISBN</label>
                    <input type="text" name="libro_id" class="entrada" id="edit-libro_id" placeholder="Ej. 804.22" required readonly>
                    <div id="edit-mensaje-disponibilidad" class="mensaje-disponibilidad"></div>
                </div>
                <div class="grupo-campo">
                    <label for="edit-fecha_salida">Fecha de Salida</label>
                    <input type="date" name="fecha_salida" class="entrada" id="edit-fecha_salida" required>
                </div>
                <div class="grupo-campo">
                    <label for="edit-plazo_prestamo">Plazo de Préstamo</label>
                    <select name="plazo_prestamo" id="edit-plazo_prestamo" class="select-plazo" onchange="togglePlazoPersonalizadoEdicion(this.value)">
                        <option value="15">15 días</option>
                        <option value="18">18 días</option>
                        <option value="21">21 días</option>
                        <option value="otro">Otro (personalizado)</option>
                    </select>
                    <input type="number" id="edit-plazo_personalizado" class="entrada" 
                           placeholder="Ingrese el número de días" min="1" max="365"
                           style="display:none; margin-top: 8px;">
                </div>
                <div class="grupo-campo">
                    <label for="edit-fecha_devolucion">Fecha Estimada de Entrega</label>
                    <input type="date" name="fecha_devolucion" class="entrada" id="edit-fecha_devolucion" readonly required>
                    <small style="color: #6b7280; display: block; margin-top: 4px;">Se recalcula automáticamente según el plazo</small>
                </div>
                <div class="grupo-campo grupo-campo-completo" style="grid-column: 1 / -1;">
                    <label for="edit-condiciones_salida">Condiciones del libro al momento del préstamo</label>
                    <textarea name="condiciones_salida" id="edit-condiciones_salida" class="textarea-condiciones" placeholder="Ej: Buen estado, algunas páginas subrayadas..."></textarea>
                </div>
                <div class="grupo-campo grupo-campo-completo" style="grid-column: 1 / -1;">
                    <label for="edit-condiciones_regreso">Condiciones del libro al momento de la devolución</label>
                    <textarea name="condiciones_regreso" id="edit-condiciones_regreso" class="textarea-condiciones" placeholder="Ej: Excelente estado, sin daños visibles..."></textarea>
                </div>
            </div>

            <div class="fila-botones">
                <button type="submit" class="btn btn-azul"><i data-lucide="refresh-cw"></i> Actualizar Cambios</button>
                <button type="button" onclick="cerrarModalEditar()" class="btn btn-gris">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DE ENTREGA -->
<div id="modal-entregar" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 600px;">
        <div class="titulo-form">
            <h2>Registrar Entrega de Libro</h2>
            <button type="button" onclick="cerrarModalEntregar()" class="btn-icon" style="color:var(--error);">
                <i data-lucide="x-circle"></i>
            </button>
        </div>
        
        <form id="form-entregar" method="POST" onsubmit="return confirmarEntregaDano(event)">
            <?php echo e(csrf_field()); ?>

            
            <div id="alerta-mora-entregar" class="mensaje-disponibilidad mensaje-no-disponible" style="display:none; margin-bottom: 15px;">
                ⚠️ <strong>¡Atención! Este préstamo está fuera de lapso.</strong><br>
                Al confirmar la entrega, el sistema aplicará automáticamente una sanción de 30 días por días de retraso.
            </div>

            <div class="rejilla-form" style="grid-template-columns: 1fr;">
                <div class="grupo-campo grupo-campo-completo">
                    <label for="estado_libro"><strong>Estado del libro devuelto:</strong></label>
                    <select name="estado_libro" id="estado_libro" class="select-plazo" onchange="toggleDetalleDano(this.value)" required>
                        <option value="bueno" selected>🟢 Buen estado</option>
                        <option value="deteriorado">🔴 Deteriorado / Con daños</option>
                    </select>
                </div>

                <div class="grupo-campo grupo-campo-completo" id="bloque-detalle-dano" style="display: none;">
                    <label for="entregar-condiciones_regreso"><strong>Detalle del daño / deterioro:</strong></label>
                    <textarea name="condiciones_regreso" id="entregar-condiciones_regreso" class="textarea-condiciones" 
                              placeholder="Ej: Hojas rotas, manchado de líquido, portada desprendida..."></textarea>
                    
                    <div class="mensaje-disponibilidad mensaje-no-disponible" style="margin-top: 10px; background-color: #fee2e2; border: 1px solid #f87171; color: #991b1b; padding: 12px; border-radius: 6px;">
                        🚫 <strong>¡BLOQUEO INDEFINIDO!</strong><br>
                        Al registrar el libro como <strong>DETERIORADO</strong>, el carnet quedará suspendido de forma permanente. <strong>Solo un administrador podrá reactivarlo manualmente</strong> desde la vista de Carnets cuando se resuelva el problema.
                    </div>
                </div>
            </div>

            <div class="fila-botones" style="margin-top: 20px;">
                <button type="submit" class="btn btn-verde"><i data-lucide="check-circle"></i> Confirmar Entrega</button>
                <button type="button" onclick="cerrarModalEntregar()" class="btn btn-gris">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    let timeoutIdLector = null;
    let timeoutIdLibro = null;

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }

        const lectorInput = document.getElementById('lector_id');
        const libroInput = document.getElementById('libro_id');

        if (lectorInput) {
            lectorInput.addEventListener('input', function() {
                clearTimeout(timeoutIdLector);
                timeoutIdLector = setTimeout(() => { verificarCarnet(this.value); }, 500);
            });
            if (lectorInput.value) verificarCarnet(lectorInput.value);
        }

        if (libroInput) {
            libroInput.addEventListener('input', function() {
                clearTimeout(timeoutIdLibro);
                timeoutIdLibro = setTimeout(() => { verificarDisponibilidad(this.value); }, 500);
            });
            if (libroInput.value) verificarDisponibilidad(libroInput.value);
        }

        const fechaSalida = document.getElementById('fecha_salida');

        if (fechaSalida && !fechaSalida.value) {
            const hoy = new Date();
            const year = hoy.getFullYear();
            const month = String(hoy.getMonth() + 1).padStart(2, '0');
            const day = String(hoy.getDate()).padStart(2, '0');
            fechaSalida.value = `${year}-${month}-${day}`;
            actualizarFechaDevolucionRegistro();
        }
    });

    function toggleDetalleDano(valor) {
        const bloque = document.getElementById('bloque-detalle-dano');
        const textarea = document.getElementById('entregar-condiciones_regreso');
        if (valor === 'deteriorado') {
            if (bloque) bloque.style.display = 'block';
            if (textarea) textarea.required = true;
        } else {
            if (bloque) bloque.style.display = 'none';
            if (textarea) {
                textarea.required = false;
                textarea.value = '';
            }
        }
    }


    function togglePlazoPersonalizado(valor) {
        const inputPersonalizado = document.getElementById('plazo_personalizado');
        if (!inputPersonalizado) return;

        if (valor === 'otro') {
            inputPersonalizado.style.display = 'block';
            inputPersonalizado.required = true;
            inputPersonalizado.focus();
        } else {
            inputPersonalizado.style.display = 'none';
            inputPersonalizado.required = false;
            inputPersonalizado.value = '';
        }
        actualizarFechaDevolucionRegistro();
    }

    function togglePlazoPersonalizadoEdicion(valor) {
        const inputPersonalizado = document.getElementById('edit-plazo_personalizado');
        if (!inputPersonalizado) return;

        if (valor === 'otro') {
            inputPersonalizado.style.display = 'block';
            inputPersonalizado.required = true;
            inputPersonalizado.focus();
        } else {
            inputPersonalizado.style.display = 'none';
            inputPersonalizado.required = false;
            inputPersonalizado.value = '';
        }
        actualizarFechaDevolucionEdicion();
    }

    function abrirModalEntregar(id, fechaDevolucion) {
        const form = document.getElementById('form-entregar');
        if (form) form.action = `/prestamo/${id}/entregar`;
        
        const selectEstado = document.getElementById('estado_libro');
        if (selectEstado) {
            selectEstado.value = 'bueno';
            toggleDetalleDano('bueno');
        }

        const alertaMora = document.getElementById('alerta-mora-entregar');
        if (alertaMora) {
            if (fechaDevolucion) {
                let fechaLimpia = fechaDevolucion.toString().trim().split(' ')[0];
                let partes = fechaLimpia.split('-');
                if (partes.length === 3) {
                    let fDev = new Date(parseInt(partes[0]), parseInt(partes[1]) - 1, parseInt(partes[2]));
                    let hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);
                    alertaMora.style.display = (fDev < hoy) ? 'block' : 'none';
                } else {
                    alertaMora.style.display = 'none';
                }
            } else {
                alertaMora.style.display = 'none';
            }
        }

        document.getElementById('modal-entregar').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
    }

    function cerrarModalEntregar() {
        document.getElementById('modal-entregar').style.display = 'none';
        document.body.style.overflow = '';
    }

    function abrirModalEditar(item) {
        document.getElementById('edit-lector_id').value = item.lector_id || '';
        document.getElementById('edit-libro_id').value = item.libro_id || '';
        document.getElementById('edit-fecha_salida').value = item.fecha_salida || '';
        document.getElementById('edit-fecha_devolucion').value = item.fecha_devolucion || '';
        document.getElementById('edit-condiciones_salida').value = item.condiciones_salida || '';
        document.getElementById('edit-condiciones_regreso').value = item.condiciones_regreso || '';
        document.getElementById('edit-estatus').value = item.estatus;

        // Calcular el plazo a partir de las fechas para precargar el select
        const plazoSelect = document.getElementById('edit-plazo_prestamo');
        const plazoPersonalizado = document.getElementById('edit-plazo_personalizado');

        if (plazoSelect && plazoPersonalizado && item.fecha_salida && item.fecha_devolucion) {
            const fSalida = new Date(item.fecha_salida + 'T00:00:00');
            const fDevolucion = new Date(item.fecha_devolucion + 'T00:00:00');
            const diffDias = Math.round((fDevolucion - fSalida) / (1000 * 60 * 60 * 24));

            const opcionesEstandar = ['15', '18', '21'];
            if (opcionesEstandar.includes(String(diffDias))) {
                plazoSelect.value = String(diffDias);
                plazoPersonalizado.style.display = 'none';
                plazoPersonalizado.value = '';
                plazoPersonalizado.required = false;
            } else {
                plazoSelect.value = 'otro';
                plazoPersonalizado.style.display = 'block';
                plazoPersonalizado.value = diffDias;
                plazoPersonalizado.required = true;
            }
        }
        
        const form = document.getElementById('form-editar');
        if (form) form.action = `/prestamo/${item.id}`;
        
        document.getElementById('modal-editar').style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
    }

    function cerrarModalEditar() {
        document.getElementById('modal-editar').style.display = 'none';
        document.body.style.overflow = '';
    }

    window.verificarCarnet = async function(numCarnet) {
        const mensajeLector = document.getElementById('mensaje-lector');
        const btnGuardar = document.getElementById('btn-guardar-prestamo');
        if (!mensajeLector) return;
        
        if (!numCarnet || numCarnet.length < 3) {
            mensajeLector.innerHTML = '';
            mensajeLector.className = 'mensaje-lector';
            if (btnGuardar) btnGuardar.disabled = false;
            return;
        }

        try {
            const response = await fetch(`/prestamo/verificar-lector/${encodeURIComponent(numCarnet)}`);
            const data = await response.json();
            
            if (data.valido) {
                mensajeLector.innerHTML = `
                    ✅ ${data.mensaje}<br>
                    <small>👤 <strong>${data.nombre || 'Usuario'}</strong></small><br>
                    <small>📚 ${data.profesion || 'Sin profesión registrada'}</small><br>
                    <small>📅 Vence: ${data.fecha_vencimiento || 'N/A'}</small>
                `;
                mensajeLector.className = 'mensaje-lector mensaje-lector-valido';
                if (btnGuardar) btnGuardar.disabled = false;
            } else {
                let detalleSancion = data.hasta ? `<br><small>🚫 Detalle: <strong>${data.hasta}</strong></small>` : '';
                mensajeLector.innerHTML = `❌ ${data.mensaje}${detalleSancion}`;
                mensajeLector.className = 'mensaje-lector mensaje-lector-invalido';
                if (btnGuardar) btnGuardar.disabled = true;
            }
        } catch (error) {
            console.error('Error:', error);
            mensajeLector.innerHTML = '⚠️ Error al verificar el carnet.';
            mensajeLector.className = 'mensaje-lector mensaje-lector-error';
            if (btnGuardar) btnGuardar.disabled = false;
        }
    };

    window.verificarDisponibilidad = async function(cota) {
        const mensajeDiv = document.getElementById('mensaje-disponibilidad');
        const btnGuardar = document.getElementById('btn-guardar-prestamo');
        if (!mensajeDiv) return;
        
        if (!cota || cota.length < 2) {
            mensajeDiv.innerHTML = '';
            mensajeDiv.className = 'mensaje-disponibilidad';
            if (btnGuardar) btnGuardar.disabled = false;
            return;
        }

        try {
            const response = await fetch(`/inventario/verificar/${encodeURIComponent(cota)}`);
            const data = await response.json();

            // Estado físico del libro registrado en inventario (bueno/deteriorado),
            // se muestra siempre que el libro exista, disponible o no.
            let estadoHtml = '';
            if (data.estado_label) {
                const esDeteriorado = data.estado && data.estado.toLowerCase() === 'deteriorado';
                const claseEstado = esDeteriorado ? 'color:#d63031;' : 'color:#00b894;';
                estadoHtml = `<br><small style="${claseEstado}">📖 Estado actual en inventario: <strong>${data.estado_label}</strong></small>`;

                if (data.dias_prestamo) {
                    estadoHtml += `<br><small style="color:#0984e3;">⏱️ Límite de préstamo: <strong>${data.dias_prestamo} días</strong></small>`;
                }

                // Se pre-llena el campo de condiciones solo si el usuario aún no ha escrito nada,
                // para que el encargado sepa en qué estado sale el libro sin tener que consultarlo aparte.
                const condicionesInput = document.getElementById('condiciones_salida');
                if (condicionesInput && !condicionesInput.value.trim()) {
                    if (esDeteriorado) {
                        const detalleDano = data.observaciones || data.detalles_dano || data.condiciones || data.detalle;
                        condicionesInput.value = detalleDano 
                            ? detalleDano 
                            : 'Libro registrado como DETERIORADO en el inventario.';
                    } else {
                        condicionesInput.value = 'Buen estado.';
                    }
                }
            }
            
            if (data.disponible) {
                mensajeDiv.innerHTML = `✅ ${data.mensaje}${estadoHtml}`;
                mensajeDiv.className = 'mensaje-disponibilidad mensaje-disponible';
                if (btnGuardar) btnGuardar.disabled = false;

                if (data.dias_prestamo) {
                    ajustarPlazoPorLibro(data.dias_prestamo);
                }
            } else {
                mensajeDiv.innerHTML = `❌ ${data.mensaje}${estadoHtml}`;
                mensajeDiv.className = 'mensaje-disponibilidad mensaje-no-disponible';
                if (btnGuardar) btnGuardar.disabled = true;
            }
        } catch (error) {
            console.error('Error:', error);
            mensajeDiv.innerHTML = '⚠️ Error al verificar disponibilidad';
            mensajeDiv.className = 'mensaje-disponibilidad mensaje-error';
            if (btnGuardar) btnGuardar.disabled = false;
        }
    };

    // Ajustar plazo según límite del libro (usa "Otro" si no es estándar)
    function ajustarPlazoPorLibro(diasLimite) {
        const plazoSelect = document.getElementById('plazo_prestamo');
        const plazoPersonalizado = document.getElementById('plazo_personalizado');
        if (!plazoSelect) return;

        const opcionesEstandar = ['15', '18', '21'];
        if (opcionesEstandar.includes(String(diasLimite))) {
            plazoSelect.value = String(diasLimite);
            if (plazoPersonalizado) {
                plazoPersonalizado.style.display = 'none';
                plazoPersonalizado.value = '';
                plazoPersonalizado.required = false;
            }
        } else {
            plazoSelect.value = 'otro';
            if (plazoPersonalizado) {
                plazoPersonalizado.style.display = 'block';
                plazoPersonalizado.value = diasLimite;
                plazoPersonalizado.required = true;
            }
        }
        actualizarFechaDevolucionRegistro();
    }

    window.verificarCarnetEdicion = function(numCarnet) {
        const mensajeLector = document.getElementById('edit-mensaje-lector');
        if (!mensajeLector) return;
        
        if (!numCarnet || numCarnet.length < 3) {
            mensajeLector.innerHTML = '';
            mensajeLector.className = 'mensaje-lector';
            return;
        }

        fetch(`/prestamo/verificar-lector/${encodeURIComponent(numCarnet)}`)
            .then(response => response.json())
            .then(data => {
                if (data.valido) {
                    mensajeLector.innerHTML = `
                        ✅ ${data.mensaje}<br>
                        <small>👤 <strong>${data.nombre || 'Usuario'}</strong></small><br>
                        <small>📚 ${data.profesion || 'Sin profesión registrada'}</small><br>
                        <small>📅 Vence: ${data.fecha_vencimiento || 'N/A'}</small>
                    `;
                    mensajeLector.className = 'mensaje-lector mensaje-lector-valido';
                } else {
                    let detalleSancion = data.hasta ? `<br><small>🚫 Detalle: <strong>${data.hasta}</strong></small>` : '';
                    mensajeLector.innerHTML = `❌ ${data.mensaje}${detalleSancion}`;
                    mensajeLector.className = 'mensaje-lector mensaje-lector-invalido';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mensajeLector.innerHTML = '⚠️ Error al verificar el carnet';
                mensajeLector.className = 'mensaje-lector mensaje-lector-error';
            });
    };

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModalEditar();
            cerrarModalEntregar();
        }
    });

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                this.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    });
</script>

<script src="<?php echo e(asset('js/prestamo.js')); ?>"></script>  
<script src="<?php echo e(asset('js/plantilla.js')); ?>?v=<?php echo e(filemtime(public_path('js/plantilla.js'))); ?>"></script>
<script src="<?php echo e(asset('js/paginacion.js')); ?>"></script>
<script src="<?php echo e(asset('js/validaciones.js')); ?>"></script>
<script src="<?php echo e(asset('js/mostrar_ocultar_formularios.js')); ?>"></script>

</body> 
</html>