@echo off
setlocal EnableExtensions EnableDelayedExpansion

set "DRFP_DIR=%~dp0.."
for %%I in ("%DRFP_DIR%") do set "DRFP_DIR=%%~fI"
set "XAMPP_DIR=%DRFP_DIR%\xampp"
set "APP_DIR=%DRFP_DIR%\app"
set "LOG=%DRFP_DIR%\instalacion.log"
set "OKFILE=%DRFP_DIR%\instalacion_ok.txt"
set "MYSQL=%XAMPP_DIR%\mysql\bin\mysql.exe"
set "MYSQLADMIN=%XAMPP_DIR%\mysql\bin\mysqladmin.exe"
set "MYSQLD=%XAMPP_DIR%\mysql\bin\mysqld.exe"
set "HTTPD=%XAMPP_DIR%\apache\bin\httpd.exe"

if exist "%OKFILE%" del "%OKFILE%" >nul 2>&1

call :log ==================================================
call :log Instalando servicios del Sistema DRFP
call :log ==================================================

REM ------------------------------------------------------
REM Visual C++ Redistributable 2012 (VC11), requerido por
REM PHP 5.6 y Apache de XAMPP en instalaciones limpias
REM ------------------------------------------------------
if not exist "%SystemRoot%\System32\msvcr110.dll" if not exist "%SystemRoot%\SysWOW64\msvcr110.dll" (
    if exist "%DRFP_DIR%\recursos\vcredist_x86.exe" (
        call :log Instalando Visual C++ Redistributable 2012 x86...
        "%DRFP_DIR%\recursos\vcredist_x86.exe" /install /passive /norestart >>"%LOG%" 2>&1
        call :log Visual C++ Redistributable 2012 instalado
    ) else (
        call :log ADVERTENCIA: falta msvcr110.dll y no se encuentra recursos\vcredist_x86.exe
    )
)

REM ------------------------------------------------------
REM Servicio MySQL (DRFP-MySQL)
REM ------------------------------------------------------
sc query DRFP-MySQL >nul 2>&1
if not errorlevel 1 (
    call :log Eliminando servicio DRFP-MySQL existente...
    net stop DRFP-MySQL >nul 2>&1
    "%MYSQLD%" --remove DRFP-MySQL >>"%LOG%" 2>&1
)

call :log Instalando servicio DRFP-MySQL...
"%MYSQLD%" --install DRFP-MySQL --defaults-file="%XAMPP_DIR%\mysql\bin\my.ini" >>"%LOG%" 2>&1
if errorlevel 1 (
    call :log ERROR: no se pudo instalar el servicio DRFP-MySQL
    goto :resumen
)
sc config DRFP-MySQL start= auto >>"%LOG%" 2>&1
call :log Iniciando servicio DRFP-MySQL...
net start DRFP-MySQL >>"%LOG%" 2>&1

set /a INTENTOS=0
:esperar_mysql
"%MYSQLADMIN%" -u root ping >nul 2>&1
if not errorlevel 1 goto :mysql_listo
set /a INTENTOS+=1
if %INTENTOS% GEQ 20 (
    call :log ERROR: el servicio DRFP-MySQL no respondio
    goto :resumen
)
ping -n 2 127.0.0.1 >nul
goto :esperar_mysql

:mysql_listo
call :log Servicio DRFP-MySQL iniciado

REM ------------------------------------------------------
REM Importar base de datos solo si esta vacia (en una
REM actualizacion se conservan los datos existentes)
REM ------------------------------------------------------
"%MYSQL%" -u root -e "USE drfp; SELECT COUNT(*) FROM users;" >nul 2>&1
if errorlevel 1 (
    call :log Creando base de datos drfp e importando datos...
    "%MYSQL%" -u root -e "CREATE DATABASE IF NOT EXISTS drfp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" >>"%LOG%" 2>&1
    "%MYSQL%" -u root --default-character-set=utf8mb4 drfp < "%APP_DIR%\database\drfp.sql" >>"%LOG%" 2>&1
    if errorlevel 1 (
        call :log ERROR: fallo la importacion de la base de datos
        goto :resumen
    )
    call :log Base de datos drfp importada con datos iniciales
) else (
    call :log La base de datos drfp ya contiene datos, se conservan
)

REM ------------------------------------------------------
REM Servicio Apache (DRFP-Apache)
REM ------------------------------------------------------
sc query DRFP-Apache >nul 2>&1
if not errorlevel 1 (
    call :log Eliminando servicio DRFP-Apache existente...
    net stop DRFP-Apache >nul 2>&1
    "%HTTPD%" -k uninstall -n DRFP-Apache >>"%LOG%" 2>&1
)

call :log Instalando servicio DRFP-Apache...
"%HTTPD%" -k install -n DRFP-Apache >>"%LOG%" 2>&1
if errorlevel 1 (
    call :log ERROR: no se pudo instalar el servicio DRFP-Apache
    goto :resumen
)
sc config DRFP-Apache start= auto >>"%LOG%" 2>&1
call :log Iniciando servicio DRFP-Apache...
net start DRFP-Apache >>"%LOG%" 2>&1

ping -n 4 127.0.0.1 >nul
sc query DRFP-Apache | findstr /C:"RUNNING" >nul 2>&1
if errorlevel 1 (
    call :log ERROR: el servicio DRFP-Apache no esta en ejecucion. Revise apache\logs\error.log
    goto :resumen
)
call :log Servicio DRFP-Apache iniciado

REM ------------------------------------------------------
REM Resumen final
REM ------------------------------------------------------
:resumen
set SERVICIOS_OK=1
sc query DRFP-MySQL | findstr /C:"RUNNING" >nul 2>&1
if errorlevel 1 set SERVICIOS_OK=0
sc query DRFP-Apache | findstr /C:"RUNNING" >nul 2>&1
if errorlevel 1 set SERVICIOS_OK=0

if "%SERVICIOS_OK%"=="1" (
    echo ok>"%OKFILE%"
    call :log Instalacion de servicios completada. Ambos servicios estan en ejecucion e iniciaran automaticamente con el equipo.
    exit /b 0
)
call :log ERROR: no todos los servicios quedaron activos. Revise %LOG%
exit /b 1

:log
for /f "tokens=1-2 delims=." %%a in ("%TIME: =0%") do set "HORA=%%a"
echo [%DATE% %HORA%] %* >>"%LOG%"
echo %*
exit /b 0
