<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Tiempo expirado</title>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/error.css')); ?>">
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
</head>
<body class="error-body">

    <div class="error-container">
        <div class="error-badge badge-419">
            <i data-lucide="timer-off" size="42"></i>
        </div>

        <h1 class="error-code code-419">419</h1>
        <h2 class="error-title">¡Vaya! Parece que el tiempo ha expirado</h2>

        <p class="error-message">
            Por seguridad, tu sesión se cerró por inactividad.
            Vuelve a ingresar al sistema para continuar con tus operaciones.
        </p>

        <div class="error-actions">
            <button onclick="window.history.back()" class="btn-error-back">
                <i data-lucide="rotate-ccw" size="18"></i> Volver a intentar
            </button>
            <a href="<?php echo e(url('/')); ?>" class="btn-error-home">
                <i data-lucide="log-in" size="18"></i> Ir al Login
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
