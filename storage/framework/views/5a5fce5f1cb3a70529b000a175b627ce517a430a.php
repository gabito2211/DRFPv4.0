<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respaldo del Sistema - Biblioteca Dr. Félix Pifano</title>
    <script src="<?php echo e(asset('js/lucide.min.js')); ?>"></script>
    <link rel="icon" href="<?php echo e(asset('img/library.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/plantilla.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos_generales.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilo_tablas.css')); ?>">
</head>

<body>
<?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('layouts.mensajes', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<div id="main-wrapper">
    <main id="main-content">
        <div class="header-acciones">
            <div>
                <h1>Respaldo del Sistema</h1>
                <p style="color: var(--texto-suave);">Exportación e importación de los datos del sistema en formato SQL.</p>
            </div>
        </div>

        <!-- ================= EXPORTAR ================= -->
        <div class="contenedor-form" style="max-width: 100%;">
            <div class="titulo-form">
                <h2>Exportar Respaldo</h2>
                <i data-lucide="database-backup" style="color: var(--primario); width: 28px; height: 28px;"></i>
            </div>

            <?php if($esPrincipal): ?>
                <p style="margin-bottom: 15px;">Como <strong>Sede Principal</strong> puedes exportar <strong>todo el sistema</strong>: catálogos (sedes, autores), bitácora y los datos de todas las sedes (<?php echo e(count($tablasAlcance)); ?> tablas).</p>
            <?php else: ?>
                <p style="margin-bottom: 15px;">Como administrador de sede exportarás <strong>únicamente los datos de tu sede</strong> (<?php echo e(count($tablasAlcance)); ?> tablas). Los catálogos globales (sedes, autores) y la bitácora solo los maneja la Sede Principal.</p>
            <?php endif; ?>

            <div class="grupo-campo" style="margin-bottom: 20px;">
                <label style="font-weight: 600;">Tablas incluidas en tu respaldo:</label>
                <p style="color: var(--texto-suave); font-size: 0.9rem; line-height: 1.8;">
                    <?php echo e(implode(' · ', $tablasAlcance)); ?>

                </p>
            </div>

            <div class="fila-botones">
                <a href="<?php echo e(route('respaldo.exportar')); ?>" class="btn btn-azul" title="Descargar respaldo en SQL">
                    <i data-lucide="database-backup"></i> Exportar Respaldo (.sql)
                </a>
            </div>
        </div>

        <!-- ================= IMPORTAR ================= -->
        <div class="contenedor-form" style="max-width: 100%; margin-top: 30px;">
            <div class="titulo-form">
                <h2>Importar Respaldo</h2>
                <i data-lucide="file-up" style="color: var(--primario); width: 28px; height: 28px;"></i>
            </div>

            <?php if($esPrincipal): ?>
                <p style="margin-bottom: 15px;">Puedes importar <strong>todo el sistema</strong>. Los registros con el mismo identificador (cota, cédula, email, id...) serán <strong>actualizados</strong> con los datos del archivo; los demás se crearán nuevos. No se elimina ningún registro existente.</p>
            <?php else: ?>
                <p style="margin-bottom: 15px;">Solo se aceptan archivos con <strong>datos de tu sede (sede <?php echo e(Auth::user()->sede_id); ?>)</strong>: cada registro del archivo debe llevar <code>sede_id = <?php echo e(Auth::user()->sede_id); ?></code>. Cualquier dato de otra sede será rechazado automáticamente.</p>
            <?php endif; ?>

            <div class="opciones-grupo" style="background: var(--fondo-sitio); border-left: 4px solid var(--primario); border-radius: 6px; padding: 12px 15px; margin-bottom: 20px;">
                <p style="margin: 0; font-size: 0.9rem;"><strong>Importante:</strong> el archivo debe haber sido generado por este módulo de exportación (formato INSERT con ON DUPLICATE KEY UPDATE). Los archivos de otras herramientas (mysqldump, phpMyAdmin) serán rechazados por seguridad.</p>
            </div>

            <form action="<?php echo e(route('respaldo.importar')); ?>" method="POST" enctype="multipart/form-data" novalidate>
                <?php echo e(csrf_field()); ?>

                <div class="grupo-campo ancho-total">
                    <label for="archivo_sql">Archivo de respaldo (.sql o .txt)</label>
                    <input type="file" name="archivo_sql" id="archivo_sql" accept=".sql,.txt" class="entrada" required style="padding: 10px;">
                </div>

                <div class="fila-botones">
                    <button type="submit" class="btn btn-azul">
                        <i data-lucide="upload"></i> Importar Respaldo
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>

<script src="<?php echo e(asset('js/plantilla.js')); ?>"></script>
<script src="<?php echo e(asset('js/validaciones.js')); ?>"></script>
<script>
    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    });
</script>
</body>
</html>
