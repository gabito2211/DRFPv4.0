<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Biblioteca Dr. Félix Pifano</title>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <link rel="icon" href="{{ asset('img/library.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('/css/estilos_generales.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/login.css') }}">

    <style>
        .info-box {
            background: #e8f4fd;
            border: 1px solid #b8daff;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            font-size: 0.85rem;
            line-height: 1.5;
            color: #1a4a7a;
        }
        .info-box i { flex-shrink: 0; margin-top: 2px; }
        .info-box strong { display: block; margin-bottom: 3px; }
        .exito-msg {
            background: #e6f4ea;
            border: 1px solid #a8d9b5;
            color: #137333;
        }
        .btn-secundario {
            width: 100%;
            margin-top: 12px;
            padding: 12px;
            border: 1px solid var(--border-color, #d1d5db);
            background: transparent;
            color: var(--text-muted, #64748b);
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-secundario:hover {
            background: rgba(0,0,0,0.03);
            color: var(--text-main, #1f2937);
        }
        .codigo-input {
            letter-spacing: 3px;
            text-transform: uppercase;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }
        .puntos {
            display: flex;
            gap: 6px;
            align-items: center;
            font-size: 0.85rem;
            color: var(--text-muted, #64748b);
            margin-bottom: 18px;
            padding-left: 4px;
        }
        .puntos i { width: 14px; height: 14px; }
        .paso { color: #9ca3af; }
        .paso.activo { color: #2563eb; font-weight: 600; }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i data-lucide="key-round" size="44"></i>
                </div>
                <h1>Recuperar Contraseña</h1>
                <p>Sistema de Gestión Bibliotecaria</p>
            </div>

            @if(session('error') || $errors->any())
            <div id="error-box" class="error-msg" style="display: flex;">
                <i data-lucide="alert-circle" size="16"></i>
                <span>
                    @if(session('error'))
                        {{ session('error') }}
                    @else
                        {{ $errors->first() }}
                    @endif
                </span>
            </div>
            @endif

            @if(session('success'))
            <div class="error-msg exito-msg" style="display: flex;">
                <i data-lucide="check-circle" size="16"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <div class="info-box">
                <i data-lucide="info" size="16"></i>
                <div>
                    <strong>¿Cómo funciona?</strong>
                    Solicita un código de recuperación al administrador de la biblioteca.
                    El te lo entrega en persona o por teléfono, y con él podrás
                    establecer una contraseña nueva.
                </div>
            </div>

            <form action="{{ route('recuperar.contrasena.enviar') }}" method="POST">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" placeholder="El correo con el que inicias sesión"
                               value="{{ old('email', session('email_recuperacion')) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="codigo">Código de Recuperación</label>
                    <div class="input-wrapper">
                        <input type="text" id="codigo" name="codigo" class="codigo-input"
                               placeholder="XXXX-XXXX" maxlength="9" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <div class="input-wrapper" style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres"
                               minlength="6" required style="width: 100%; padding-right: 40px;">
                        <button type="button" id="togglePassword"
                                style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; color: var(--text-muted, #64748b); padding: 0; display: flex; align-items: center;">
                            <i data-lucide="eye" id="eyeIcon" size="18"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="Repite la contraseña" minlength="6" required>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Cambiar Contraseña
                    <i data-lucide="check" size="18"></i>
                </button>
            </form>

            <button type="button" class="btn-secundario" onclick="window.location.href='{{ route('login') }}'">
                <i data-lucide="arrow-left" size="16"></i>
                Volver al inicio de sesión
            </button>

            <div class="login-footer" id="footer-biblioteca">
                &copy; {{ date('Y') }} - Biblioteca Pública Central "Dr. Félix Pifano"
            </div>
        </div>
    </div>

    <script>
        if (window.lucide) {
            lucide.createIcons();
        }

        const codigoInput = document.getElementById('codigo');
        codigoInput.addEventListener('input', function () {
            let v = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 8);
            if (v.length > 4) {
                v = v.slice(0, 4) + '-' + v.slice(4);
            }
            this.value = v;
        });

        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            icon.setAttribute('data-lucide', type === 'text' ? 'eye-off' : 'eye');
            if (window.lucide) lucide.createIcons();
        });
    </script>
</body>
</html>