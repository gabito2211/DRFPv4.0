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
            
            <div class="header-acciones">
                <div>
                    <h1>Registro de Visitantes</h1>
                    <p style="color: var(--texto-suave);">Control de visitante.</p>
                </div>    
            </div>

            <!-- Contenedor del Formulario (Oculto por defecto) -->
            <div id="form-container" class="contenedor-form" style="max-width: 100%;">
                <div class="titulo-form">
                    <h2>Nueva Asistencia</h2>
                    <button type="button" id="btn-cerrar-form" style="background:none; border:none; color:var(--error); cursor:pointer;">
                        <i data-lucide="x-circle"></i>
                    </button>
                </div>
                
                <form method="POST" action="<?php echo e(route('visitantes.registrar')); ?>" id="attendance-form">
                    <?php echo e(csrf_field()); ?>

   

                    
                    <div class="rejilla-form">
                        <!-- Campo Fecha: Ponle el valor de hoy por defecto para ahorrar tiempo -->
                        <div class="grupo-campo">
                            <label for="reg-date">Fecha</label>
                            <input type="date" id="reg-date" name="fecha" class="entrada" value="<?php echo e(date('Y-m-d')); ?>" required>
                        </div>

                        <!-- Campo Cédula: Será nuestra llave de búsqueda -->
                        <div class="grupo-campo">
                            <label for="id-card">Cédula</label>
                            <input required name="id-card" onkeypress="return permite(event, 'num')" type="text" id="id-card" class="entrada" placeholder="V-00.000.000"  maxlength="8" minlength="8">                        </div>

                        <!-- El resto de los campos se mantienen igual, solo verifica el atributo 'name' -->
                        <div class="grupo-campo">
                            <label for="fullname">Nombre y Apellido</label>
                            <input required name="fullname" onkeypress="return permite(event, 'car')" type="text" id="fullname" class="entrada" placeholder="Ej. Juan Pérez">
                        </div>
                        <div class="grupo-campo">
                            <label>Sexo</label>
                            <div class="opciones-grupo">
                                <label class="opcion-item"><input type="radio" name="gender" value="M" required> M</label>
                                <label class="opcion-item"><input type="radio" name="gender" value="F"> F</label>
                            </div>
                        </div>
                        <div class="grupo-campo">
                            <label for="sala_id">Sala</label>
                            <select name="sala_id" id="sala_id" class="entrada" required>
                                <option value="">Seleccionar...</option>
                                <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sala): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sala->id); ?>"><?php echo e($sala->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="grupo-campo">
                            <label for="telefono">Numero de teltefono</label>
                            <input required onkeypress="return permite(event, 'num')" type="text" id="telefono" name="telefono" class="entrada" placeholder="Ej. (0412)-1234-567"  maxlength="11" minlength="11">
                        </div>
                        <div class="grupo-campo">
                            <label for="telefono2">Numero de Telefono segundario</label>
                            <input required onkeypress="return permite(event, 'num')" type="text" id="telefono2" name="telefono2" class="entrada" placeholder="Ej. (0412)-1234-567" maxlength="11" minlength="11">
                        </div>
                        <div class="grupo-campo">
                            <label for="gmail">Correo Electronico</label>
                            <input required  type="gmail" id="gmail" name="gmail" class="entrada" placeholder="Ej. libro@gmail.com">
                        </div>
                        <div class="grupo-campo">
                        <label for="comunidad">Comunidad</label>
                        <select class="entrada" id="ubicacion" name="comunidad" required>
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
                            <label for="direccion">Direccion exacta</label>
                            <textarea id="direccion" name="direccion" class="area-texto" placeholder="Notas adicionales..."></textarea>
                        </div>
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
                    <h3>Listado de Asistencias Hoy</h3>
                    <div class="busqueda-rapida">
                        <i data-lucide="search" size="18" style="color: var(--texto-suave);"></i>
                        <input type="text" id="input-filtro-tabla" placeholder="Buscar por nombre o cédula ">
                    </div>
                    <button id="btn-toggle-form" class="btn btn-azul">
                        <i data-lucide="plus-circle"></i>
                        <span>Nuevo Registro</span>
                    </button>
                </div>
               <table id="tabla-visitantes">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Visitante</th>
                            <th>Cédula</th>
                            <th>Sexo</th>
                            <th>Sala</th>
                            <th>Comunidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        <?php $__currentLoopData = $visitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($visita->fecha); ?></td>
                            <td><?php echo e($visita->hora_entrada ? date('H:i', strtotime($visita->hora_entrada)) : '—'); ?></td>
                            <td><?php echo e($visita->persona->nombre); ?></td>
                            <td><?php echo e($visita->persona->cedula); ?></td>
                            <td><?php echo e($visita->persona->sexo); ?></td>
                            <td><?php echo e($visita->sala ? $visita->sala->nombre : $visita->departamento); ?></td>
                            <td><?php echo e($visita->persona->comunidad); ?></td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <button type="button" class="btn-icon" title="Ver" onclick="verPersona(<?php echo e($visita->persona->id); ?>)">
                                        <i data-lucide="eye" style="color: #0984e3;"></i>
                                    </button>
                                    <button type="button" class="btn-icon" title="Editar" onclick="editarPersona(<?php echo e($visita->persona->id); ?>)">
                                        <i data-lucide="edit-3" style="color: #00b894;"></i>
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
                        Mostrando registros del 1 al 5
                    </div>
                    <div class="botones-paginacion">
                        <button class="btn-pag" id="btn-prev" disabled>
                            <i data-lucide="chevron-left" size="16"></i> Anterior
                        </button>
                        <div id="numeros-paginas" style="display: flex; gap: 5px;">
                            <!-- Los números se generarán con js -->
                        </div>
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
   <!-- Modal de Ver Persona -->
    <div id="modal-ver-persona" class="modal-personalizado" style="display: none;">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2>Detalles de la Persona</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModalVer()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Nombre:</strong> <span id="ver-nombre"></span></p>
                <p><strong>Cédula:</strong> <span id="ver-cedula"></span></p>
                <p><strong>Sexo:</strong> <span id="ver-sexo"></span></p>
                <p><strong>Teléfono:</strong> <span id="ver-telefono"></span></p>
                <p><strong>Teléfono 2:</strong> <span id="ver-telefono2"></span></p>
                <p><strong>Correo:</strong> <span id="ver-correo"></span></p>
                <p><strong>Comunidad:</strong> <span id="ver-comunidad"></span></p>
                <p><strong>Dirección:</strong> <span id="ver-direccion"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-gris" onclick="cerrarModalVer()">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Editar Persona -->
    <div id="modal-editar-persona" class="modal-personalizado" style="display: none;">
        <div class="modal-contenido" style="max-width: 600px;">
            <div class="modal-header">
                <h2>Editar Persona</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModalEditar()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form id="form-editar-persona" method="POST">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit-persona-id" name="persona_id">
                
                <div class="rejilla-form">
                    <div class="grupo-campo">
                        <label>Cédula</label>
                        <input type="text" id="edit-cedula" name="cedula" class="entrada" readonly>
                    </div>
                    <div class="grupo-campo">
                        <label>Nombre y Apellido</label>
                        <input type="text" id="edit-nombre" name="nombre" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Sexo</label>
                        <select id="edit-sexo" name="sexo" class="entrada" required>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div class="grupo-campo">
                        <label>Teléfono</label>
                        <input type="text" id="edit-telefono" name="telefono" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Teléfono 2</label>
                        <input type="text" id="edit-telefono2" name="telefono2" class="entrada">
                    </div>
                    <div class="grupo-campo">
                        <label>Correo Electrónico</label>
                        <input type="email" id="edit-correo" name="gmail" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Comunidad</label>
                        <select id="edit-comunidad" name="comunidad" class="entrada" required>
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
                        <textarea id="edit-direccion" name="direccion" class="area-texto" rows="2"></textarea>
                    </div>
                </div>
                
                <div class="fila-botones">
                    <button type="button" class="btn btn-gris" onclick="cerrarModalEditar()">Cancelar</button>
                    <button type="submit" class="btn btn-azul">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script>
        document.getElementById('id-card').addEventListener('blur', function() {
            let cedula = this.value;
            if (cedula.length > 4) { 
                fetch(`/buscar-persona/${cedula}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'encontrado') {
                            // 1. Rellenamos los campos
                            document.getElementById('fullname').value = data.datos.nombre;
                            document.getElementById('telefono').value = data.datos.telefono;
                            document.getElementById('telefono2').value = data.datos.telefono2 || '';
                            document.getElementById('gmail').value = data.datos.correo;
                            document.getElementById('direccion').value = data.datos.direccion;
                            
                            let genderRadios = document.getElementsByName('gender');
                            genderRadios.forEach(radio => {
                                if (radio.value === data.datos.sexo) radio.checked = true;
                            });

                            document.getElementById('ubicacion').value = data.datos.comunidad;
                            
                            // 2. LLAMAMOS A LA FUNCIÓN (Ahora sí la encontrará)
                            notificarEncontrado();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>
    <script src="<?php echo e(asset('js/plantilla.js')); ?>"></script>
    <script src="<?php echo e(asset('js/paginacion.js')); ?>"></script>
    <script src="<?php echo e(asset('js/validaciones.js')); ?>"></script>
    <script src="<?php echo e(asset('js/mostrar_ocultar_formularios.js')); ?>"></script>
    <script src="<?php echo e(asset('js/registrar_visitas.js')); ?>"></script></body>
</html>