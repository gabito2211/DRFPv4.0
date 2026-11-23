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
    <link rel="stylesheet" href="<?php echo e(asset('css/registrar_visitas_modal.css')); ?>">
</head>
<body>

    <!-- Navegación lateral -->    
    <?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div id="main-wrapper">
        <main id="main-content">
            <div class="header-acciones" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; width: 100%; max-width: 1100px;">
                <div>
                    <h1>Registro de Visitas Guiadas</h1>
                    <p style="color: var(--texto-suave);">Control de grupos, instituciones y recorridos programados.</p>
                </div>
            </div>

            <?php echo $__env->make('layouts.mensajes', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <div style="max-width: 1100px; width: 100%; margin-bottom: 20px;">
                <button id="btn-toggle-cronograma" class="btn btn-gris" style="border: 1px solid var(--borde);">
                    <i data-lucide="calendar"></i> Ver Cronograma de Visitas
                </button>
            </div>

            <!-- Contenedor del Cronograma / Calendario -->
            <div id="cronograma-container" class="contenedor-tabla" style="display: none; max-width: 1100px; width: 100%; margin-bottom: 30px;">
                <div class="rejilla-form" style="grid-template-columns: 1fr 1fr; gap: 30px;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <button id="cal-prev" class="btn-icon"><i data-lucide="chevron-left"></i></button>
                            <h3 id="cal-mes-ano" style="margin: 0; font-size: 1.2rem;"></h3>
                            <button id="cal-next" class="btn-icon"><i data-lucide="chevron-right"></i></button>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; text-align: center; font-weight: 600; color: var(--texto-suave); font-size: 0.9rem; margin-bottom: 10px;">
                            <div>Do</div><div>Lu</div><div>Ma</div><div>Mi</div><div>Ju</div><div>Vi</div><div>Sá</div>
                        </div>
                        <div id="cal-dias" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px;"></div>
                    </div>

                    <div style="border-left: 2px solid var(--fondo-sitio); padding-left: 20px;">
                        <h3>Visitas para el <span id="detalles-fecha" style="color: var(--primario);">Seleccione un día</span></h3>
                        <div id="detalles-visitas-lista" style="margin-top: 15px; max-height: 250px; overflow-y: auto;">
                            <p style="color: var(--texto-suave); font-style: italic;">Haz clic en un día marcado en verde para ver el desglose.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Registro de Visita Guiada -->
            <div id="form-container" class="contenedor-form" style="display: none; max-width: 1100px; margin-bottom: 30px;">
                <div class="titulo-form" style="display: flex; justify-content: space-between;">
                    <h2>Nueva Visita Guiada</h2>
                    <button type="button" id="btn-cerrar-form" style="border:none; background:none; cursor:pointer; color:var(--error);">
                        <i data-lucide="x-circle"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('guiadas.store')); ?>" method="POST">
                    <?php echo e(csrf_field()); ?>

                    <div class="rejilla-form">
                        <div class="grupo-campo ancho-total">
                            <label for="institucion">Institución / Organización</label>
                            <input type="text" name="institucion" class="entrada" value="<?php echo e(old('institucion')); ?>" required>
                        </div>
                        <div class="grupo-campo">
                            <label for="responsable">Responsable del Grupo</label>
                            <input type="text" name="responsable" class="entrada" value="<?php echo e(old('responsable')); ?>" required>
                        </div>
                        <div class="grupo-campo">
                            <label for="funcionario_receptor">Encargado receptor</label>
                            <input type="text" name="encargado" class="entrada" value="<?php echo e(old('encargado')); ?>" required>
                        </div>
                        <div class="grupo-campo">
                            <label for="cantidad">Cantidad de Personas</label>
                            <input type="number" name="cantidad" class="entrada" value="<?php echo e(old('cantidad')); ?>" required min="1">
                        </div>
                        <div class="grupo-campo">
                            <label for="fecha-visita">Fecha</label>
                            <input type="date" name="fecha_visita" id="fecha-visita" class="entrada" min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e(old('fecha_visita')); ?>" required>
                        </div>
                        <!-- Campo Hora en registro -->
                        <div class="grupo-campo">
                            <label for="hora-visita">Hora</label>
                            <input type="time" name="hora" id="hora-visita" class="entrada" value="<?php echo e(old('hora')); ?>" required>
                        </div>
                        <div class="grupo-campo">
                            <label for="sala_id">Sala / Tipo de Recorrido</label>
                            <select name="sala_id" id="sala_id" class="entrada" required>
                                <option value="">Seleccionar...</option>
                                <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sala): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sala->id); ?>" <?php echo e(old('sala_id') == $sala->id ? 'selected' : ''); ?>><?php echo e($sala->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="fila-botones">
                        <button type="submit" class="btn btn-azul">
                            <i data-lucide="save"></i> Guardar Registro
                        </button>
                        <button type="reset" id="btn-cancelar" class="btn btn-gris">Cancelar</button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Visitas -->
            <div class="contenedor-tabla" style="max-width: 1100px; width: 100%;">
                <div class="titulo-form">
                    <h3>Historial de Recorridos</h3>
                    <div class="busqueda-rapida">
                        <i data-lucide="search" size="18"></i>
                        <input type="text" id="input-filtro-tabla" class="input-busqueda" placeholder="Buscar institución o responsable...">
                    </div>
                    <button id="btn-toggle-form" class="btn btn-azul">
                        <i data-lucide="plus-circle"></i> Nuevo Registro
                    </button>
                </div>

                <table id="tabla-guiadas">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Institución</th>
                            <th>Responsable</th>
                            <th>Encargado</th>
                            <th>Personas</th>
                            <th>Sala</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        <?php $__currentLoopData = $visitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(\Carbon\Carbon::parse($visita->fecha_visita)->format('d/m/Y')); ?></td>
                            <td>
                                <?php if($visita->hora): ?>
                                    <?php echo e(\Carbon\Carbon::parse($visita->hora)->format('H:i')); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo e($visita->institucion); ?></strong></td>
                            <td><?php echo e($visita->responsable); ?></td>
                            <td><?php echo e($visita->encargado); ?></td>
                            <td><span class="count-badge"><?php echo e($visita->cantidad); ?></span></td>
                            <td><span class="badge-grupo"><?php echo e($visita->sala ? $visita->sala->nombre : ucfirst($visita->tipo_recorrido)); ?></span></td>
                            <td>
                                <button class="btn-icon" title="Ver" onclick="verGuia(<?php echo e($visita->id); ?>)">
                                    <i data-lucide="eye" style="color: #0984e3;"></i>
                                </button>
                                <button class="btn-icon" title="Editar" onclick="editarGuia(<?php echo e($visita->id); ?>)">
                                    <i data-lucide="edit-3" style="color: #00b894;"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <div class="paginacion-container">
                    <div class="info-paginacion" id="info-paginacion">Mostrando registros</div>
                    <div class="botones-paginacion">
                        <button class="btn-pag" id="btn-prev" disabled><i data-lucide="chevron-left" size="16"></i></button>
                        <div id="numeros-paginas" style="display: flex; gap: 5px;"></div>
                        <button class="btn-pag" id="btn-next"><i data-lucide="chevron-right" size="16"></i></button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Pie de página -->    
        <?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <!-- Modal de Ver Visita -->
    <div id="modal-ver-guia" class="modal-personalizado" style="display: none;">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2>Detalles del Recorrido</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModalVer()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Institución:</strong> <span id="ver-institucion"></span></p>
                <p><strong>Responsable:</strong> <span id="ver-responsable"></span></p>
                <p><strong>Cantidad de Personas:</strong> <span id="ver-cantidad"></span></p>
                <p><strong>Encargado:</strong> <span id="ver-encargado"></span></p>
                <p><strong>Fecha del Recorrido:</strong> <span id="ver-fecha"></span></p>
                <p><strong>Hora:</strong> <span id="ver-hora"></span></p>
                <p><strong>Sala / Tipo de Recorrido:</strong> <span id="ver-tipo"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-gris" onclick="cerrarModalVer()">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Editar Visita -->
    <div id="modal-editar-guia" class="modal-personalizado" style="display: none;">
        <div class="modal-contenido" style="max-width: 600px;">
            <div class="modal-header">
                <h2>Editar Visita Guiada</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModalEditar()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form id="form-editar-guia" method="POST">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="_method" value="PUT">
            
                <div class="rejilla-form">
                    <div class="grupo-campo ancho-total">
                        <label>Institución / Organización</label>
                        <input type="text" id="edit-institucion" name="institucion" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Responsable</label>
                        <input type="text" id="edit-responsable" name="responsable" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Encargado</label>
                        <input type="text" id="edit-encargado" name="encargado" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Cantidad</label>
                        <input type="number" id="edit-cantidad" name="cantidad" class="entrada" required min="1">
                    </div>
                    <div class="grupo-campo">
                        <label>Fecha</label>
                        <input type="date" id="edit-fecha" name="fecha_visita" class="entrada" required>
                    </div>
                    <!-- Campo Hora en edición -->
                    <div class="grupo-campo">
                        <label>Hora</label>
                        <input type="time" id="edit-hora" name="hora" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Sala / Tipo de Recorrido</label>
                        <select id="edit-sala_id" name="sala_id" class="entrada" required>
                            <option value="">Seleccionar...</option>
                            <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sala): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sala->id); ?>"><?php echo e($sala->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            
                <div class="fila-botones">
                    <button type="button" class="btn btn-gris" onclick="cerrarModalEditar()">Cancelar</button>
                    <button type="submit" class="btn btn-azul">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/plantilla.js')); ?>"></script>
    <script src="<?php echo e(asset('js/paginacion.js')); ?>"></script>
    <script src="<?php echo e(asset('js/mostrar_ocultar_formularios.js')); ?>"></script>
    <script src="<?php echo e(asset('js/visitas_guiadas.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('fecha-visita');
            if (dateInput && !dateInput.value) {
                dateInput.value = new Date().toISOString().split('T')[0];
            }
        });
    </script>

    <script>
    // Colección de visitas enviadas a JS para el calendario / cronograma
    window.visitasCronograma = <?php echo json_encode($visitas->items()); ?>;

    document.addEventListener('DOMContentLoaded', () => {
        // --- LÓGICA DESPLEGABLE CRONOGRAMA ---
        const btnToggle = document.getElementById('btn-toggle-cronograma');
        const cronogramaContenedor = document.getElementById('cronograma-container');

        if (btnToggle && cronogramaContenedor) {
            btnToggle.addEventListener('click', () => {
                if (cronogramaContenedor.style.display === 'none') {
                    cronogramaContenedor.style.display = 'block';
                    btnToggle.innerHTML = '<i data-lucide="calendar-off"></i> Ocultar Cronograma';
                    renderizarCalendario();
                } else {
                    cronogramaContenedor.style.display = 'none';
                    btnToggle.innerHTML = '<i data-lucide="calendar"></i> Ver Cronograma de Visitas';
                }
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        }

        // --- LÓGICA DEL CALENDARIO ---
        let fechaActual = new Date();
        const visitas = window.visitasCronograma || [];

        function renderizarCalendario() {
            const contenedorDias = document.getElementById('cal-dias');
            const txtMesAno = document.getElementById('cal-mes-ano');
            
            if (!contenedorDias) return;
            contenedorDias.innerHTML = '';

            const ano = fechaActual.getFullYear();
            const mes = fechaActual.getMonth();

            const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            txtMesAno.textContent = `${meses[mes]} ${ano}`;

            const primerDiaIndex = new Date(ano, mes, 1).getDay();
            const totalDias = new Date(ano, mes + 1, 0).getDate();

            for (let i = 0; i < primerDiaIndex; i++) {
                const divVacio = document.createElement('div');
                divVacio.classList.add('cal-dia', 'vacio');
                contenedorDias.appendChild(divVacio);
            }

            for (let dia = 1; dia <= totalDias; dia++) {
                const divDia = document.createElement('div');
                divDia.classList.add('cal-dia');
                divDia.textContent = dia;

                const mesFormateado = String(mes + 1).padStart(2, '0');
                const diaFormateado = String(dia).padStart(2, '0');
                const fechaString = `${ano}-${mesFormateado}-${diaFormateado}`;

                const visitasDelDia = visitas.filter(v => v.fecha_visita === fechaString);

                if (visitasDelDia.length > 0) {
                    divDia.classList.add('con-visita');
                    divDia.title = `${visitasDelDia.length} visita(s) programada(s)`;
                }

                divDia.addEventListener('click', () => {
                    document.querySelectorAll('.cal-dia').forEach(d => d.classList.remove('seleccionado'));
                    divDia.classList.add('seleccionado');
                    mostrarDetallesDia(fechaString, visitasDelDia);
                });

                contenedorDias.appendChild(divDia);
            }
        }

        function mostrarDetallesDia(fechaStr, visitasDia) {
            const txtFecha = document.getElementById('detalles-fecha');
            const listaVisitas = document.getElementById('detalles-visitas-lista');
            
            const [a, m, d] = fechaStr.split('-');
            txtFecha.textContent = `${d}/${m}/${a}`;

            listaVisitas.innerHTML = '';

            if (visitasDia.length === 0) {
                listaVisitas.innerHTML = '<p style="color: var(--texto-suave); font-style: italic;">No hay visitas agendadas para este día.</p>';
                return;
            }

            // Renderizar dinámicamente cada visita encontrada con su HORA
            visitasDia.forEach(v => {
                const divTarjeta = document.createElement('div');
                divTarjeta.classList.add('tarjeta-detalle-visita');
                divTarjeta.innerHTML = `
                    <h4>${v.institucion}</h4>
                    <p><strong>Hora:</strong> ${v.hora ? v.hora.substring(0, 5) : 'No asignada'}</p>
                    <p><strong>Responsable:</strong> ${v.responsable}</p>
                    <p><strong>Encargado:</strong> ${v.encargado || 'No asignado'}</p>
                    <p><strong>Personas:</strong> <span class="count-badge">${v.cantidad}</span> | <strong>Sala:</strong> ${(v.sala ? v.sala.nombre : (v.tipo_recorrido || '')).toUpperCase()}</p>
                `;
                listaVisitas.appendChild(divTarjeta);
            });
        }

        const btnPrev = document.getElementById('cal-prev');
        const btnNext = document.getElementById('cal-next');

        if (btnPrev && btnNext) {
            btnPrev.addEventListener('click', () => {
                fechaActual.setMonth(fechaActual.getMonth() - 1);
                renderizarCalendario();
            });
            btnNext.addEventListener('click', () => {
                fechaActual.setMonth(fechaActual.getMonth() + 1);
                renderizarCalendario();
            });
        }
    });
    </script>
</body>
</html>
