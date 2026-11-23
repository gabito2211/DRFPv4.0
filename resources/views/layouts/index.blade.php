<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Biblioteca Dr. Félix Pifano</title>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <link rel="icon" href="{{ asset('img/library.png')}}" type="image/png">
    <link rel="stylesheet" href="{{ asset('/css/estilos_generales.css')}}">
    <link rel="stylesheet" href="{{ asset('/css/login.css')}}">
</head>
<body>

    <div class="top-right-select">
        <label for="sede_login">
            <i data-lucide="building-2" size="16"></i>
        </label>
        <select id="sede_login" name="sede_id" form="login-form">
            
            @if(isset($sedes))
                @foreach($sedes as $sede)
                    <option value="{{ $sede->id }}" data-nombre="{{ $sede->biblioteca }}" data-comunidad="{{ $sede->comunidad }}">{{ $sede->biblioteca }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i data-lucide="library" size="48"></i>
                </div>
                <h1>Bienvenido</h1>
                <p>Sistema de Gestión Bibliotecaria</p>
                <!-- Asignamos ID al sub-título de la biblioteca -->
                <p id="titulo-biblioteca" style="font-weight: bold; color: var(--accent-color); font-size: 0.85rem; margin-top: 4px;"></p>
            </div>

            @if(session('error') || $errors->any() || request()->has('error'))
            <div id="error-box" class="error-msg" style="display: flex;">
                <i data-lucide="alert-circle" size="16"></i>
                <span>
                    @if(session('error'))
                        {{ session('error') }}
                    @elseif($errors->any())
                        {{ $errors->first() }}
                    @else
                        Usuario o contraseña incorrectos.
                    @endif
                </span>
            </div>
            @endif

            <form id="login-form" action="{{ route('login.procesar') }}" method="POST">
                {{ csrf_field() }} 
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-wrapper">  
                        <input type="email" id="email" name="email" placeholder="Ingresa tu correo" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper" style="position: relative; display: flex; align-items: center;">
                        <input  type="password" id="password" name="password" placeholder="••••••••" required style="width: 100%; padding-right: 40px;">
                        <button type="button" id="togglePassword" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; color: var(--text-muted, #64748b); padding: 0; display: flex; align-items: center;">
                            <i data-lucide="eye" id="eyeIcon" size="18"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Iniciar Sesión
                    <i data-lucide="log-in" size="18"></i>
                </button>
            </form>

            <a href="{{ route('recuperar.contrasena') }}" style="display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 14px; font-size: 0.85rem; color: var(--accent-color); text-decoration: none;">
                <i data-lucide="key-round" size="14"></i>
                ¿Olvidaste tu contraseña?
            </a>

            <!-- Asignamos ID al footer -->
            <div class="login-footer" id="footer-biblioteca">
                &copy; {{ date('Y') }} - Biblioteca Pública Central "Dr. Félix Pifano"
            </div>
        </div>
    </div>

    <script>
        // Inicializar iconos
        if (window.lucide) {
            lucide.createIcons();
        }

        // Lógica para detectar errores en la URL
        const params = new URLSearchParams(window.location.search);
        if (params.has('error')) {
            const errorBox = document.getElementById('error-box');
            if(errorBox) errorBox.style.display = 'flex';
        }

        // --- CAMBIO DINÁMICO DE TÍTULOS Y FONDO ---
        const selectSede = document.getElementById('sede_login');
        const tituloBiblioteca = document.getElementById('titulo-biblioteca');
        const footerBiblioteca = document.getElementById('footer-biblioteca');

        // Fondos del login: la sede principal conserva la imagen original,
        // el resto usa la foto de su comunidad (public/img/comunidades)
        const FONDO_PRINCIPAL = '{{ asset("img/biblioteca.jpg") }}';
        const CARPETA_COMUNIDADES = '{{ asset("img/comunidades") }}/';
        const GRADIENTE_LOGIN = 'linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75))';

        function establecerFondo(rutaImagen) {
            document.body.style.backgroundImage = GRADIENTE_LOGIN + ", url('" + rutaImagen + "')";
        }

        function cambiarFondoPorComunidad(rutaDeseada) {
            const precarga = new Image();
            precarga.onload = function () {
                establecerFondo(rutaDeseada);
            };
            precarga.onerror = function () {
                // Si la imagen de la comunidad aún no existe, volvemos a la principal
                establecerFondo(FONDO_PRINCIPAL);
            };
            precarga.src = rutaDeseada;
        }

        if (selectSede) {
            selectSede.addEventListener('change', function() {
                // Obtener la opción seleccionada
                const selectedOption = this.options[this.selectedIndex];
                const nombreSede = selectedOption.getAttribute('data-nombre') || 'Dr. Félix Pifano';
                const comunidad = selectedOption.getAttribute('data-comunidad');
                const sedeId = selectedOption.value;

                // Actualizar el título debajo de "Sistema de Gestión Bibliotecaria"
                tituloBiblioteca.textContent = nombreSede;

                // Actualizar el texto del pie de página
                const anioActual = new Date().getFullYear();
                footerBiblioteca.innerHTML = `&copy; ${anioActual} - ${nombreSede}`;

                // Actualizar el fondo según la comunidad
                if (sedeId === '1' || !comunidad) {
                    // La sede principal siempre conserva su imagen original
                    establecerFondo(FONDO_PRINCIPAL);
                } else {
                    cambiarFondoPorComunidad(CARPETA_COMUNIDADES + comunidad + '.jpg');
                }
            });
        }

        // Lógica para mostrar / ocultar contraseña
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                // Alternar el tipo de input
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Cambiar el icono entre 'eye' y 'eye-off'
                const icon = document.getElementById('eyeIcon');
                if (type === 'text') {
                    icon.setAttribute('data-lucide', 'eye-off');
                } else {
                    icon.setAttribute('data-lucide', 'eye');
                }

                // Re-renderizar el icono con Lucide
                if (window.lucide) {
                    lucide.createIcons();
                }
            });
        }

        // Indicador de carga en el botón al iniciar sesión
        const loginFormCarga = document.getElementById('login-form');
        if (loginFormCarga) {
            loginFormCarga.addEventListener('submit', function () {
                const btn = loginFormCarga.querySelector('.btn-login');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('cargando');
                    btn.innerHTML = '<span class="btn-spinner"></span> Iniciando sesión...';
                }
            });
        }

    </script>
</body>
</html>
<!--quisiera declarar mi amor por la chinita de san jose pero se declarar rutas de laravel-->