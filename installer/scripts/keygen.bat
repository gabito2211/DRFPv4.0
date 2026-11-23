@echo off
setlocal EnableExtensions

set "DRFP_DIR=%~dp0.."
for %%I in ("%DRFP_DIR%") do set "DRFP_DIR=%%~fI"
set "APP_DIR=%DRFP_DIR%\app"
set "PHP=%DRFP_DIR%\xampp\php\php.exe"
set "LOG=%DRFP_DIR%\instalacion.log"

call :log Generando clave de aplicacion (APP_KEY) si es necesario...

if not exist "%APP_DIR%\.env" (
    call :log ERROR: no existe el archivo .env en %APP_DIR%
    exit /b 1
)

findstr /B /C:"APP_KEY=base64" "%APP_DIR%\.env" >nul 2>&1
if not errorlevel 1 (
    call :log APP_KEY ya esta definida, no se modifica
    exit /b 0
)

cd /d "%APP_DIR%"
"%PHP%" artisan key:generate >>"%LOG%" 2>&1
if errorlevel 1 (
    call :log ERROR: fallo php artisan key:generate
    exit /b 1
)
call :log APP_KEY generada correctamente
exit /b 0

:log
for /f "tokens=1-2 delims=." %%a in ("%TIME: =0%") do set "HORA=%%a"
echo [%DATE% %HORA%] %* >>"%LOG%"
echo %*
exit /b 0
