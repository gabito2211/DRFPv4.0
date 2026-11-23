<?php
// app/Http/Controllers/EncargadoController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encargado;
use Validator; // <-- Importar el facade Validator
use App\Sede;
use App\Bitacora;

class EncargadoController extends Controller
{
    /**
     * Mostrar todos los encargados
     */
    public function index()
    {
        try {
            $encargados = Encargado::all();
            return view('encargado', compact('encargados'));
        } catch (\Exception $e) {
            return view('encargado', [
                'encargados' => collect([]),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Registrar un nuevo encargado
     */
    public function store(Request $request)
    {
        try {
            // Validación con Validator para Laravel 5.4
            $validator = Validator::make($request->all(), [
                'id-card' => 'required|string|unique:encargados,cedula',
                'fullname' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'telefono2' => 'nullable|string|max:20'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $encargado = Encargado::create([
                'cedula' => $request->input('id-card'),
                'nombre' => $request->input('fullname'),
                'telefono' => $request->input('telefono'),
                'telefono2' => $request->input('telefono2')
            ]);

            Bitacora::registrar('create', 'encargados', "Se registró al encargado '{$encargado->nombre}' con cédula {$encargado->cedula}.", null, ['nombre' => $encargado->nombre, 'cedula' => $encargado->cedula]);

            return redirect()->route('encargados.index')
                ->with('success', 'Encargado registrado exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Buscar encargado por cédula (para autocompletar)
     */
    public function buscar($cedula)
    {
        try {
            $encargado = Encargado::where('cedula', $cedula)->first();
            
            if ($encargado) {
                return response()->json([
                    'status' => 'encontrado',
                    'datos' => $encargado
                ]);
            }
            
            return response()->json([
                'status' => 'no_encontrado'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un encargado específico
     */
    public function show($id)
    {
        try {
            $encargado = Encargado::findOrFail($id);
            return response()->json($encargado);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Encargado no encontrado'
            ], 404);
        }
    }

    /**
     * Actualizar un encargado
     */
    public function update(Request $request, $id)
    {
        try {
            $encargado = Encargado::findOrFail($id);
            
            // Validación con Validator para Laravel 5.4
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'telefono2' => 'nullable|string|max:20'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $encargado->update([
                'nombre' => $request->input('nombre'),
                'telefono' => $request->input('telefono'),
                'telefono2' => $request->input('telefono2')
            ]);

            Bitacora::registrar('update', 'encargados', "Se actualizó al encargado '{$encargado->nombre}'.", null, ['nombre' => $encargado->nombre, 'cedula' => $encargado->cedula]);

            return redirect()->route('encargados.index')
                ->with('success', 'Encargado actualizado exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un encargado
     */
    public function destroy($id)
    {
        try {
            $encargado = Encargado::findOrFail($id);
            $datosAntes = $encargado->toArray();
            $encargado->delete();

            Bitacora::registrar('delete', 'encargados', "Se eliminó al encargado '{$encargado->nombre}'.", $datosAntes, null);

            return redirect()->route('encargados.index')
                ->with('success', 'Encargado eliminado exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}