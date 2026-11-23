<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema Local - Biblioteca Dr. Félix Pifano</title>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <link rel="icon" href="{{ asset('img/library.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/plantilla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos_generales.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilo_tablas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registrar_visitas_modal.css') }}">
</head>
<body>

    <!-- Navegación lateral -->    
    @include('layouts.nav')

    <div id="main-wrapper">
        <main id="main-content">

            @include('layouts.mensajes')

            <div class="header-acciones">
                <h1>Registro de Encargados</h1>
            </div>

            <!-- Contenedor del Formulario (Oculto por defecto) -->
            <div id="form-container" class="contenedor-form" style="max-width: 100%; display: none;">
                <div class="titulo-form">
                    <h2>Nuevo Encargado</h2>
                    <button type="button" id="btn-cerrar-form" style="background:none; border:none; color:var(--error); cursor:pointer;">
                        <i data-lucide="x-circle"></i>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('encargados.registrar') }}" id="attendance-form">
                    {{ csrf_field() }}

                    <div class="grupo-campo">
                        <label for="id-card">Cédula</label>
                        <input required name="id-card" onkeypress="return permite(event, 'num')" type="text" id="id-card" class="entrada" placeholder="V-00.000.000" maxlength="8" minlength="8">
                    </div>

                    <div class="grupo-campo">
                        <label for="fullname">Nombre y Apellido</label>
                        <input required name="fullname" onkeypress="return permite(event, 'car')" type="text" id="fullname" class="entrada" placeholder="Ej. Juan Pérez">
                    </div>

                    <div class="grupo-campo">
                        <label for="telefono">Número de Teléfono</label>
                        <input required onkeypress="return permite(event, 'num')" type="text" id="telefono" name="telefono" class="entrada" placeholder="Ej. 04121234567" maxlength="11" minlength="11">
                    </div>

                    <div class="grupo-campo">
                        <label for="telefono2">Número de Teléfono Secundario</label>
                        <input onkeypress="return permite(event, 'num')" type="text" id="telefono2" name="telefono2" class="entrada" placeholder="Ej. 04127654321" maxlength="11" minlength="11">
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
                    <h3>Listado de Encargados</h3>
                    <div class="busqueda-rapida">
                        <i data-lucide="search" size="18" style="color: var(--texto-suave);"></i>
                        <input type="text" id="input-filtro-tabla" placeholder="Buscar por nombre o cédula">
                    </div>
                    <button id="btn-toggle-form" class="btn btn-azul">
                        <i data-lucide="plus-circle"></i>
                        <span>Nuevo Registro</span>
                    </button>
                </div>
                
                <table id="tabla-visitantes">
                    <thead>
                        <tr>
                            <th>Encargado</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        @foreach($encargados as $encargado)
                        <tr>
                            <td>{{ $encargado->nombre }}</td>
                            <td>{{ $encargado->cedula }}</td>
                            <td>{{ $encargado->telefono }}</td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <button type="button" class="btn-icon" title="Ver" onclick="verPersona({{ $encargado->id }})">
                                        <i data-lucide="eye" style="color: #0984e3;"></i>
                                    </button>
                                    <button type="button" class="btn-icon" title="Editar" onclick="editarPersona({{ $encargado->id }})">
                                        <i data-lucide="edit-3" style="color: #00b894;"></i>
                                    </button>
                                    <button type="button" class="btn-icon" title="Eliminar" onclick="eliminarPersona({{ $encargado->id }})">
                                        <i data-lucide="trash-2" style="color: #d63031;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Controles de Paginación -->
                <div class="paginacion-container">
                    <div class="info-paginacion" id="info-paginacion">
                        Mostrando registros del 1 al {{ count($encargados) }}
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
        @include('layouts.footer')
    </div>

    <!-- Modal de Ver Persona -->
    <div id="modal-ver-persona" class="modal-personalizado" style="display: none;">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2>Detalles del Encargado</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModalVer()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Nombre:</strong> <span id="ver-nombre"></span></p>
                <p><strong>Cédula:</strong> <span id="ver-cedula"></span></p>
                <p><strong>Teléfono:</strong> <span id="ver-telefono"></span></p>
                <p><strong>Teléfono Secundario:</strong> <span id="ver-telefono2"></span></p>
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
                <h2>Editar Encargado</h2>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModalEditar()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form id="form-editar-persona" method="POST">
                {{ csrf_field() }}
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
                        <label>Teléfono</label>
                        <input type="text" id="edit-telefono" name="telefono" class="entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Teléfono Secundario</label>
                        <input type="text" id="edit-telefono2" name="telefono2" class="entrada">
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
        // Buscar encargado por cédula al perder el foco
        document.getElementById('id-card').addEventListener('blur', function() {
            let cedula = this.value;
            if (cedula.length >= 4) { 
                fetch(`/encargados/buscar/${cedula}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'encontrado') {
                            // Rellenar los campos
                            document.getElementById('fullname').value = data.datos.nombre;
                            document.getElementById('telefono').value = data.datos.telefono;
                            document.getElementById('telefono2').value = data.datos.telefono2 || '';
                            
                            // Notificar al usuario que el encargado ya existe
                            mostrarInfo('Este encargado ya está registrado. Se han cargado sus datos.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        // Funciones para los modales
        function verPersona(id) {
            fetch(`/encargados/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('ver-nombre').textContent = data.nombre;
                    document.getElementById('ver-cedula').textContent = data.cedula;
                    document.getElementById('ver-telefono').textContent = data.telefono;
                    document.getElementById('ver-telefono2').textContent = data.telefono2 || 'No registrado';
                    
                    document.getElementById('modal-ver-persona').style.display = 'flex';
                })
                .catch(error => console.error('Error:', error));
        }

        function cerrarModalVer() {
            document.getElementById('modal-ver-persona').style.display = 'none';
        }

        function editarPersona(id) {
            fetch(`/encargados/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-persona-id').value = data.id;
                    document.getElementById('edit-cedula').value = data.cedula;
                    document.getElementById('edit-nombre').value = data.nombre;
                    document.getElementById('edit-telefono').value = data.telefono;
                    document.getElementById('edit-telefono2').value = data.telefono2 || '';
                    
                    // Cambiar la acción del formulario
                    document.getElementById('form-editar-persona').action = `/encargados/${id}`;
                    
                    document.getElementById('modal-editar-persona').style.display = 'flex';
                })
                .catch(error => console.error('Error:', error));
        }

        function cerrarModalEditar() {
            document.getElementById('modal-editar-persona').style.display = 'none';
        }

        // Función para eliminar encargado
        function eliminarPersona(id) {
            confirmar('¿Estás seguro?', 'Se eliminará permanentemente a este encargado. Esta acción no se puede deshacer.', function() {
                // Crear un formulario para eliminar
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/encargados/${id}`;

                // Agregar CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfInput);

                // Agregar método DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Agregar el formulario al body y enviarlo
                document.body.appendChild(form);
                form.submit();
            });
        }

        // Cerrar modales al hacer clic fuera
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-personalizado')) {
                event.target.style.display = 'none';
            }
        }
    </script>

    <script src="{{ asset('js/plantilla.js') }}?v={{ filemtime(public_path('js/plantilla.js')) }}"></script>
    <script src="{{ asset('js/paginacion.js') }}"></script>
    <script src="{{ asset('js/validaciones.js') }}"></script>
    <script src="{{ asset('js/mostrar_ocultar_formularios.js') }}"></script>
</body>
</html>