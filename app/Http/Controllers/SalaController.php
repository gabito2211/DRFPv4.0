<?php

namespace App\Http\Controllers;

use App\Sala;
use App\Bitacora;
use Illuminate\Http\Request;
use Validator;

class SalaController extends Controller
{
    public function index()
    {
        $salas = Sala::withCount('pasillos')->orderBy('nombre')->get();

        return view('ubicacion.salas', compact('salas'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'nombre' => trim($request->input('nombre')),
        ]);

        $sedeId = auth()->check() ? auth()->user()->sede_id : null;

        $reglaNombre = 'required|string|max:255|unique:salas,nombre';

        if (!empty($sedeId)) {
            $reglaNombre = 'required|string|max:255|unique:salas,nombre,NULL,id,sede_id,' . $sedeId;
        }

        $validator = Validator::make($request->all(), [
            'nombre'      => $reglaNombre,
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre de la sala es obligatorio.',
            'nombre.unique'   => 'Ya existe una sala registrada con ese nombre en esta sede.',
            'nombre.max'      => 'El nombre no puede superar los 255 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sala = Sala::create([
            'nombre'      => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
        ]);

        Bitacora::registrar('create', 'salas', "Se registró la sala '{$sala->nombre}'.", null, [
            'nombre'      => $sala->nombre,
            'descripcion' => $sala->descripcion,
        ]);

        return redirect()->route('ubicacion.salas.index')
            ->with('success', 'Sala registrada exitosamente');
    }

    public function show($id)
    {
        $sala = Sala::with('pasillos.estanterias')->findOrFail($id);

        return response()->json($sala);
    }

    public function update(Request $request, $id)
    {
        $sala = Sala::findOrFail($id);

        $request->merge([
            'nombre' => trim($request->input('nombre')),
        ]);

        $sedeId = auth()->check() ? auth()->user()->sede_id : null;

        $reglaNombre = 'required|string|max:255|unique:salas,nombre,' . $sala->id . ',id';

        if (!empty($sedeId)) {
            $reglaNombre .= ',sede_id,' . $sedeId;
        }

        $validator = Validator::make($request->all(), [
            'nombre'      => $reglaNombre,
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre de la sala es obligatorio.',
            'nombre.unique'   => 'Ya existe una sala registrada con ese nombre en esta sede.',
            'nombre.max'      => 'El nombre no puede superar los 255 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $datosAntes = [
            'nombre'      => $sala->nombre,
            'descripcion' => $sala->descripcion,
        ];

        $sala->update([
            'nombre'      => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
        ]);

        Bitacora::registrar('update', 'salas', "Se actualizó la sala '{$sala->nombre}'.", $datosAntes, [
            'nombre'      => $sala->nombre,
            'descripcion' => $sala->descripcion,
        ]);

        return redirect()->route('ubicacion.salas.index')
            ->with('success', 'Sala actualizada exitosamente');
    }

    public function destroy($id)
    {
        $sala = Sala::findOrFail($id);

        $datosAntes = [
            'nombre'      => $sala->nombre,
            'descripcion' => $sala->descripcion,
        ];

        $sala->delete();

        Bitacora::registrar('delete', 'salas', "Se eliminó la sala '{$sala->nombre}'.", $datosAntes, null);

        return redirect()->route('ubicacion.salas.index')
            ->with('success', 'Sala eliminada exitosamente');
    }
}
