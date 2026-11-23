<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use App\Carnet;
use App\Prestamo;
use App\Sancion; // 👈 Importado para revisar sanciones
use Carbon\Carbon; // 👈 Importado para comparar fechas
use Illuminate\Support\Facades\Storage;
use App\Sede;
use App\Bitacora;

class CarnetController extends Controller
{
    public function index() {
        // =========================================================================
        // 🔄 DESBLOQUEO AUTOMÁTICO AL CARGAR LA VISTA DE CARNETS
        // =========================================================================
        $sancionesVencidas = Sancion::where('estado', 'pendiente')
            ->whereNotNull('fecha_resolucion')
            ->where('fecha_resolucion', '<=', Carbon::now())
            ->get();

        foreach ($sancionesVencidas as $sancion) {
            // 1. Marcar sanción como solventada
            $sancion->estado = 'solventado';
            $sancion->observacion_resolucion = 'Desbloqueo automático por cumplimiento de fecha límite.';
            $sancion->save();

            // 2. Verificar si la persona aún tiene otras sanciones pendientes
            $sancionesPendientes = Sancion::where('persona_id', $sancion->persona_id)
                ->where('estado', 'pendiente')
                ->count();

            // 3. Si no tiene más sanciones, cambiar carnet a 'Activo'
            if ($sancionesPendientes === 0) {
                Carnet::where('persona_id', $sancion->persona_id)
                    ->update(['estado' => 'Activo']);
            }
        }
        // =========================================================================

        // 1. Buscamos el último registro para el código automático
        $ultimo = Carnet::latest('id')->first();
        $numero = 1; 

        if ($ultimo && !empty($ultimo->num_carnet)) {
            $partes = explode('-', $ultimo->num_carnet);
            if (isset($partes[2])) {
                $numero = (int)$partes[2] + 1;
            }
        }
        
        $codigoAutomatico = 'LIB-' . date('Y') . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT);
        $carnets = Carnet::with(['persona' => function ($q) {
            $q->withCount(['prestamos' => function ($q2) {
                $q2->where('estatus', 'pendiente');
            }]);
        }])->get();

        return view('carnet', [
            'carnets' => $carnets,
            'codigoAutomatico' => $codigoAutomatico
        ]);
    }

   public function store(Request $request)
   {
    $this->validate($request, [
        'id-card'    => 'required',
        'num-carnet' => 'required',
        'profesion'  => 'required',
        'exp'        => 'required|date',
        'venc'       => 'required|date',
        'foto'       => 'nullable|file|max:5048',
    ]);

    // Buscar a la persona por cédula
    $persona = Persona::where('cedula', $request->input('id-card'))->first();

    if (!$persona) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'La persona no existe. Regístrela primero en Visitantes.');
    }

    // Verificar si ya tiene carnet
    $carnetExistente = Carnet::where('persona_id', $persona->id)->first();

    if ($carnetExistente) {
        return redirect()->back()
            ->withInput()
            ->with('carnet_existente', [
                'persona_id' => $persona->id,
                'nombre' => $persona->nombre,
                'num_carnet' => $carnetExistente->num_carnet
            ]);
    }

    $nombreFoto = null;
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $nombreFoto = time() . '_' . $persona->cedula . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('img/carnets/'), $nombreFoto);
    }

    $persona->update([
        'telefono'  => $request->input('telefono'),
        'telefono2' => $request->input('telefono2'),
        'correo'    => $request->input('gmail'),
        'direccion' => $request->input('direccion'),
        'comunidad' => $request->input('ubicacion')
    ]);

    $carnet = Carnet::create([
        'persona_id'        => $persona->id,
        'num_carnet'        => $request->input('num-carnet'),
        'profesion'         => $request->input('profesion'),
        'fecha_emision'     => $request->input('exp'),
        'fecha_vencimiento' => $request->input('venc'),
        'foto'              => $nombreFoto,
        'estado'            => 'Activo'
    ]);

    Bitacora::registrar('create', 'carnets', "Se emitió el carnet #{$carnet->num_carnet} para la persona con cédula {$persona->cedula}.", null, ['num_carnet' => $carnet->num_carnet, 'persona_id' => $carnet->persona_id]);

    return redirect()->route('carnets.index')->with('success', '✅ Nuevo carnet generado exitosamente para ' . $persona->nombre);
}

    public function editar($id)
    {
        $carnet = Carnet::with('persona')->findOrFail($id);
        
        $ultimo = Carnet::latest('id')->first();
        $numero = 1;
        
        if ($ultimo && !empty($ultimo->num_carnet)) {
            $partes = explode('-', $ultimo->num_carnet);
            if (isset($partes[2])) {
                $numero = (int)$partes[2] + 1;
            }
        }
        
        $codigoAutomatico = 'LIB-' . date('Y') . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT);
        $carnets = Carnet::with('persona')->get();
        
        return view('carnet', [
            'carnets' => $carnets,
            'codigoAutomatico' => $codigoAutomatico,
            'carnetEditar' => $carnet
        ]);
    }

  public function actualizar(Request $request, $id)
  {
    $this->validate($request, [
        'profesion'  => 'required',
        'exp'        => 'required|date',
        'venc'       => 'required|date',
        'telefono'   => 'required',
        'gmail'      => 'required|email',
    ]);
    
    try {
        $carnet = Carnet::findOrFail($id);
        $persona = $carnet->persona;
        
        // Actualizar persona
        $persona->update([
            'telefono'  => $request->input('telefono'),
            'telefono2' => $request->input('telefono2'),
            'correo'    => $request->input('gmail'),
            'direccion' => $request->input('direccion'),
            'comunidad' => $request->input('ubicacion')
        ]);
        
        // Actualizar foto si se subió
        if ($request->hasFile('foto')) {
            if ($carnet->foto && file_exists(public_path('img/carnets/' . $carnet->foto))) {
                unlink(public_path('img/carnets/' . $carnet->foto));
            }
            $file = $request->file('foto');
            $nombreFoto = time() . '_' . $persona->cedula . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/carnets/'), $nombreFoto);
            $carnet->foto = $nombreFoto;
        }
        
        // Actualizar carnet
        $carnet->update([
            'profesion'         => $request->input('profesion'),
            'fecha_emision'     => $request->input('exp'),
            'fecha_vencimiento' => $request->input('venc')
        ]);

        Bitacora::registrar('update', 'carnets', "Se actualizó el carnet #{$carnet->num_carnet}.", null, ['num_carnet' => $carnet->num_carnet, 'persona_id' => $carnet->persona_id]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Carnet actualizado correctamente'
            ]);
        }
        
        return redirect()->route('carnets.index')->with('success', 'Carnet actualizado correctamente');
        
    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        
        return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
}

   public function renovar($id)
   {
    $carnet = Carnet::findOrFail($id);
    
    $fechaEmision = date('Y-m-d');
    $fechaVencimiento = date('Y-m-d', strtotime('+5 years'));
    
    $carnet->update([
        'fecha_emision'     => $fechaEmision,
        'fecha_vencimiento' => $fechaVencimiento,
        'estado'            => 'Activo'
    ]);

    Bitacora::registrar('update', 'carnets', "Se renovó el carnet #{$carnet->num_carnet}.");

    return redirect()->route('carnets.index')->with('success', 'Carnet renovado exitosamente. Nueva fecha de vencimiento: ' . $fechaVencimiento);
}

    public function destroy($id)
    {
        $carnet = Carnet::with('persona')->findOrFail($id);

        if ($carnet->persona && $carnet->persona->tienePrestamosPendientes()) {
            return redirect()->route('carnets.index')
                ->with('error', '❌ No se puede eliminar el carnet: ' . $carnet->persona->nombre . ' tiene préstamos pendientes por devolver.');
        }

        $datosAntes = $carnet->toArray();

        $carnet->delete();

        Bitacora::registrar('delete', 'carnets', "Se eliminó el carnet #{$carnet->num_carnet}.", $datosAntes, null);

        return redirect()->route('carnets.index')->with('success', 'Carnet eliminado exitosamente');
    }

    public function datos($id)
    {
        $carnet = Carnet::with('persona')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'carnet' => $carnet
        ]);
    }

    public function desbloquear($id)
    {
        $carnet = Carnet::findOrFail($id);
        
        $carnet->update([
            'estado' => 'Activo'
        ]);

        Bitacora::registrar('update', 'carnets', "Se desbloqueó el carnet #{$carnet->num_carnet}.");

        return redirect()->route('carnets.index')
            ->with('success', 'Carnet desbloqueado exitosamente.');
    }

    public function imprimir($id)
    {
        $carnet = Carnet::with('persona')->findOrFail($id);
        $persona = $carnet->persona;
        
        $foto = public_path('img/carnets/' . $carnet->foto);
        if (!$carnet->foto || !file_exists($foto)) {
            $foto = asset('img/user-default.png');
        } else {
            $foto = asset('img/carnets/' . $carnet->foto);
        }
         
        return view('reportes.carnet', [
            'nombre' => $persona->nombre,
            'cedula' => $persona->cedula,
            'profesion' => $carnet->profesion,
            'num_carnet' => $carnet->num_carnet,
            'fecha_emision' => $carnet->fecha_emision,
            'fecha_vencimiento' => $carnet->fecha_vencimiento,
            'foto' => $foto
        ]);
    }
}