<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acceso Denegado</title>
    <link rel="icon" href="{{ asset('img/library.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
    <script src="{{ asset('js/lucide.min.js') }}"></script>
</head>
<body class="error-body">

    <div class="error-container">
        <div class="error-badge badge-403">
            <i data-lucide="shield-alert" size="42"></i>
        </div>
        
        <h1 class="error-code code-403">403</h1>
        <h2 class="error-title">Acceso Denegado</h2>
        
        <p class="error-message">
            No tienes los permisos necesarios para acceder a esta sección o realizar esta acción dentro del sistema.
        </p>

        <div class="error-actions">
            <button onclick="window.history.back()" class="btn-error-back">
                <i data-lucide="arrow-left" size="18"></i> Regresar
            </button>
            <a href="{{ url('/inicio') }}" class="btn-error-home">
                <i data-lucide="home" size="18"></i> Ir al Inicio
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>