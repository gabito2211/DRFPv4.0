<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/inicio.css')); ?>">

    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 2000;
            backdrop-filter: blur(3px);
        }
        .modal-content {
            background: white;
            width: 90%;
            max-width: 650px;
            margin: 4% auto;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .badge-activo { background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 0.8rem; }
        .badge-devuelto { background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 0.8rem; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; margin-top: 5px; font-size: 0.9rem; }

        /* ===== DASHBOARD DE MÉTRICAS ===== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .metric-card {
            background: white;
            border-radius: 10px;
            padding: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 4px solid var(--primario);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        .metric-card.verde   { border-left-color: #059669; }
        .metric-card.rojo    { border-left-color: #dc2626; }
        .metric-card.amarillo{ border-left-color: #d97706; }
        .metric-card.morado  { border-left-color: #7c3aed; }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(37, 99, 235, 0.1);
            color: var(--primario);
            flex-shrink: 0;
        }
        .metric-card.verde    .metric-icon { background: rgba(5,150,105,0.1); color: #059669; }
        .metric-card.rojo     .metric-icon { background: rgba(220,38,38,0.1); color: #dc2626; }
        .metric-card.amarillo .metric-icon { background: rgba(217,119,6,0.1); color: #d97706; }
        .metric-card.morado   .metric-icon { background: rgba(124,58,237,0.1); color: #7c3aed; }

        .metric-info { display: flex; flex-direction: column; }
        .metric-valor {
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--texto-oscuro);
            line-height: 1.1;
        }
        .metric-label {
            font-size: 0.78rem;
            color: var(--texto-suave);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-top: 2px;
        }
    </style>
</head>
<body>

<?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div id="main-wrapper">
    <main id="main-content">
        
        <div class="header-acciones">
            <div>
                <h1 style="font-size: 1.5rem; color: var(--texto-oscuro);">Bienvenido al Sistema</h1>
                <p style="color: var(--texto-suave);"> <?php if(Auth::check() && Auth::user()->sede): ?>
                    <?php echo e(Auth::user()->sede->biblioteca); ?>

                    <?php else: ?>
                        Sede Principal <br><small style="font-size: 0.75rem; color: var(--text-muted);">(Administración)</small>
                    <?php endif; ?>
                </p>
            </div>
            <div id="reloj-digital" style="font-weight: 600; color: var(--primario);"></div>
        </div>

        <?php echo $__env->make('layouts.mensajes', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <!-- ========== DASHBOARD DE MÉTRICAS ========== -->
        <div class="dashboard-grid">

            <div class="metric-card">
                <div class="metric-icon"><i data-lucide="book-open"></i></div>
                <div class="metric-info">
                    <span class="metric-valor"><?php echo e($totalLibros); ?></span>
                    <span class="metric-label">Libros Totales</span>
                </div>
            </div>

            <div class="metric-card verde">
                <div class="metric-icon"><i data-lucide="book-check"></i></div>
                <div class="metric-info">
                    <span class="metric-valor"><?php echo e($librosDisponibles); ?></span>
                    <span class="metric-label">Disponibles</span>
                </div>
            </div>

            <div class="metric-card rojo">
                <div class="metric-icon"><i data-lucide="book-x"></i></div>
                <div class="metric-info">
                    <span class="metric-valor"><?php echo e($librosPrestados); ?></span>
                    <span class="metric-label">Prestados</span>
                </div>
            </div>

            <div class="metric-card rojo">
                <div class="metric-icon"><i data-lucide="alert-triangle"></i></div>
                <div class="metric-info">
                    <span class="metric-valor"><?php echo e($prestamosVencidos); ?></span>
                    <span class="metric-label">Vencidos</span>
                </div>
            </div>

            <div class="metric-card amarillo">
                <div class="metric-icon"><i data-lucide="clock"></i></div>
                <div class="metric-info">
                    <span class="metric-valor"><?php echo e($prestamosPendientes); ?></span>
                    <span class="metric-label">Pendientes de Entrega</span>
                </div>
            </div>

        </div>
        <!-- ========== FIN DASHBOARD ========== -->

        <!-- Tabla Préstamos Internos -->
        <div class="contenedor-tabla" style="margin-top: 15px;">
            <table id="tabla-inventario">
                <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="titulo-seccion"><h3>Listado de Préstamos del Dia</h3></div>
            <?php if(in_array(Auth::user()->rol, [1, 2])): ?>
            <button class="btn btn-azul" onclick="abrirModalPrestamoInterno()">
                <i data-lucide="plus-circle"></i> Nuevo Registro
            </button>
            <?php endif; ?>
        </div>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Lector</th>
                        <th>Libro</th>
                        <th>Hora Préstamo</th>
                        <th>Hora Devolución</th>
                        <th>Estatus</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <?php $__empty_1 = true; $__currentLoopData = $prestamosInternos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e(date('d/m/Y', strtotime($pi->fecha))); ?></td>
                        <td>
                            <strong><?php echo e($pi->nombre_lector); ?></strong><br>
                            <small style="color:#6b7280;">V-<?php echo e($pi->cedula); ?></small>
                        </td>
                        <td>
                            <strong><?php echo e($pi->titulo_libro); ?></strong><br>
                            <small style="color:#6b7280;">Cota: <?php echo e($pi->cota_libro); ?></small>
                        </td>
                        <td><?php echo e(date('g:i A', strtotime($pi->hora_prestamo))); ?></td>
                        <td>
                            <?php echo e($pi->hora_devolucion ? date('g:i A', strtotime($pi->hora_devolucion)) : '--:--'); ?>

                        </td>
                        <td>
                            <?php if($pi->estado == 'activo'): ?>
                                <span class="badge-activo">En Sala</span>
                            <?php else: ?>
                                <span class="badge-devuelto">Devuelto</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if($pi->estado == 'activo'): ?>
                                <form action="<?php echo e(route('prestamo_interno.devolver', $pi->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo e(csrf_field()); ?>

                                    <?php echo e(method_field('PUT')); ?>

                                    <?php if(in_array(Auth::user()->rol, [1, 2])): ?>
                                    <button type="submit" class="btn-icon" style="color: var(--exito);" title="Marcar Devolución">
                                        <i data-lucide="check-circle" size="20"></i>
                                    </button>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                <button class="btn-icon" style="color: gray; opacity:0.5;" disabled title="Ya entregado">
                                    <i data-lucide="check" size="20"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 25px; color: #6b7280;">
                            No se han registrado préstamos internos en el día de hoy.
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

<!-- MODAL REGISTRAR PRÉSTAMO INTERNO -->
<div id="modal-prestamo-interno" class="modal-overlay">
    <div class="modal-content">
        <div class="titulo-form" style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Registrar Préstamo Interno (Sala)</h2>
            <button type="button" onclick="cerrarModalPrestamoInterno()" class="btn-icon" style="color:var(--error);">
                <i data-lucide="x-circle"></i>
            </button>
        </div>

        <form action="<?php echo e(route('prestamo_interno.store')); ?>" method="POST" id="form-prestamo-interno">
            <?php echo e(csrf_field()); ?>

            
            <div class="rejilla-form" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                
                <!-- Cédula Lector -->
                <div class="grupo-campo">
                    <label>Cédula del Lector</label>
                    <div style="display: flex; gap: 5px;">
                        <input type="text" id="pi_cedula" name="cedula" class="entrada" placeholder="Ej: 12345678" required>
                        <button type="button" class="btn btn-gris" onclick="buscarLectorPorCedula()">
                            <i data-lucide="search" size="18"></i>
                        </button>
                    </div>
                </div>

                <!-- Nombre Lector (Cargado / Visualizado) -->
                <div class="grupo-campo">
                    <label>Nombre del Lector</label>
                    <input type="text" id="pi_nombre" name="nombre_lector" class="entrada" placeholder="Se completará automáticamente..." readonly required style="background: #f1f5f9;">
                </div>

                <!-- Cota Libro -->
                <div class="grupo-campo">
                    <label>Cota del Libro</label>
                    <div style="display: flex; gap: 5px;">
                        <input type="text" id="pi_cota" name="cota_libro" class="entrada" placeholder="Ej: 000.1" required>
                        <button type="button" class="btn btn-gris" onclick="buscarLibroPorCota()">
                            <i data-lucide="search" size="18"></i>
                        </button>
                    </div>
                </div>

                <!-- Título Libro -->
                <div class="grupo-campo">
                    <label>Título del Libro</label>
                    <input type="text" id="pi_titulo" name="titulo_libro" class="entrada" placeholder="Se completará automáticamente..." readonly required style="background: #f1f5f9;">
                </div>

                <!-- Fecha y Hora -->
                <div class="grupo-campo">
                    <label>Fecha de Préstamo</label>
                    <input type="text" class="entrada" value="<?php echo e(date('d/m/Y')); ?>" readonly style="background: #f1f5f9;">
                </div>

                <div class="grupo-campo">
                    <label>Hora de Salida</label>
                    <input type="text" class="entrada" value="Hora Actual" readonly style="background: #f1f5f9;">
                </div>

            </div>

            <div id="pi-mensaje-error" class="info-box" style="display:none; color:#dc2626; border-color:#fca5a5; background:#fef2f2; margin-top:15px;"></div>

            <div class="fila-botones" style="margin-top: 20px; display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="cerrarModalPrestamoInterno()" class="btn btn-gris">Cancelar</button>
                <button type="submit" class="btn btn-azul" id="btn-guardar-pi" disabled><i data-lucide="save"></i> Guardar Registro</button>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo e(asset('js/plantilla.js')); ?>"></script>
<script>
    lucide.createIcons();

    function actualizarReloj() {
        const ahora = new Date();
        const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        document.getElementById('reloj-digital').textContent = ahora.toLocaleDateString('es-ES', opciones);
    }
    setInterval(actualizarReloj, 1000);
    actualizarReloj();

    // Lógica Modales Préstamo Interno
    function abrirModalPrestamoInterno() {
        document.getElementById('modal-prestamo-interno').style.display = 'block';
    }

    function cerrarModalPrestamoInterno() {
        document.getElementById('modal-prestamo-interno').style.display = 'none';
        document.getElementById('form-prestamo-interno').reset();
        document.getElementById('pi-mensaje-error').style.display = 'none';
        document.getElementById('btn-guardar-pi').disabled = true;
    }

    // Búsqueda AJAX de Lector por Cédula
    let timeoutCedula = null;
    document.getElementById('pi_cedula').addEventListener('keyup', function() {
        clearTimeout(timeoutCedula);
        timeoutCedula = setTimeout(buscarLectorPorCedula, 400);
    });

    function buscarLectorPorCedula() {
        const cedula = document.getElementById('pi_cedula').value.trim();
        const mensajeError = document.getElementById('pi-mensaje-error');
        
        if (cedula.length === 0) return;

        fetch('/buscar-lector/' + encodeURIComponent(cedula))
            .then(res => res.json())
            .then(data => {
                if (data.encontrado) {
                    document.getElementById('pi_nombre').value = data.nombre;
                    mensajeError.style.display = 'none';
                    verificarFormularioCompleto();
                } else {
                    document.getElementById('pi_nombre').value = '';
                    mensajeError.style.display = 'block';
                    mensajeError.textContent = '⚠️ ' + data.mensaje;
                    document.getElementById('btn-guardar-pi').disabled = true;
                }
            })
            .catch(err => console.error(err));
    }

    // Búsqueda AJAX de Libro por Cota
    let timeoutCota = null;
    document.getElementById('pi_cota').addEventListener('keyup', function() {
        clearTimeout(timeoutCota);
        timeoutCota = setTimeout(buscarLibroPorCota, 400);
    });

    function buscarLibroPorCota() {
        const cota = document.getElementById('pi_cota').value.trim();
        const mensajeError = document.getElementById('pi-mensaje-error');

        if (cota.length === 0) return;

        fetch('/buscar-libro/' + encodeURIComponent(cota))
            .then(res => res.json())
            .then(data => {
                if (data.encontrado) {
                    if (!data.disponible) {
                        document.getElementById('pi_titulo').value = data.titulo;
                        mensajeError.style.display = 'block';
                        mensajeError.textContent = '⚠️ ' + data.mensaje;
                        document.getElementById('btn-guardar-pi').disabled = true;
                    } else {
                        document.getElementById('pi_titulo').value = data.titulo;
                        mensajeError.style.display = 'none';
                        verificarFormularioCompleto();
                    }
                } else {
                    document.getElementById('pi_titulo').value = '';
                    mensajeError.style.display = 'block';
                    mensajeError.textContent = '⚠️ ' + data.mensaje;
                    document.getElementById('btn-guardar-pi').disabled = true;
                }
            })
            .catch(err => console.error(err));
    }

    // Habilitar botón de guardar si ambos datos están validados
    function verificarFormularioCompleto() {
        const nombre = document.getElementById('pi_nombre').value;
        const titulo = document.getElementById('pi_titulo').value;
        if (nombre.length > 0 && titulo.length > 0) {
            document.getElementById('btn-guardar-pi').disabled = false;
        }
    }
</script>

<script src="<?php echo e(asset('js/paginacion.js')); ?>"></script>

</body>
</html>