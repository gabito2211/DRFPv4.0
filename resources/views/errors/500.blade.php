<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Error Interno del Servidor</title>
    <link rel="icon" href="{{ asset('img/library.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
    <script src="{{ asset('js/lucide.min.js') }}"></script>
</head>
<body class="error-body">

    <div class="error-container">
        <div class="error-badge badge-500">
            <i data-lucide="server-off" size="42"></i>
        </div>
        
        <h1 class="error-code code-500">500</h1>
        <h2 class="error-title">Error en el Servidor</h2>
        
        <p class="error-message">
            Ha ocurrido un problema inesperado en el sistema. Por favor, intenta de nuevo o ponte en contacto con el administrador.
        </p>

        <div class="error-actions">
            <button onclick="window.location.reload()" class="btn-error-back">
                <i data-lucide="refresh-cw" size="18"></i> Reintentar
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