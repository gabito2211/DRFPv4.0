@echo off
setlocal EnableExtensions

set "URL=http://localhost/"
set "LOG=%~dp0instalacion.log"

REM ------------------------------------------------------
REM Iniciar los servicios si estan detenidos.
REM Si el usuario no tiene permisos, se reintenta con
REM elevacion (aparece el aviso de Control de cuentas)
REM ------------------------------------------------------
sc query DRFP-MySQL | findstr /C:"RUNNING" >nul 2>&1
if errorlevel 1 (
    net start DRFP-MySQL >nul 2>&1
    if errorlevel 1 powershell -NoProfile -ExecutionPolicy Bypass -Command "Start-Process -FilePath 'net.exe' -ArgumentList 'start','DRFP-MySQL' -Verb RunAs -Wait" >nul 2>&1
)

sc query DRFP-Apache | findstr /C:"RUNNING" >nul 2>&1
if errorlevel 1 (
    net start DRFP-Apache >nul 2>&1
    if errorlevel 1 powershell -NoProfile -ExecutionPolicy Bypass -Command "Start-Process -FilePath 'net.exe' -ArgumentList 'start','DRFP-Apache' -Verb RunAs -Wait" >nul 2>&1
)

REM ------------------------------------------------------
REM Esperar hasta que el sistema responda (max 30 intentos)
REM ------------------------------------------------------
set /a INTENTOS=0

:esperar
powershell -NoProfile -ExecutionPolicy Bypass -Command "try{$r=(New-Object Net.WebClient).DownloadString('%URL%');exit 0}catch{exit 1}" >nul 2>&1
if not errorlevel 1 goto :listo
set /a INTENTOS+=1
if %INTENTOS% GEQ 30 goto :fallo
ping -n 2 127.0.0.1 >nul
goto :esperar

:listo
start "" "%URL%"
exit /b 0

:fallo
powershell -NoProfile -ExecutionPolicy Bypass -Command "(New-Object -ComObject Wscript.Shell).Popup('El Sistema DRFP no respondio despues de varios intentos.'+[char]13+[char]10+'Verifique los servicios DRFP-Apache y DRFP-MySQL (panel de servicios de Windows) o ejecute este acceso directo como administrador.',0,'Sistema DRFP',16)" >nul 2>&1
exit /b 1
