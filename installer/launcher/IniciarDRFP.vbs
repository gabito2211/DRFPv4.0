' Lanzador del Sistema DRFP: ejecuta IniciarDRFP.bat oculto (sin ventana de consola)
Set shell = CreateObject("WScript.Shell")
Dim ruta
ruta = Left(WScript.ScriptFullName, InStrRev(WScript.ScriptFullName, "\"))
shell.Run Chr(34) & ruta & "IniciarDRFP.bat" & Chr(34), 0, False
