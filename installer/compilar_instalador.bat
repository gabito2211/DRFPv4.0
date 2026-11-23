@echo off
setlocal EnableExtensions EnableDelayedExpansion

REM ============================================================
REM Compilador del instalador del Sistema DRFP v4.0
REM - Empaqueta el XAMPP 5.6.14 local (limpio de datos)
REM - Empaqueta el proyecto (incluye vendor, sin installer)
REM - Reescribe rutas C:\xampp -> C:\DRFP\xampp en configs
REM - Genera el icono .ico
REM - Verifica/descarga el redistributable VC++ 2012
REM - Compila el instalador con Inno Setup 6
REM ============================================================

set "INSTALLER_DIR=%~dp0"
REM Normalizar la ruta (%%~fI resuelve el ".." para que las exclusiones de robocopy funcionen)
for %%I in ("%INSTALLER_DIR%..") do set "PROYECTO=%%~fI"
set "XAMPP_SRC=C:\xampp"
REM El payload se construye en %TEMP% para no sincronizar ~500 MB con OneDrive
set "PAYLOAD=%TEMP%\DRFP_payload"
set "PHP=%XAMPP_SRC%\php\php.exe"
set "ISCC=C:\Program Files (x86)\Inno Setup 6\ISCC.exe"
if not exist "%ISCC%" set "ISCC=%LocalAppData%\Programs\Inno Setup 6\ISCC.exe"
echo ============================================================
echo   Compilador del instalador del Sistema DRFP v4.0
echo ============================================================
echo.

if not exist "%PHP%" (
    echo ERROR: no se encontro PHP de XAMPP en %PHP%
    echo        Se requiere XAMPP 5.6.14 instalado en %XAMPP_SRC%
    goto :fin
)

if not exist "%PROYECTO%\artisan" (
    echo ERROR: no se encontro el proyecto DRFP en %PROYECTO%
    goto :fin
)

echo [1/7] Limpiando carpeta payload anterior...
if exist "%PAYLOAD%\xampp" rmdir /s /q "%PAYLOAD%\xampp"
if exist "%PAYLOAD%\app" rmdir /s /q "%PAYLOAD%\app"
mkdir "%PAYLOAD%\xampp" >nul 2>&1
mkdir "%PAYLOAD%\app" >nul 2>&1

echo [2/7] Copiando XAMPP 5.6.14 (excluyendo datos de MySQL, tmp y logs)...
robocopy "%XAMPP_SRC%" "%PAYLOAD%\xampp" /E /NFL /NDL /NJH /NJS /NP /XD "%XAMPP_SRC%\mysql\data" "%XAMPP_SRC%\tmp" "%XAMPP_SRC%\apache\logs" "%XAMPP_SRC%\tomcat" >nul
if errorlevel 8 (
    echo ERROR al copiar XAMPP
    goto :fin
)

echo [3/7] Restaurando estructura limpia de la base de datos...
robocopy "%XAMPP_SRC%\mysql\backup" "%PAYLOAD%\xampp\mysql\data" /E /NFL /NDL /NJH /NJS /NP >nul
if errorlevel 8 (
    echo ERROR al copiar la estructura limpia de MySQL
    goto :fin
)
mkdir "%PAYLOAD%\xampp\tmp" >nul 2>&1
mkdir "%PAYLOAD%\xampp\apache\logs" >nul 2>&1
mkdir "%PAYLOAD%\xampp\php\logs" >nul 2>&1

echo [4/7] Copiando el sistema DRFP...
robocopy "%PROYECTO%" "%PAYLOAD%\app" /E /NFL /NDL /NJH /NJS /NP /XD "%PROYECTO%\installer" "%PROYECTO%\.git" /XF fix2.php testajax2.php drfp_launcher.log iniciar_drfp.sh >nul
if errorlevel 8 (
    echo ERROR al copiar el sistema DRFP
    goto :fin
)

echo [5/7] Reescribiendo rutas de XAMPP a C:\DRFP\xampp en archivos de configuracion...
powershell -NoProfile -ExecutionPolicy Bypass -Command "$lista=@(); $lista+=Get-ChildItem '%PAYLOAD%\xampp\apache\conf' -Recurse -Filter *.conf; $lista+=Get-ChildItem '%PAYLOAD%\xampp\php' -Filter 'php*.ini'; $lista+=Get-Item '%PAYLOAD%\xampp\mysql\bin\my.ini'; $lista+=Get-Item '%PAYLOAD%\xampp\ctlscript.bat'; foreach($f in $lista){ $t=[IO.File]::ReadAllText($f.FullName); $o=$t; $t=$t -replace 'C:\\xampp','C:\DRFP\xampp'; $t=$t -replace 'C:/xampp','C:/DRFP/xampp'; if($t -ne $o){ [IO.File]::WriteAllText($f.FullName,$t); Write-Host ('   ' + $f.Name) } }"

echo [6/7] Generando icono del instalador y acceso directo...
if exist "%PROYECTO%\drfp_icon.png" (
    "%PHP%" "%INSTALLER_DIR%recursos\generar_icono.php" "%PROYECTO%\drfp_icon.png" "%INSTALLER_DIR%recursos\drfp.ico"
) else (
    echo    drfp_icon.png no encontrado, se usara icono por defecto
)

echo [7/7] Verificando Visual C++ Redistributable 2012...
if not exist "%INSTALLER_DIR%recursos\vcredist_x86.exe" (
    echo    Descargando vcredist_x86.exe desde Microsoft...
    powershell -NoProfile -ExecutionPolicy Bypass -Command "try{[Net.ServicePointManager]::SecurityProtocol=[Net.SecurityProtocolType]::Tls12;(New-Object Net.WebClient).DownloadFile('https://download.microsoft.com/download/1/6/B/16B06F60-3B20-4FF2-B699-5E9B7962F9AE/VSU_4/vcredist_x86.exe','%INSTALLER_DIR%recursos\vcredist_x86.exe')}catch{exit 1}"
    if not exist "%INSTALLER_DIR%recursos\vcredist_x86.exe" (
        echo    AVISO: no se pudo descargar. El instalador seguira funcionando
        echo    si la PC destino ya tiene el runtime VC++ 2012 ^(msvcr110.dll^).
    )
)

echo.
echo Compilando instalador con Inno Setup...
if not exist "%ISCC%" (
    echo.
    echo ERROR: no se encontro Inno Setup 6 en:
    echo    %ISCC%
    echo.
    echo Instalelo una sola vez ^(gratis^) desde https://jrsoftware.org/isdl.php
    echo y vuelva a ejecutar este script.
    goto :fin
)
"%ISCC%" "%INSTALLER_DIR%DRFP-installer.iss"
if errorlevel 1 (
    echo ERROR: la compilacion con Inno Setup fallo
    goto :fin
)

echo.
echo ============================================================
echo   Instalador generado: %INSTALLER_DIR%dist\InstaladorDRFP-v4.0.exe
echo ============================================================

:fin
echo.
pause
