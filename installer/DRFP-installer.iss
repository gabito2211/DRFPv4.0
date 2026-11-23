; ============================================================
; Instalador del Sistema DRFP v4.0 (Inno Setup 6)
; - Instala en C:\DRFP (XAMPP 5.6.14 + aplicacion + base de datos)
; - Registra Apache y MySQL como servicios con inicio automatico
; - Crea accesos directos con el lanzador del sistema
; - Compatible con Windows 7 y posteriores (x86 y x64)
;
; Compilar con: installer\compilar_instalador.bat
; (que previamente prepara la carpeta payload\)
; ============================================================

#define DRFPNombre "Sistema DRFP"
#define DRFPLetra "DRFP"
#define DRFPVersion "4.0"

; La carpeta payload se genera en %TEMP%\DRFP_payload (ver compilar_instalador.bat)
#define PayloadRoot GetEnv("TEMP") + "\DRFP_payload"

[Setup]
AppId={{EBFB5424-4ED6-AA68-3940-0524C0B7E1C6}
AppName={#DRFPNombre}
AppVersion={#DRFPVersion}
AppVerName={#DRFPNombre} v{#DRFPVersion}
AppPublisher=DRFP
DefaultDirName=C:\{#DRFPLetra}
DisableDirPage=yes
DefaultGroupName={#DRFPNombre}
DisableProgramGroupPage=yes
AllowNoIcons=yes
PrivilegesRequired=admin
MinVersion=6.1
Compression=lzma2/max
SolidCompression=yes
WizardStyle=modern
SetupIconFile=recursos\drfp.ico
OutputDir=dist
OutputBaseFilename=InstaladorDRFP-v{#DRFPVersion}
Uninstallable=yes
UninstallDisplayName={#DRFPNombre}
UninstallDisplayIcon={app}\recursos\drfp.ico
CreateUninstallRegKey=yes
InternalCompressLevel=max

[Languages]
Name: "spanish"; MessagesFile: "compiler:Languages\Spanish.isl"

[Tasks]
Name: "desktopicon"; Description: "Crear acceso directo en el escritorio"; GroupDescription: "Accesos directos:"; Flags: checkedonce

[Files]
Source: "{#PayloadRoot}\xampp\*"; DestDir: "{app}\xampp"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#PayloadRoot}\app\*"; DestDir: "{app}\app"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "scripts\configurar_apache.bat"; DestDir: "{app}\scripts"; Flags: ignoreversion
Source: "scripts\httpd-drfp-vhost.conf"; DestDir: "{app}\scripts"; Flags: ignoreversion
Source: "scripts\instalar_servicios.bat"; DestDir: "{app}\scripts"; Flags: ignoreversion
Source: "scripts\keygen.bat"; DestDir: "{app}\scripts"; Flags: ignoreversion
Source: "launcher\IniciarDRFP.bat"; DestDir: "{app}"; Flags: ignoreversion
Source: "launcher\IniciarDRFP.vbs"; DestDir: "{app}"; Flags: ignoreversion
Source: "recursos\vcredist_x86.exe"; DestDir: "{app}\recursos"; Flags: ignoreversion skipifsourcedoesntexist
Source: "recursos\drfp.ico"; DestDir: "{app}\recursos"; Flags: ignoreversion skipifsourcedoesntexist

[Dirs]
Name: "{app}\xampp\tmp"
Name: "{app}\xampp\apache\logs"

[Icons]
Name: "{group}\{#DRFPNombre}"; Filename: "wscript.exe"; Parameters: """{app}\IniciarDRFP.vbs"""; IconFilename: "{app}\recursos\drfp.ico"; Comment: "Iniciar el Sistema DRFP"
Name: "{group}\Desinstalar {#DRFPNombre}"; Filename: "{uninstallexe}"
Name: "{commondesktop}\{#DRFPNombre}"; Filename: "wscript.exe"; Parameters: """{app}\IniciarDRFP.vbs"""; IconFilename: "{app}\recursos\drfp.ico"; Comment: "Iniciar el Sistema DRFP"; Tasks: desktopicon

[Run]
Filename: "{app}\scripts\configurar_apache.bat"; Flags: runhidden waituntilterminated
Filename: "{app}\scripts\instalar_servicios.bat"; Flags: runhidden waituntilterminated
Filename: "{app}\scripts\keygen.bat"; Flags: runhidden waituntilterminated
Filename: "wscript.exe"; Parameters: """{app}\IniciarDRFP.vbs"""; Description: "Iniciar el Sistema DRFP ahora"; Flags: postinstall shellexec

[UninstallRun]
Filename: "net.exe"; Parameters: "stop DRFP-Apache"; Flags: runhidden; RunOnceId: "DrfpStopApache"
Filename: "{app}\xampp\apache\bin\httpd.exe"; Parameters: "-k uninstall -n DRFP-Apache"; Flags: runhidden; RunOnceId: "DrfpUninstallApache"
Filename: "net.exe"; Parameters: "stop DRFP-MySQL"; Flags: runhidden; RunOnceId: "DrfpStopMysql"
Filename: "{app}\xampp\mysql\bin\mysqld.exe"; Parameters: "--remove DRFP-MySQL"; Flags: runhidden; RunOnceId: "DrfpUninstallMysql"

[UninstallDelete]
Type: filesandordirs; Name: "{app}"

[Code]
// Advertir si los servicios no quedaron activos tras la instalacion
procedure CurStepChanged(CurStep: TSetupStep);
begin
  if CurStep = ssPostInstall then
  begin
    if not FileExists(ExpandConstant('{app}\instalacion_ok.txt')) then
    begin
      MsgBox('AVISO: los servicios DRFP-Apache y/o DRFP-MySQL no quedaron en ejecucion.' #13#10 +
             'Causas mas comunes:' #13#10 +
             '  - El puerto 80 esta ocupado por otro programa (IIS, Skype, etc.)' #13#10 +
             '  - Falta el componente Visual C++ 2012 en el sistema' #13#10#13#10 +
             'Consulte el archivo C:\DRFP\instalacion.log para mas detalles.' #13#10 +
             'El sistema intentara iniciar los servicios al usar el acceso directo.', mbError, MB_OK);
    end;
  end;
end;

// Confirmar que el usuario entiende que se eliminaran tambien los datos de la base
function InitializeUninstall(): Boolean;
begin
  Result := MsgBox('Esta a punto de desinstalar el Sistema DRFP.' #13#10#13#10 +
                   'SE ELIMINARAN TAMBIEN:' #13#10 +
                   '  - Los archivos del sistema y de XAMPP en C:\DRFP' #13#10 +
                   '  - Toda la base de datos (lectores, inventario, prestamos, etc.)' #13#10#13#10 +
                   'Si desea conservar los datos, copie antes la carpeta' #13#10 +
                   'C:\DRFP\xampp\mysql\data a un lugar seguro.' #13#10#13#10 +
                   'Desea continuar con la desinstalacion?', mbConfirmation, MB_YESNO) = IDYES;
end;
