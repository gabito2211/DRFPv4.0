<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Autor;
use App\Bitacora;
use Validator;

class AutorController extends Controller
{
    /**
     * Mostrar todos los autores registrados.
     */
    public function index()
    {
        $autores = Autor::orderBy('nombre')->get();
        return view('autor', compact('autores'));
    }

    /**
     * Registrar un nuevo autor desde la vista de gestión de autores.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:autores,nombre',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $autor = Autor::create([
            'nombre' => trim($request->input('nombre')),
        ]);

        Bitacora::registrar('create', 'autores', "Se registró el autor '{$autor->nombre}'.", null, ['nombre' => $autor->nombre]);

        return redirect()->route('autores.index')
            ->with('success', '✅ Autor registrado exitosamente');
    }

    /**
     * Registrar un nuevo autor vía AJAX (usado desde el modal rápido
     * dentro del formulario de Inventario, sin salir de esa pantalla).
     */
    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:autores,nombre',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('nombre'),
            ], 422);
        }

        $autor = Autor::create([
            'nombre' => trim($request->input('nombre')),
        ]);

        Bitacora::registrar('create', 'autores', "Se registró el autor '{$autor->nombre}'.", null, ['nombre' => $autor->nombre]);

        return response()->json([
            'success' => true,
            'autor'   => $autor,
        ]);
    }

    /**
     * Mostrar un autor específico (para el modal de edición).
     */
    public function show($id)
    {
        $autor = Autor::findOrFail($id);
        return response()->json($autor);
    }

    /**
     * Actualizar un autor. Si se le cambia el nombre, se actualizan también
     * (por consistencia) los libros del inventario que tenían el nombre
     * anterior, ya que ese campo sigue siendo texto libre en Inventario.
     */
    public function update(Request $request, $id)
    {
        $autor = Autor::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:autores,nombre,' . $autor->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $nombreAnterior = $autor->nombre;
        $nombreNuevo    = trim($request->input('nombre'));

        $autor->update(['nombre' => $nombreNuevo]);

        Bitacora::registrar('update', 'autores', "Se actualizó el autor '{$nombreAnterior}' a '{$nombreNuevo}'.", ['nombre' => $nombreAnterior], ['nombre' => $nombreNuevo]);

        if ($nombreAnterior !== $nombreNuevo) {
            \App\Inventario::where('autor', $nombreAnterior)->update(['autor' => $nombreNuevo]);
        }

        return redirect()->route('autores.index')
            ->with('success', '✅ Autor actualizado exitosamente');
    }

    /**
     * Eliminar un autor, siempre que no tenga libros asociados en el
     * inventario (para no dejar libros con un autor "fantasma").
     */
    public function destroy($id)
    {
        $autor = Autor::findOrFail($id);

        $tieneLibros = \App\Inventario::where('autor', $autor->nombre)->exists();

        if ($tieneLibros) {
            return redirect()->back()
                ->with('error', '❌ No se puede eliminar: este autor tiene libros registrados en el inventario.');
        }

        $autor->delete();

        Bitacora::registrar('delete', 'autores', "Se eliminó el autor '{$autor->nombre}'.", ['nombre' => $autor->nombre], null);

        return redirect()->route('autores.index')
            ->with('success', '✅ Autor eliminado exitosamente');
    }
}
