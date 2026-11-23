<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use App\Visita; // <--- Cambiado a SINGULAR para que coincida con el uso abajo
use Carbon\Carbon;
use App\Sede;
use App\Sala;
use App\Bitacora;

class VisitasController extends Controller 
{
    public function index()
    {
        // Ahora sí coincidirá con la importación de arriba
        $visitas = Visita::with('persona', 'sala')->orderBy('created_at', 'desc')->get();
        $salas = Sala::orderBy('nombre')->get();

        return view('registro_visitantes', compact('visitas', 'salas'));
    }

   public function store(Request $request)
{
    $this->validate($request, [
        'sala_id' => 'required|integer|exists:salas,id',
    ]);

    $sala = Sala::findOrFail($request->input('sala_id'));

    // 1. Buscamos a la persona. Si existe, la asigna a $persona. 
    // Si no existe, la crea primero.
    $persona = Persona::firstOrCreate(
        ['cedula' => $request->input('id-card')], 
        [
            'nombre'    => $request->input('fullname'),
            'sexo'      => $request->input('gender'),
            'telefono'  => $request->input('telefono'),
            'telefono2' => $request->input('telefono2'),
            'correo'    => $request->input('gmail'),
            'comunidad' => $request->input('comunidad'),
            'direccion' => $request->input('direccion')
        ]
    );

    // 2. REGISTRO DE LA VISITA: Esto se ejecuta SIEMPRE.
    // Usamos 'create' para que cada vez que venga se genere una nueva fila en 'visitas'
    $visita = Visita::create([
        'persona_id'   => $persona->id, // El ID que recuperamos de la tabla personas
        'fecha'        => date('Y-m-d'),
        'hora_entrada' => date('H:i:s'),
        'departamento' => $sala->nombre,
        'sala_id'      => $sala->id,
    ]);

    Bitacora::registrar('create', 'visitas', "Se registró la visita de '{$persona->nombre}' (cédula {$persona->cedula}).", null, ['persona_id' => $visita->persona_id, 'nombre' => $persona->nombre, 'cedula' => $persona->cedula, 'fecha' => $visita->fecha, 'departamento' => $visita->departamento]);

    // 3. Personalizamos el mensaje
    $mensaje = $persona->wasRecentlyCreated 
        ? "Bienvenido/a, " . $persona->nombre . ". Registro exitoso." 
        : "Nueva visita registrada para: " . $persona->nombre;

    return redirect()->back()->with('success', $mensaje);
}
public function buscar($cedula)
{
    $persona = \App\Persona::where('cedula', $cedula)->first();

    if ($persona) {
        return response()->json([
            'status' => 'encontrado',
            'datos'  => $persona
        ]);
    }

    return response()->json(['status' => 'no_encontrado']);
}
// Obtener datos de la persona para el modal
public function datosPersona($id)
{
    $persona = Persona::findOrFail($id);
    return response()->json([
        'success' => true,
        'persona' => $persona
    ]);
}

// Mostrar modal con datos de la persona para editar
public function editarPersona($id)
{
    $persona = Persona::findOrFail($id);
    return response()->json([
        'success' => true,
        'persona' => $persona
    ]);
}

//Actualizar persona
public function actualizarPersona(Request $request, $id)
{
    \Log::info('Datos recibidos para actualizar:', $request->all());
    
    $this->validate($request, [
        'nombre'    => 'required',
        'sexo'      => 'required',
        'telefono'  => 'required',
        'telefono2' => 'nullable',
        'gmail'     => 'required|email',
        'comunidad' => 'required',
        'direccion' => 'required'
    ]);
    
    try {
        $persona = Persona::findOrFail($id);
        
        $persona->update([
            'nombre'    => $request->input('nombre'),
            'sexo'      => $request->input('sexo'),
            'telefono'  => $request->input('telefono'),
            'telefono2' => $request->input('telefono2'),
            'correo'    => $request->input('gmail'),
            'comunidad' => $request->input('comunidad'),
            'direccion' => $request->input('direccion')
        ]);

        Bitacora::registrar('update', 'personas', "Se actualizó la persona con cédula {$persona->cedula}.", null, ['cedula' => $persona->cedula, 'nombre' => $persona->nombre]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Persona actualizada correctamente'
            ]);
        }
        
        return redirect()->back()->with('success', 'Persona actualizada correctamente');
        
    } catch (\Exception $e) {
        \Log::error('Error al actualizar persona: ' . $e->getMessage());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        
        return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
}
// Eliminar persona (solo si no tiene visitas o se puede)
public function eliminarPersona($id)
{
    try {
        $persona = Persona::findOrFail($id);
        
        // Verificar si tiene visitas
        $visitas = Visita::where('persona_id', $id)->count();
        
        if ($visitas > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar la persona porque tiene ' . $visitas . ' visitas registradas.');
        }
        
        $datosAntes = ['cedula' => $persona->cedula, 'nombre' => $persona->nombre];
        $persona->delete();

        Bitacora::registrar('delete', 'personas', "Se eliminó la persona con cédula {$persona->cedula}.", $datosAntes, null);

        return redirect()->back()->with('success', 'Persona eliminada correctamente');
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al eliminar: ' . $e->getMessage());
    }
}
}