<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Usuarios - Biblioteca Dr. Félix Pifano</title>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <link rel="icon" href="{{ asset('img/library.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/plantilla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos_generales.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilo_tablas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registrar_visitas_modal.css') }}">
</head>

<style>
    .modal-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-contenido {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
</style>

<body>

    <!-- Navegación lateral -->
    @include('layouts.nav')

    <div id="main-wrapper">
        <main id="main-content">

            @include('layouts.mensajes')

            <div class="header-acciones">
                <div>
                    <h1>Gestión de Usuarios</h1>
                    <p style="color: var(--texto-suave);">Creacion y control de usuarios.</p>
                </div> 
            </div>

            <div id="form-container" class="contenedor-form" style="max-width: 100%; display: none;">
                <div class="titulo-form">
                    <h2>Nuevo Usuario</h2>
                    <button type="button" id="btn-cerrar-form" style="background:none; border:none; color:var(--error); cursor:pointer;">
                        <i data-lucide="x-circle"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('usuarios.store') }}" id="form-usuario">
                    {{ csrf_field() }}

                    <div class="grupo-campo">
                        <label for="name">Nombre completo</label>
                        <input required name="name" type="text" id="name" class="entrada" placeholder="Ej. Juan Pérez" value="{{ old('name') }}">
                    </div>

                    <div class="grupo-campo">
                        <label for="email">Correo electrónico</label>
                        <input required name="email" type="email" id="email" class="entrada" placeholder="usuario@correo.com" value="{{ old('email') }}">
                    </div>

                    <div class="grupo-campo">
                        <label for="rol">Rol de Usuario</label>
                        <select required name="rol" id="rol" class="entrada">
                            <option value="" disabled selected>Selecciona un rol</option>
                            <option value="1" {{ old('rol') == 1 ? 'selected' : '' }}>Administrador</option>
                            <option value="2" {{ old('rol') == 2 ? 'selected' : '' }}>Recepcionista</option>
                            <option value="3" {{ old('rol') == 3 ? 'selected' : '' }}>Almacenista</option>
                        </select>
                    </div>

                    <div class="grupo-campo">
                        <label for="sede_id">Sede</label>
                        @if($esPrincipal)
                            <select name="sede_id" id="sede_id" class="entrada">
                                <option value="">Sin Sede Asignada</option>
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id }}" {{ old('sede_id') == $sede->id ? 'selected' : '' }}>
                                        {{ $sede->biblioteca }} ({{ $sede->comunidad }})
                                    </option>
                                @endforeach
                            </select>
                        @else
                            {{-- Admin de sede secundaria: siempre su propia sede --}}
                            <input type="hidden" name="sede_id" value="{{ Auth::user()->sede_id }}">
                            <input type="text" class="entrada" value="{{ Auth::user()->sede ? Auth::user()->sede->biblioteca : 'Sede ' . Auth::user()->sede_id }}" disabled>
                        @endif
                    </div>

                    <div class="grupo-campo">
                        <label for="password">Contraseña</label>
                        <div class="input-wrapper" style="position: relative; display: flex; align-items: center;">
                            <input required name="password" type="password" id="password" class="entrada" placeholder="Mínimo 6 caracteres" minlength="6" style="padding-right: 40px; width: 100%;">
                            <button type="button" onclick="togglePasswordVisibility('password', this)" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; color: var(--text-muted, #64748b); padding: 5px; display: flex; align-items: center;" title="Mostrar / Ocultar contraseña">
                                <i data-lucide="eye" size="18"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grupo-campo">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <div class="input-wrapper" style="position: relative; display: flex; align-items: center;">
                            <input required name="password_confirmation" type="password" id="password_confirmation" class="entrada" placeholder="Repite la contraseña" minlength="6" style="padding-right: 40px; width: 100%;">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; color: var(--text-muted, #64748b); padding: 5px; display: flex; align-items: center;" title="Mostrar / Ocultar contraseña">
                                <i data-lucide="eye" size="18"></i>
                            </button>
                        </div>
                    </div>

                    <div class="fila-botones">
                        <button type="submit" class="btn btn-azul">
                            <i data-lucide="save"></i> Guardar Usuario
                        </button>
                        <button type="reset" class="btn btn-gris" id="btn-cancelar">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>

            <div class="contenedor-tabla">
                <div class="titulo-form">
                    <h3>Usuarios del sistema</h3>
                    <div class="busqueda-rapida">
                        <i data-lucide="search" size="18" style="color: var(--texto-suave);"></i>
                        <input type="text" id="input-filtro-tabla" placeholder="Buscar por nombre o correo">
                    </div>
                    <button id="btn-toggle-form" class="btn btn-azul">
                        <i data-lucide="plus-circle"></i>
                        <span>Nuevo Registro</span>
                    </button>
                </div>

                <table id="tabla-usuarios">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>                            
                            <th>Registrado</th>
                            <th>Estado</th> <!-- Nueva Columna -->
                            <th>Sede</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="badge badge-rol-{{ $usuario->rol }}">
                                    {{ $usuario->nombre_rol }}
                                </span>
                            </td>
                            <td>{{ $usuario->sede ? $usuario->sede->biblioteca : 'N/A' }}</td>
                            <td>{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : 'N/A' }}</td>
                            <td>
            <span class="badge {{ $usuario->estado === 'habilitado' ? 'badge-exito' : 'badge-error' }}" style="padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; background: {{ $usuario->estado === 'habilitado' ? '#e6f4ea' : '#fce8e6' }}; color: {{ $usuario->estado === 'habilitado' ? '#137333' : '#c5221f' }};">
                {{ ucfirst($usuario->estado) }}
            </span>
        </td>
                            <td>

                            <div style="display: flex; gap: 5px;">
    <!-- Botón para Visualizar -->
    <button type="button" class="btn-icon" title="Ver Detalles" onclick="abrirModalVer({{ json_encode($usuario) }})">
        <i data-lucide="eye" style="color: #0984e3;"></i>
    </button>

                                <form action="{{ route('usuarios.toggle-estado', $usuario->id) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PATCH">
                    <button type="submit" class="btn-icon" title="{{ $usuario->estado === 'habilitado' ? 'Inhabilitar Usuario' : 'Habilitar Usuario' }}" style="background: none; border: none; cursor: pointer;">
                        @if($usuario->estado === 'habilitado')
                            <i data-lucide="user-check" style="color: #27ae60;"></i>
                        @else
                            <i data-lucide="user-x" style="color: #e74c3c;"></i>
                        @endif
                    </button>
                </form>

                                <div style="display: flex; gap: 5px;">
                                    <button type="button" class="btn-icon" title="Editar" onclick="abrirModalEditar({{ json_encode($usuario) }})">
                                        <i data-lucide="edit-3" style="color: #00b894;"></i>
                                    </button>

                                    <button type="button" class="btn-icon" title="Generar código de recuperación de contraseña"
                                            onclick="generarCodigoRecuperacion({{ $usuario->id }}, {{ json_encode($usuario->name) }})">
                                        <i data-lucide="key-round" style="color: #f39c12;"></i>
                                    </button>

                                    <button type="button" class="btn-icon" title="Eliminar" onclick="eliminarUsuario({{ $usuario->id }})">
                                        <i data-lucide="trash-2" style="color: #d63031;"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color: var(--texto-suave);">Todavía no hay usuarios registrados.</td>
                        </tr>
                        @endforelse
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

        <div id="modal-editar-usuario" class="modal-background" style="display: none;">
        <div class="modal-contenido">
            <div class="titulo-form" style="margin-bottom: 20px;">
                <h2 id="modal-titulo">Editar Usuario</h2>
                <button type="button" onclick="cerrarModalEditar()" style="background:none; border:none; color:var(--error); cursor:pointer;">
                    <i data-lucide="x-circle"></i>
                </button>
            </div>

            <form method="POST" id="form-editar-usuario">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">

                <div class="grupo-campo">
                    <label for="edit_name">Nombre completo</label>
                    <input required name="name" type="text" id="edit_name" class="entrada" placeholder="Ej. Juan Pérez">
                </div>

                <div class="grupo-campo">
                    <label for="edit_email">Correo electrónico</label>
                    <input required name="email" type="email" id="edit_email" class="entrada" placeholder="usuario@correo.com">
                </div>

                <div class="grupo-campo">
                    <label for="edit_rol">Rol de Usuario</label>
                    <select required name="rol" id="edit_rol" class="entrada">
                        <option value="1">Administrador</option>
                        <option value="2">Recepcionista</option>
                        <option value="3">Almacenista</option>
                    </select>
                </div>

                <div class="grupo-campo">
                        <label for="edit_sede_id">Sede</label>
                        @if($esPrincipal)
                            <select name="sede_id" id="edit_sede_id" class="entrada">
                                <option value="">Sin Sede Asignada</option>
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id }}">{{ $sede->biblioteca }} ({{ $sede->comunidad }})</option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" name="sede_id" value="{{ Auth::user()->sede_id }}">
                            <input type="text" id="edit_sede_id" class="entrada" value="{{ Auth::user()->sede ? Auth::user()->sede->biblioteca : 'Sede ' . Auth::user()->sede_id }}" disabled>
                        @endif
                    </div>

                <div class="grupo-campo">
                    <label for="edit_password">Nueva Contraseña (Opcional)</label>
                    <div class="input-wrapper" style="position: relative; display: flex; align-items: center;">
                        <input name="password" type="password" id="edit_password" class="entrada" placeholder="Dejar en blanco para no cambiarla" minlength="6" style="padding-right: 40px; width: 100%;">
                        <button type="button" onclick="togglePasswordVisibility('edit_password', this)" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; color: var(--text-muted, #64748b); padding: 5px; display: flex; align-items: center;" title="Mostrar / Ocultar contraseña">
                            <i data-lucide="eye" size="18"></i>
                        </button>
                    </div>
                </div>

                <div class="grupo-campo">
                    <label for="edit_password_confirmation">Confirmar nueva contraseña</label>
                    <div class="input-wrapper" style="position: relative; display: flex; align-items: center;">
                        <input name="password_confirmation" type="password" id="edit_password_confirmation" class="entrada" placeholder="Repite la contraseña" minlength="6" style="padding-right: 40px; width: 100%;">
                        <button type="button" onclick="togglePasswordVisibility('edit_password_confirmation', this)" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; color: var(--text-muted, #64748b); padding: 5px; display: flex; align-items: center;" title="Mostrar / Ocultar contraseña">
                            <i data-lucide="eye" size="18"></i>
                        </button>
                    </div>
                </div>

                <div class="fila-botones" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-azul">
                        <i data-lucide="save"></i> Actualizar Usuario
                    </button>
                    <button type="button" class="btn btn-gris" onclick="cerrarModalEditar()">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Visualizar Usuario -->
<div id="modal-ver-usuario" class="modal-background" style="display: none;">
    <div class="modal-contenido" style="max-width: 450px;">
        <div class="titulo-form" style="margin-bottom: 20px; align-items: center;">
            <h2>Detalles del Usuario</h2>
            <button type="button" onclick="cerrarModalVer()" style="background:none; border:none; color:var(--error); cursor:pointer;">
                <i data-lucide="x-circle"></i>
            </button>
        </div>

        <div class="detalles-usuario" style="display: flex; flex-direction: column; gap: 15px;">
            <div class="grupo-campo">
                <label style="font-weight: bold; color: var(--texto-suave); font-size: 0.85rem;">Nombre Completo</label>
                <p id="ver_name" style="margin: 4px 0 0 0; font-size: 1rem; color: #333; font-weight: 500;"></p>
            </div>

            <div class="grupo-campo">
                <label style="font-weight: bold; color: var(--texto-suave); font-size: 0.85rem;">Correo Electrónico</label>
                <p id="ver_email" style="margin: 4px 0 0 0; font-size: 1rem; color: #333;"></p>
            </div>

            <div class="grupo-campo">
                <label style="font-weight: bold; color: var(--texto-suave); font-size: 0.85rem;">Rol Asignado</label>
                <div id="ver_rol" style="margin-top: 4px;"></div>
            </div>

            <div class="grupo-campo">
                <label style="font-weight: bold; color: var(--texto-suave); font-size: 0.85rem;">Sede</label>
                <p id="ver_sede" style="margin: 4px 0 0 0; font-size: 1rem; color: #333;"></p>
            </div>

            <div class="grupo-campo">
                <label style="font-weight: bold; color: var(--texto-suave); font-size: 0.85rem;">Estado de la Cuenta</label>
                <div id="ver_estado" style="margin-top: 4px;"></div>
            </div>

            <div class="grupo-campo">
                <label style="font-weight: bold; color: var(--texto-suave); font-size: 0.85rem;">Fecha de Registro</label>
                <p id="ver_created_at" style="margin: 4px 0 0 0; font-size: 1rem; color: #333;"></p>
            </div>
        </div>

        <div class="fila-botones" style="margin-top: 25px; justify-content: flex-end;">
            <button type="button" class="btn btn-gris" onclick="cerrarModalVer()">
                Cerrar
            </button>
        </div>
    </div>
</div>

        <!-- Modal para Generar Código de Recuperación -->
    <div id="modal-codigo-recuperacion" class="modal-background" style="display: none;">
        <div class="modal-contenido" style="max-width: 460px;">
            <div class="titulo-form" style="margin-bottom: 20px; align-items: center;">
                <h2>Código de Recuperación</h2>
                <button type="button" onclick="cerrarModalCodigo()" style="background:none; border:none; color:var(--error); cursor:pointer;">
                    <i data-lucide="x-circle"></i>
                </button>
            </div>

            <div class="grupo-campo">
                <label>Usuario</label>
                <p id="rec_usuario" style="margin: 4px 0 0 0; font-size: 1rem; color: #333; font-weight: 500;"></p>
            </div>

            <div class="grupo-campo" style="margin-top: 15px;">
                <label>Código generado</label>
                <div id="rec_caja_codigo" style="display: none;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 6px;">
                        <input type="text" id="rec_codigo" readonly
                            style="flex: 1; padding: 14px; font-size: 1.4rem; letter-spacing: 5px; font-weight: 700; text-align: center; font-family: 'Courier New', monospace; border: 2px dashed #f39c12; border-radius: 8px; background: #fff8e8; color: #b45309;">
                        <button type="button" class="btn-icon" title="Copiar código"
                            onclick="copiarCodigo()" style="background: none; border: 1px solid var(--border-color, #d1d5db); border-radius: 6px; padding: 9px; cursor: pointer; display: flex;">
                            <i data-lucide="copy" style="color: #0f766e;"></i>
                        </button>
                    </div>
                    <p style="margin: 8px 0 0 0; font-size: 0.8rem; color: #b45309;">
                        <i data-lucide="alert-triangle" size="13" style="vertical-align: -2px;"></i>
                        Este código se muestra una sola vez. Entrégaselo al usuario.
                    </p>
                    <p id="rec_expira" style="margin: 6px 0 0 0; font-size: 0.8rem; color: var(--texto-suave);"></p>
                </div>

                <div id="rec_cargando" style="display: none; padding: 20px; text-align: center; color: var(--texto-suave);">
                    <i data-lucide="loader" size="20"></i> Generando código...
                </div>
            </div>

            <div class="fila-botones" style="margin-top: 25px; justify-content: flex-end;">
                <button type="button" class="btn btn-gris" onclick="cerrarModalCodigo()">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

        @include('layouts.footer')
    </div>

    <script>
    // Filtro de tabla (se queda aquí porque es exclusivo de esta vista)
    document.getElementById('input-filtro-tabla').addEventListener('keyup', function () {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll('#tabla-body tr').forEach(function (fila) {
            fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? '' : 'none';
        });
    });

    // --- FUNCIONALIDAD DEL MODAL ---
    function abrirModalEditar(usuario) {
        const modal = document.getElementById('modal-editar-usuario');
        const form = document.getElementById('form-editar-usuario');

        // Asignar el action dinámicamente con la ruta de actualización
        form.action = `/usuario/${usuario.id}`;

        // Rellenar los inputs con los datos del usuario seleccionado
        document.getElementById('edit_name').value = usuario.name;
        document.getElementById('edit_email').value = usuario.email;
        document.getElementById('edit_rol').value = usuario.rol;
        document.getElementById('edit_sede_id').value = usuario.sede_id ? usuario.sede_id : '';
        
        // Limpiar campos de contraseña por seguridad
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';

        // Mostrar modal
        modal.style.display = 'flex';
        
        // Recargar iconos en el modal
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    }

    function cerrarModalEditar() {
        document.getElementById('modal-editar-usuario').style.display = 'none';
    }

    // Cerrar modal si se hace clic fuera del contenido blanco
    window.onclick = function(event) {
        const modal = document.getElementById('modal-editar-usuario');
        if (event.target === modal) {
            cerrarModalEditar();
        }
    }

    // Acción de eliminar (exclusivo de esta vista)
    function eliminarUsuario(id) {
        confirmar('¿Estás seguro?', 'Se eliminará permanentemente a este usuario. Esta acción no se puede deshacer.', function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/usuario/${id}`;

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

    // Función global para alternar visibilidad de contraseña
    function togglePasswordVisibility(inputId, btn) {
        var input = document.getElementById(inputId);
        if (!input) return;
        var esPassword = (input.getAttribute('type') === 'password');
        input.setAttribute('type', esPassword ? 'text' : 'password');
        btn.innerHTML = '<i data-lucide="' + (esPassword ? 'eye-off' : 'eye') + '" size="18"></i>';
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    }

    // Inicializar iconos de Lucide al cargar
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    });
</script>

<script>
    // -------------------------------------------------------------
    // FILTRO DE TABLA (Ámbito Global)
    // -------------------------------------------------------------
    document.getElementById('input-filtro-tabla').addEventListener('keyup', function () {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll('#tabla-body tr').forEach(function (fila) {
            fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? '' : 'none';
        });
    });

    // -------------------------------------------------------------
    // MODAL DE EDICIÓN
    // -------------------------------------------------------------
    function abrirModalEditar(usuario) {
        const modal = document.getElementById('modal-editar-usuario');
        const form = document.getElementById('form-editar-usuario');

        // Asignar el action dinámicamente con la ruta de actualización
        form.action = `/usuario/${usuario.id}`;

        // Rellenar los inputs con los datos del usuario seleccionado
        document.getElementById('edit_name').value = usuario.name;
        document.getElementById('edit_email').value = usuario.email;
        document.getElementById('edit_rol').value = usuario.rol;
        document.getElementById('edit_sede_id').value = usuario.sede_id ? usuario.sede_id : '';
        
        // Limpiar campos de contraseña por seguridad
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';

        // Mostrar modal
        modal.style.display = 'flex';
        
        // Recargar iconos en el modal
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    }

    function cerrarModalEditar() {
        document.getElementById('modal-editar-usuario').style.display = 'none';
    }

    // -------------------------------------------------------------
    // MODAL DE VISUALIZACIÓN (GLOBAL)
    // -------------------------------------------------------------
    function abrirModalVer(usuario) {
        const modal = document.getElementById('modal-ver-usuario');

        // Mapeo de Roles según la convención del sistema
        const roles = {
            1: 'Administrador',
            2: 'Recepcionista',
            3: 'Almacenista'
        };

        // Asignación de datos a los campos de texto
        document.getElementById('ver_name').textContent = usuario.name || 'N/A';
        document.getElementById('ver_email').textContent = usuario.email || 'N/A';
        
        // Mapeo del badge para el Rol
        const rolNombre = usuario.nombre_rol || roles[usuario.rol] || 'Sin Rol';
        document.getElementById('ver_rol').innerHTML = `
            <span class="badge badge-rol-${usuario.rol}">
                ${rolNombre}
            </span>
        `;

        // Sede asignada
        const nombreSede = usuario.sede ? usuario.sede.biblioteca : 'Sin Sede Asignada';
        document.getElementById('ver_sede').textContent = nombreSede;

        // Estado con formato visual de Badge
        const esHabilitado = usuario.estado === 'habilitado';
        const estadoTexto = usuario.estado ? usuario.estado.charAt(0).toUpperCase() + usuario.estado.slice(1) : 'N/A';
        const bgEstado = esHabilitado ? '#e6f4ea' : '#fce8e6';
        const colorEstado = esHabilitado ? '#137333' : '#c5221f';

        document.getElementById('ver_estado').innerHTML = `
            <span class="badge" style="padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; background: ${bgEstado}; color: ${colorEstado}; display: inline-block;">
                ${estadoTexto}
            </span>
        `;

        // Formatear Fecha de Creación (dd/mm/yyyy)
        if (usuario.created_at) {
            const fecha = new Date(usuario.created_at);
            document.getElementById('ver_created_at').textContent = fecha.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        } else {
            document.getElementById('ver_created_at').textContent = 'N/A';
        }

        // Mostrar modal
        modal.style.display = 'flex';

        // Recargar iconos de Lucide
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    }

    function cerrarModalVer() {
        document.getElementById('modal-ver-usuario').style.display = 'none';
    }

    // -------------------------------------------------------------
    // CERRAR MODALES HACIENDO CLIC FUERA
    // -------------------------------------------------------------
    window.onclick = function(event) {
        const modalEditar = document.getElementById('modal-editar-usuario');
        const modalVer = document.getElementById('modal-ver-usuario');
        
        if (event.target === modalEditar) {
            cerrarModalEditar();
        }
        if (event.target === modalVer) {
            cerrarModalVer();
        }
    };

    // -------------------------------------------------------------
    // ACCIÓN DE ELIMINAR
    // -------------------------------------------------------------
    function eliminarUsuario(id) {
        confirmar('¿Estás seguro?', 'Se eliminará permanentemente a este usuario. Esta acción no se puede deshacer.', function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/usuario/${id}`;

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
</script>

    <script src="{{ asset('js/plantilla.js') }}?v={{ filemtime(public_path('js/plantilla.js')) }}"></script>
    <script src="{{ asset('js/paginacion.js') }}"></script>
    <script src="{{ asset('js/validaciones.js') }}"></script>
    <script src="{{ asset('js/mostrar_ocultar_formularios.js') }}"></script>

    <script>
    function generarCodigoRecuperacion(id, nombre) {
        var modal = document.getElementById('modal-codigo-recuperacion');
        document.getElementById('rec_usuario').textContent = nombre;
        document.getElementById('rec_caja_codigo').style.display = 'none';
        document.getElementById('rec_cargando').style.display = 'block';
        modal.style.display = 'flex';

var token = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/usuario/' + id + '/codigo-recuperacion', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (r) {
            return r.json().then(function (d) { return { ok: r.ok, datos: d }; });
        })
        .then(function (respuesta) {
            document.getElementById('rec_cargando').style.display = 'none';
            var data = respuesta.datos;

            if (respuesta.ok && data.exito) {
                document.getElementById('rec_codigo').value = data.codigo;
                document.getElementById('rec_expira').textContent =
                    'Válido por ' + data.minutos + ' minutos. Expira el ' + data.expira + '.';
                document.getElementById('rec_caja_codigo').style.display = 'block';
                if (window.lucide) lucide.createIcons();
            } else {
                var motivo = 'No se pudo generar el código.';
                if (data && data.message) {
                    motivo = data.message;
                } else if (data && data.error) {
                    motivo = data.error;
                }
                document.getElementById('rec_cargando').innerHTML =
                    '<span style="color: var(--error);">' + motivo + '</span>';
            }
        })
        .catch(function () {
            document.getElementById('rec_cargando').innerHTML =
                '<span style="color: var(--error);">Error de conexión con el servidor.</span>';
        });
    }

    function copiarCodigo() {
        var input = document.getElementById('rec_codigo');
        input.select();
        input.setSelectionRange(0, 99999);

        try {
            document.execCommand('copy');
            if (window.lucide) lucide.createIcons();
        } catch (e) {
            input.focus();
        }
    }

    function cerrarModalCodigo() {
        document.getElementById('modal-codigo-recuperacion').style.display = 'none';
    }

    document.addEventListener('click', function (event) {
        var modal = document.getElementById('modal-codigo-recuperacion');
        if (event.target === modal) {
            cerrarModalCodigo();
        }
    });
    </script>
</body>
</html>