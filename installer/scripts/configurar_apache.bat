@echo off
setlocal EnableExtensions EnableDelayedExpansion

set "DRFP_DIR=%~dp0.."
for %%I in ("%DRFP_DIR%") do set "DRFP_DIR=%%~fI"
set "XAMPP_DIR=%DRFP_DIR%\xampp"
set "LOG=%DRFP_DIR%\instalacion.log"
set "XAMPP_URL=%DRFP_DIR:\=/%/xampp"
set "APP_URL=%DRFP_DIR:\=/%/app"
set "VHOST_SRC=%~dp0httpd-drfp-vhost.conf"
set "VHOST_DST=%XAMPP_DIR%\apache\conf\extra\httpd-vhosts.conf"

call :log ==================================================
call :log Configurando Apache para el Sistema DRFP...
call :log ==================================================

REM --- Advertir si el puerto 80 ya esta ocupado por otro proceso ---
for /f "tokens=5" %%p in ('netstat -a -n -p tcp ^| findstr /R /C:":80 .*LISTENING"') do (
    call :log ADVERTENCIA: el puerto 80 esta en uso por el proceso con PID %%p. Si el servidor no inicia, libere el puerto o revise instalacion.log
)

REM --- Asegurar que httpd.conf incluye httpd-vhosts.conf ---
findstr /C:"Include conf/extra/httpd-vhosts.conf" "%XAMPP_DIR%\apache\conf\httpd.conf" >nul 2>&1
if errorlevel 1 (
    echo Include conf/extra/httpd-vhosts.conf>>"%XAMPP_DIR%\apache\conf\httpd.conf"
    call :log httpd.conf: se agrego la inclusion de httpd-vhosts.conf
)

REM --- Generar el VirtualHost del sistema a partir de la plantilla ---
if not exist "%VHOST_SRC%" (
    call :log ERROR: no se encontro la plantilla httpd-drfp-vhost.conf
    exit /b 1
)

powershell -NoProfile -ExecutionPolicy Bypass -Command "$c=[IO.File]::ReadAllText('%VHOST_SRC:\=\\%');$c=$c.Replace('__RUTA_APP__','%APP_URL:\=\\%').Replace('__RUTA_XAMPP__','%XAMPP_URL:\=\\%');[IO.File]::WriteAllText('%VHOST_DST:\=\\%',$c,[Text.Encoding]::ASCII)" >>"%LOG%" 2>&1
if errorlevel 1 (
    call :log ERROR: no se pudo generar el VirtualHost
    exit /b 1
)
call :log VirtualHost generado: %VHOST_DST%

REM --- Directorios necesarios para Apache y MySQL ---
if not exist "%XAMPP_DIR%\tmp" mkdir "%XAMPP_DIR%\tmp"
if not exist "%XAMPP_DIR%\apache\logs" mkdir "%XAMPP_DIR%\apache\logs"
call :log Configuracion de Apache completada
exit /b 0

:log
for /f "tokens=1-2 delims=." %%a in ("%TIME: =0%") do set "HORA=%%a"
echo [%DATE% %HORA%] %* >>"%LOG%"
echo %*
exit /b 0
