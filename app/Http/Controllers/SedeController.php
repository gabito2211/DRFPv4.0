<?php

namespace App\Http\Controllers;

use App\Sede;
use App\Encargado;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class SedeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Solo la sede principal administra el catálogo completo;
        // las demás sedes solo ven y editan la suya
        if ($this->esPrincipal($user)) {
            $sedes = Sede::with('encargado')->get();
            $esPrincipal = true;
        } else {
            $sedes = Sede::with('encargado')->where('id', $user->sede_id)->get();
            $esPrincipal = false;
        }

        return view('sedes', compact('sedes', 'esPrincipal'));
    }

    public function store(Request $request)
    {
        // Solo la sede principal puede registrar nuevas sedes
        if (!$this->esPrincipal(Auth::user())) {
            return redirect()->route('sedes.index')
                ->with('error', 'Solo la Sede Principal puede registrar nuevas sedes.');
        }

        try {
            $validator = Validator::make($request->all(), [
                'comunidad'  => 'required|string',
                'biblioteca' => 'required|string|unique:sedes,biblioteca',
                'direccion'  => 'required|string',
                'cedula'     => 'required|string|unique:encargados,cedula',
                'nombre'     => 'required|string|max:255',
                'telefono'   => 'required|string|max:20',
                'telefono2'  => 'nullable|string|max:20'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // 1. Crear el ENCARGADO
            $encargado = Encargado::create([
                'cedula'   => $request->input('cedula'),
                'nombre'   => $request->input('nombre'),
                'telefono' => $request->input('telefono'),
                'telefono2' => $request->input('telefono2')
            ]);

            // 2. Crear la SEDE con el ID del encargado
            $sede = Sede::create([
                'comunidad'    => $request->input('comunidad'),
                'biblioteca'   => $request->input('biblioteca'),
                'direccion'    => $request->input('direccion'),
                'encargado_id' => $encargado->id, // ← USAMOS EL ID
                'cedula'       => $encargado->cedula // ← LO GUARDAMOS PERO NO LO USAMOS
            ]);

            Bitacora::registrar('create', 'sedes', "Se registró la sede '{$sede->biblioteca}' en '{$sede->comunidad}'.", null, ['biblioteca' => $sede->biblioteca, 'comunidad' => $sede->comunidad]);

            return redirect()->route('sedes.index')
                ->with('success', 'Sede y encargado registrados exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Limitación: la sede principal edita todas;
        // las demás sedes solo pueden editarse a sí mismas
        if (!$this->esPrincipal($user) && (int) $user->sede_id !== (int) $id) {
            return redirect()->route('sedes.index')
                ->with('error', 'Solo puedes editar los datos de tu propia sede.');
        }

        try {
            $sede = Sede::findOrFail($id);
            
            // Buscar el encargado actual
            $encargado = $sede->encargado;

            $validator = Validator::make($request->all(), [
                'comunidad'  => 'required|string',
                'biblioteca' => 'required|string|unique:sedes,biblioteca,' . $id,
                'direccion'  => 'required|string',
                'cedula'     => 'required|string|unique:encargados,cedula,' . ($encargado ? $encargado->id : 'NULL'),
                'nombre'     => 'required|string|max:255',
                'telefono'   => 'required|string|max:20',
                'telefono2'  => 'nullable|string|max:20'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Si la cédula cambió, necesitamos actualizar el encargado
            $nuevaCedula = $request->input('cedula');

            // Actualizar SEDE (solo campos de sede, NO tocamos encargado_id)
            $sede->update([
                'comunidad'  => $request->input('comunidad'),
                'biblioteca' => $request->input('biblioteca'),
                'direccion'  => $request->input('direccion'),
                // No actualizamos encargado_id aquí
                // Actualizamos la cédula solo como referencia
                'cedula'     => $nuevaCedula
            ]);

            // Actualizar ENCARGADO
            if ($encargado) {
                $encargado->update([
                    'cedula'   => $nuevaCedula,
                    'nombre'   => $request->input('nombre'),
                    'telefono' => $request->input('telefono'),
                    'telefono2' => $request->input('telefono2')
                ]);
            } else {
                // Si no existe, crear uno nuevo y asignarlo a la sede
                $nuevoEncargado = Encargado::create([
                    'cedula'   => $nuevaCedula,
                    'nombre'   => $request->input('nombre'),
                    'telefono' => $request->input('telefono'),
                    'telefono2' => $request->input('telefono2')
                ]);
                // Asignar el nuevo encargado a la sede
                $sede->encargado_id = $nuevoEncargado->id;
                $sede->save();
            }

            Bitacora::registrar('update', 'sedes', "Se actualizó la sede '{$sede->biblioteca}'.", null, ['biblioteca' => $sede->biblioteca, 'comunidad' => $sede->comunidad]);

            return redirect()->route('sedes.index')
                ->with('success', 'Sede y encargado actualizados exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        // Solo la sede principal puede eliminar sedes
        if (!$this->esPrincipal(Auth::user())) {
            return redirect()->route('sedes.index')
                ->with('error', 'Solo la Sede Principal puede eliminar sedes.');
        }

        try {
            $sede = Sede::findOrFail($id);
            $encargadoId = $sede->encargado_id;
            $datosAntes = ['biblioteca' => $sede->biblioteca, 'comunidad' => $sede->comunidad];

            // Eliminar la sede
            $sede->delete();

            Bitacora::registrar('delete', 'sedes', "Se eliminó la sede '{$sede->biblioteca}'.", $datosAntes, null);

            // Verificar si el encargado está asociado a otra sede
            $otraSede = Sede::where('encargado_id', $encargadoId)->first();
            if (!$otraSede && $encargadoId) {
                // Si no está en otra sede, eliminar el encargado
                Encargado::find($encargadoId)->delete();
            }

            return redirect()->route('sedes.index')
                ->with('success', 'Sede eliminada exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    // Método para migrar datos existentes (EJECUTAR UNA SOLA VEZ)
    public function migrarEncargados()
    {
        $sedes = Sede::whereNull('encargado_id')->whereNotNull('cedula')->get();
        $contador = 0;
        
        foreach ($sedes as $sede) {
            $encargado = Encargado::where('cedula', $sede->cedula)->first();
            if ($encargado) {
                $sede->encargado_id = $encargado->id;
                $sede->save();
                $contador++;
            }
        }
        
        return "Se migraron {$contador} sedes";
    }

    /**
     * Determina si el usuario pertenece a la sede principal
     * (misma regla que el trait AsignaSede).
     */
    protected function esPrincipal($user)
    {
        return $user && ((int) $user->sede_id === 1 || empty($user->sede_id));
    }
}