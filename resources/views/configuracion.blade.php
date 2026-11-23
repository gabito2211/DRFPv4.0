<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - Biblioteca Dr. Félix Pifano</title>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <link rel="icon" href="img/library.png" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/plantilla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos_generales.css') }}">
    <style>
        .config-section {
            margin-bottom: 40px;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            color: var(--sidebar-bg);
        }

        .section-header i {
            color: var(--sidebar-active);
        }

        .card-ajuste {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .avatar-upload {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .avatar-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid var(--sidebar-active);
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .info-label {
            display: block;
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 5px;

        .badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
    font-weight: bold;
                    }
    .badge-rol-1 { background-color: #ffeaa7; color: #d63031; } /* Administrador */
        .badge-rol-2 { background-color: #dfe6e9; color: #2d3436; } /* Recepcionista */
        .badge-rol-3 { background-color: #dff9fb; color: #0984e3; } /* Almacenista */
            
        }
    </style>
</head>
<body>

    <!-- Navegación lateral -->    
    @include('layouts.nav')


    <div id="main-wrapper">
        <main id="main-content">
            
            <!-- Encabezado de la página -->
            <div style="width: 100%; max-width: 900px; margin-bottom: 30px;">
                <h1 style="color: var(--sidebar-bg); font-size: 1.8rem;">Configuración del Sistema</h1>
                <p style="color: #64748b;">Administra los parámetros generales y tu perfil de usuario.</p>
            </div>

            <div class="contenedor-form" style="max-width: 900px; width: 100%;">
                    
                <!-- SECCIÓN 1: AJUSTES DE USUARIO -->
                <div class="config-section">
                    <div class="section-header">
                        <i data-lucide="user-cog"></i>
                        <h3>Mi Perfil de Usuario</h3>
                    </div>
                    
                    <form action="#" method="POST">
                        <div class="avatar-upload">
                            <div class="avatar-preview">
                                <i data-lucide="user" size="40" color="#94a3b8"></i>
                            </div>
                            <div>
                                <label class="btn btn-gris" style="cursor: pointer; font-size: 0.8rem;">
                                    <i data-lucide="camera" size="16"></i> Cambiar Foto
                                    <input type="file" style="display: none;">
                                </label>
                            </div>
                        </div>

                        <div class="rejilla-form">
                            <div class="grupo-campo">
                                <label for="user-name">Nombre y Apellido</label>
                                <input type="text" id="user-name" class="entrada" value="" placeholder="Ej. Juan Pérez">
                            </div>
                            <div class="grupo-campo">
                                <label for="usuario">Nombre de Usuario</label>
                                <input type="text" id="usuario" class="entrada" value="" placeholder="Ej. admin_pifano">
                            </div>
                            <div class="grupo-campo">
                                <label for="user-pass">Nueva Contraseña</label>
                                <input type="password" id="user-pass" class="entrada" placeholder="Dejar en blanco para no cambiar">
                                <span class="info-label">Mínimo 8 caracteres.</span>
                            </div>
                            <div class="grupo-campo">
                                <label for="user-pass-confirm">Confirmar Contraseña</label>
                                <input type="password" id="user-pass-confirm" class="entrada" placeholder="Repita la nueva contraseña">
                            </div>
                        </div>
                        <div class="fila-botones" style="justify-content: flex-start; margin-top: 15px;">
                            <button type="submit" class="btn btn-azul">Guardar Cambios de Perfil</button>
                        </div>
                    </form>
                </div>

                <!-- SECCIÓN 2: PREFERENCIAS DEL SISTEMA
                <div class="config-section" style="margin-bottom: 0;">
                    <div class="section-header">
                        <i data-lucide="settings-2"></i>
                        <h3>Preferencias del Sistema</h3>
                    </div>
                    
                    <div class="card-ajuste">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div>
                                <h4 style="margin: 0;">Copias de Seguridad Automáticas</h4>
                                <p class="info-label">Realizar respaldo de la base de datos cada 24 horas.</p>
                            </div>
                            <input type="checkbox" checked>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                            <div>
                                <h4 style="margin: 0;">Notificaciones de Vencimiento</h4>
                                <p class="info-label">Alertar en el panel principal sobre libros no devueltos.</p>
                            </div>
                            <input type="checkbox" checked>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                            <div>
                                <h4 style="margin: 0; color: var(--error);">Mantenimiento</h4>
                                <p class="info-label">Limpiar registros de visitas antiguos (más de 1 año).</p>
                            </div>
                            <button class="btn btn-gris" style="color: var(--error);">Ejecutar Limpieza</button>
                        </div>
                    </div>
                </div>-->

            </div>
        </main>
    <!-- Pie de página -->    
    @include('layouts.footer')

    
    </div>

    <script src="{{ asset('js/plantilla.js') }}?v={{ filemtime(public_path('js/plantilla.js')) }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>