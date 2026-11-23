<?php

use App\Http\Controllers\SedeController;
use App\Sede;

Route::get('/', function () {
    $sedes = Sede::all();
    return view('layouts.index', compact('sedes'));
})->name('login')->middleware('guest');  

Route::post('/login', 'UsuarioController@login')->name('login.procesar');  
Route::post('/logout', 'UsuarioController@logout')->name('logout');  

Route::get('/recuperar-contrasena', 'RecuperacionController@mostrarFormulario')->name('recuperar.contrasena');
Route::post('/recuperar-contrasena', 'RecuperacionController@recuperar')->name('recuperar.contrasena.enviar');  

Route::group(['middleware' => ['auth', 'prevent-back-history']], function () {  

    // -------------------------------------------------------------
    // ACCESO: Almacenista (3), Recepcionista (2), Administrador (1)
    // -------------------------------------------------------------
    Route::group(['middleware' => ['role:1,2,3']], function () {
        Route::get('/inicio', 'InicioController@index')->name('inicio');
        
                // Búsqueda AJAX de lector por Cédula
        Route::get('/buscar-lector/{cedula}', 'InicioController@buscarLector');

        // Búsqueda AJAX de libro por Cota
        Route::get('/buscar-libro/{cota}', 'InicioController@buscarLibro');

        // Rutas de préstamos internos
        Route::post('/prestamo-interno/guardar', 'InicioController@guardarPrestamoInterno')->name('prestamo_interno.store');
        Route::put('/prestamo-interno/devolver/{id}', 'InicioController@devolverPrestamoInterno')->name('prestamo_interno.devolver');

        // Inventario
        Route::get('/inventario', 'InventarioController@index')->name('inventario.index');  
        Route::post('/inventario', 'InventarioController@store')->name('inventario.store');  
        Route::put('/inventario/{cota}', 'InventarioController@update')->name('inventario.update');  
        Route::delete('/inventario/{cota}', 'InventarioController@destroy')->name('inventario.destroy');  
        Route::get('/inventario/verificar/{cota}', 'InventarioController@verificarDisponibilidad')->name('inventario.verificar');  
        Route::get('/inventario/{cota}/historial', 'InventarioController@historial')->name('inventario.historial');  
        Route::get('/inventario/reportes', 'ReporteInventarioController@generar')->name('inventario.reportes');  
        Route::get('/inventario/exportar-sql', 'InventarioController@exportarSql')->name('inventario.exportar.sql');
        Route::post('/inventario/importar-sql', 'InventarioController@importarSql')->name('inventario.importar.sql');  

        // Autores (catálogo compartido usado por el select del formulario de Inventario)
        Route::get('/autores', 'AutorController@index')->name('autores.index');
        Route::post('/autores', 'AutorController@store')->name('autores.store');
        Route::post('/autores/rapido', 'AutorController@storeAjax')->name('autores.store-ajax');
        Route::get('/autores/{id}', 'AutorController@show')->name('autores.show');
        Route::put('/autores/{id}', 'AutorController@update')->name('autores.update');
        Route::delete('/autores/{id}', 'AutorController@destroy')->name('autores.destroy');

        // Estanterías: registro rápido vía AJAX desde el formulario de Inventario
        Route::post('/inventario/estanterias/rapida', 'EstanteriaController@storeAjax')->name('estanterias.store-ajax');

        // Pasillos: registro rápido vía AJAX desde el modal de estanterías de Inventario
        Route::post('/inventario/pasillos/rapida', 'PasilloController@storeAjax')->name('pasillos.store-ajax');

        // Carnetización
        Route::get('/carnets', 'CarnetController@index')->name('carnets.index');  
        Route::get('/carnet', 'CarnetController@index');  
        Route::post('/carnets/registrar', 'CarnetController@store')->name('carnets.registrar');  
        Route::get('/carnets/{id}/editar', 'CarnetController@editar')->name('carnets.editar');  
        Route::put('/carnets/{id}', 'CarnetController@actualizar')->name('carnets.actualizar');  
        Route::post('/carnets/{id}/renovar', 'CarnetController@renovar')->name('carnets.renovar');  
        Route::post('/carnets/eliminar/{id}', 'CarnetController@destroy')->name('carnets.destroy');  
        Route::get('/carnets/{id}/datos', 'CarnetController@datos')->name('carnets.datos');  
        Route::get('/carnet/imprimir/{id}', 'CarnetController@imprimir')->name('carnet.imprimir');  
        Route::post('/carnets/{id}/desbloquear', 'CarnetController@desbloquear')->name('carnets.desbloquear');  

        // Préstamos y Sanciones
    
        Route::get('/prestamos', 'PrestamoController@index')->name('prestamos.index'); 
        // Reportes de préstamos (estilo inventario, sin DomPDF)
Route::get('/prestamo/reportes', 'ReportePrestamoController@generar')->name('prestamo.reportes');
Route::get('/prestamo/reporte/pdf', function () {
    return redirect()->route('prestamo.reportes');
})->name('prestamo.reporte.pdf'); // Alias de compatibilidad
        Route::get('/prestamo', 'PrestamoController@index')->name('prestamo.index');  
        Route::post('/prestamo', 'PrestamoController@store')->name('prestamo.store');  
        Route::get('/prestamo/{id}/edit', 'PrestamoController@edit')->name('prestamo.edit');  
        Route::put('/prestamo/{id}', 'PrestamoController@update')->name('prestamo.update');  
        Route::delete('/prestamo/{id}', 'PrestamoController@destroy')->name('prestamo.destroy');  
        Route::post('/prestamo/{id}/entregar', 'PrestamoController@entregar')->name('prestamo.entregar');  
        Route::get('/prestamo/verificar-lector/{cedula}', 'PrestamoController@verificarLectorPorCedula')->name('prestamo.verificar-lector');  
        Route::get('/sanciones', 'SancionController@index')->name('sanciones.index');  
        Route::post('/sanciones/{id}/solventar', 'SancionController@solventar')->name('sanciones.solventar');  
        Route::get('/sanciones/{id}/datos', 'SancionController@datos')->name('sanciones.datos');  
        Route::post('/carnet/{id}/desbloquear', 'PrestamoController@desbloquearCarnetManualmente')->name('carnet.desbloquear');  
    });

    // -------------------------------------------------------------
    // ACCESO: Recepcionista (2) y Administrador (1)
    // -------------------------------------------------------------
    Route::group(['middleware' => ['role:1,2']], function () {
        // Registro de Visitas
        Route::get('/registro', 'VisitasController@index')->name('visitantes.index');  
        Route::post('/visitantes/registrar', 'VisitasController@store')->name('visitantes.registrar');  
        Route::get('/buscar-persona/{cedula}', 'VisitasController@buscar')->name('persona.buscar');  
        Route::get('/persona/{id}/datos', 'VisitasController@datosPersona')->name('persona.datos');  
        Route::get('/persona/{id}/editar', 'VisitasController@editarPersona')->name('persona.editar');  
        Route::put('/persona/{id}', 'VisitasController@actualizarPersona')->name('persona.actualizar');  
        Route::delete('/persona/{id}', 'VisitasController@eliminarPersona')->name('persona.eliminar');  

        // Visitas Guiadas
        Route::get('/visita', 'VisitasGuiadasController@index')->name('guiadas.index');  
        Route::post('/visita/registrar', 'VisitasGuiadasController@store')->name('guiadas.store');  
        Route::get('/visita/{id}/datos', 'VisitasGuiadasController@datos')->name('guiadas.datos');  
        Route::put('/visita/{id}', 'VisitasGuiadasController@update')->name('guiadas.update');  
    });

    // -------------------------------------------------------------
    // ACCESO EXCLUSIVO: Administrador (1)
    // -------------------------------------------------------------
    Route::group(['middleware' => ['role:1']], function () {
        // Ubicación: Salas
        Route::get('/ubicacion/salas', 'SalaController@index')->name('ubicacion.salas.index');
        Route::post('/ubicacion/salas', 'SalaController@store')->name('ubicacion.salas.store');
        Route::get('/ubicacion/salas/{id}', 'SalaController@show')->name('ubicacion.salas.show');
        Route::put('/ubicacion/salas/{id}', 'SalaController@update')->name('ubicacion.salas.update');
        Route::delete('/ubicacion/salas/{id}', 'SalaController@destroy')->name('ubicacion.salas.destroy');

        // Ubicación: Pasillos
        Route::get('/ubicacion/pasillos', 'PasilloController@index')->name('ubicacion.pasillos.index');
        Route::post('/ubicacion/pasillos', 'PasilloController@store')->name('ubicacion.pasillos.store');
        Route::get('/ubicacion/pasillos/{id}', 'PasilloController@show')->name('ubicacion.pasillos.show');
        Route::put('/ubicacion/pasillos/{id}', 'PasilloController@update')->name('ubicacion.pasillos.update');
        Route::delete('/ubicacion/pasillos/{id}', 'PasilloController@destroy')->name('ubicacion.pasillos.destroy');

        // Ubicación: Estanterías
        Route::get('/ubicacion/estanterias', 'EstanteriaController@index')->name('ubicacion.estanterias.index');
        Route::post('/ubicacion/estanterias', 'EstanteriaController@store')->name('ubicacion.estanterias.store');
        Route::get('/ubicacion/estanterias/{id}', 'EstanteriaController@show')->name('ubicacion.estanterias.show');
        Route::put('/ubicacion/estanterias/{id}', 'EstanteriaController@update')->name('ubicacion.estanterias.update');
        Route::delete('/ubicacion/estanterias/{id}', 'EstanteriaController@destroy')->name('ubicacion.estanterias.destroy');

        // Sedes
        Route::get('/sedes', 'SedeController@index')->name('sedes.index');  
        Route::post('/sedes', 'SedeController@store')->name('sedes.store');  
        Route::put('/sedes/{id}', 'SedeController@update')->name('sedes.update');  
        Route::delete('/sedes/{id}', 'SedeController@destroy')->name('sedes.destroy');  

        // Gestión de Usuarios
        Route::get('/usuario', 'UsuarioController@index')->name('usuarios.index');  
        Route::post('/usuario/registrar', 'UsuarioController@store')->name('usuarios.store');  
        Route::put('/usuario/{id}', 'UsuarioController@update')->name('usuarios.update');
        Route::patch('/usuario/{id}/toggle-estado', 'UsuarioController@toggleEstado')->name('usuarios.toggle-estado');  
        Route::delete('/usuario/{id}', 'UsuarioController@destroy')->name('usuarios.destroy');  

        // Recuperación de contraseña
        Route::post('/usuario/{id}/codigo-recuperacion', 'UsuarioController@generarCodigoRecuperacion')->name('usuarios.codigo-recuperacion');  
        Route::delete('/usuario/{id}/codigo-recuperacion', 'UsuarioController@anularCodigos')->name('usuarios.anular-codigos');  

        // Encargados, Actividades y Plan Vacacional
        Route::get('/encargados', 'EncargadoController@index')->name('encargados.index');  
        Route::post('/encargados/registrar', 'EncargadoController@store')->name('encargados.registrar');  
        Route::get('/encargados/buscar/{cedula}', 'EncargadoController@buscar')->name('encargados.buscar');  
        Route::get('/encargados/{id}', 'EncargadoController@show')->name('encargados.show');  
        Route::put('/encargados/{id}', 'EncargadoController@update')->name('encargados.update');  
        Route::delete('/encargados/{id}', 'EncargadoController@destroy')->name('encargados.destroy');  

         // =============================================================
        // PLANES VACACIONALES (usando PlanVacacionalController)
        // =============================================================
        
        // Vista principal de planes (planVacacional.blade.php)
        Route::get('/planes', 'PlanVacacionalController@index')->name('planes.index');
        
        // CRUD de Planes
        Route::post('/planes', 'PlanVacacionalController@storePlan')->name('planes.store');
        Route::get('/planes/{id}', 'PlanVacacionalController@showPlan')->name('planes.show');
        Route::get('/planes/{id}/detalles', 'PlanVacacionalController@detallesPlan')->name('planes.detalles');
        Route::put('/planes/{id}', 'PlanVacacionalController@updatePlan')->name('planes.update');
        Route::delete('/planes/{id}', 'PlanVacacionalController@destroyPlan')->name('planes.destroy');
        Route::post('/planes/{id}/activar', 'PlanVacacionalController@activarPlan')->name('planes.activar');
        Route::get('/reportes/plan-vacacional', 'ReportePlanVacacionalController@reportes')
            ->name('reportes.plan.index');
        Route::get('/reportes/plan-vacacional/csv', 'ReportePlanVacacionalController@exportCsv')
            ->name('reportes.plan.csv');
        Route::get('/reportes/plan-vacacional/pdf', function () {
            return redirect()->route('reportes.plan.index');
        })->name('reportes.plan.pdf');
        // =============================================================
        // ACTIVIDADES (usando ActividadesController)
        // =============================================================
        
        // Vista de gestión de actividades (nuevaActividad.blade.php)
        Route::get('/actividades/plan/{id}', 'ActividadesController@verPorPlan')->name('actividades.plan');
        Route::get('/actividades/nueva', 'ActividadesController@nueva')->name('actividades.nueva');
        
        // CRUD de Actividades
        Route::post('/actividades', 'ActividadesController@store')->name('actividades.store');
        Route::get('/actividades/{id}', 'ActividadesController@show')->name('actividades.show');
        Route::put('/actividades/{id}', 'ActividadesController@update')->name('actividades.update');
        Route::delete('/actividades/{id}', 'ActividadesController@destroy')->name('actividades.destroy');
        Route::get('/actividades/filtrar', 'ActividadesController@filtrar')->name('actividades.filtrar');
        Route::get('/actividades/sede/{id}', 'ActividadesController@getSedeData')->name('actividades.sede');

        // Bitácora del Sistema
        Route::get('/bitacora', 'BitacoraController@index')->name('bitacora.index');

        // Respaldo del Sistema (exportar/importar)
        Route::get('/respaldo', 'RespaldoSistemaController@index')->name('respaldo.index');
        Route::get('/respaldo/exportar', 'RespaldoSistemaController@exportar')->name('respaldo.exportar');
        Route::post('/respaldo/importar', 'RespaldoSistemaController@importar')->name('respaldo.importar');
    });

});