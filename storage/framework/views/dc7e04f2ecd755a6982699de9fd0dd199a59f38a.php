<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada</title>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/error.css')); ?>">
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
</head>
<body class="error-body">

    <div class="error-container">
        <div class="error-badge badge-404">
            <i data-lucide="file-question" size="42"></i>
        </div>
        
        <h1 class="error-code code-404">404</h1>
        <h2 class="error-title">Página no encontrada</h2>
        
        <p class="error-message">
            La página que estás buscando no existe, ha sido movida o el enlace ingresado es incorrecto.
        </p>

        <div class="error-actions">
            <button onclick="window.history.back()" class="btn-error-back">
                <i data-lucide="arrow-left" size="18"></i> Regresar
            </button>
            <a href="<?php echo e(url('/inicio')); ?>" class="btn-error-home">
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