<?php

namespace App\Http\Controllers;

use App\Pasillo;
use App\Sala;
use App\Bitacora;
use Illuminate\Http\Request;
use Validator;

class PasilloController extends Controller
{
    public function index()
    {
        $pasillos = Pasillo::with('sala')->withCount('estanterias')->orderBy('numero')->get();
        $salas = Sala::orderBy('nombre')->get();

        return view('ubicacion.pasillos', compact('pasillos', 'salas'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'numero' => trim($request->input('numero')),
        ]);

        $validator = Validator::make($request->all(), [
            'numero'  => 'required|string|max:50|unique:pasillos,numero,NULL,id,sala_id,' . $request->input('sala_id'),
            'sala_id' => 'required|integer|exists:salas,id',
        ], [
            'numero.required' => 'El número del pasillo es obligatorio.',
            'numero.unique'   => 'Ya existe un pasillo con ese número en la sala seleccionada.',
            'numero.max'      => 'El número no puede superar los 50 caracteres.',
            'sala_id.required' => 'Debe seleccionar la sala a la que pertenece el pasillo.',
            'sala_id.exists'   => 'La sala seleccionada no existe.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sala = Sala::findOrFail($request->input('sala_id'));

        $pasillo = Pasillo::create([
            'numero'  => $request->input('numero'),
            'sala_id' => $sala->id,
        ]);

        $pasillo->load('sala');

        Bitacora::registrar('create', 'pasillos', "Se registró el pasillo '{$pasillo->numero}' en la sala '{$pasillo->sala->nombre}'.", null, [
            'numero'  => $pasillo->numero,
            'sala_id' => $pasillo->sala_id,
        ]);

        return redirect()->route('ubicacion.pasillos.index')
            ->with('success', 'Pasillo registrado exitosamente');
    }

    /**
     * Registrar un nuevo pasillo vía AJAX (usado desde el modal rápido
     * de estanterías dentro del formulario de Inventario, sin salir de esa pantalla).
     */
    public function storeAjax(Request $request)
    {
        $request->merge([
            'numero' => trim($request->input('numero')),
        ]);

        $validator = Validator::make($request->all(), [
            'numero'  => 'required|string|max:50|unique:pasillos,numero,NULL,id,sala_id,' . $request->input('sala_id'),
            'sala_id' => 'required|integer|exists:salas,id',
        ], [
            'numero.required' => 'El número del pasillo es obligatorio.',
            'numero.unique'   => 'Ya existe un pasillo con ese número en la sala seleccionada.',
            'numero.max'      => 'El número no puede superar los 50 caracteres.',
            'sala_id.required' => 'Debe seleccionar la sala a la que pertenece el pasillo.',
            'sala_id.exists'   => 'La sala seleccionada no existe.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $sala = Sala::findOrFail($request->input('sala_id'));

        $pasillo = Pasillo::create([
            'numero'  => $request->input('numero'),
            'sala_id' => $sala->id,
        ]);

        $pasillo->load('sala');

        Bitacora::registrar('create', 'pasillos', "Se registró el pasillo '{$pasillo->numero}' en la sala '{$pasillo->sala->nombre}'.", null, [
            'numero'  => $pasillo->numero,
            'sala_id' => $pasillo->sala_id,
        ]);

        return response()->json([
            'success' => true,
            'pasillo' => [
                'id'          => $pasillo->id,
                'numero'      => $pasillo->numero,
                'sala_id'     => $pasillo->sala_id,
                'sala_nombre' => $pasillo->sala ? $pasillo->sala->nombre : null,
            ],
        ]);
    }

    public function show($id)
    {
        $pasillo = Pasillo::with('sala', 'estanterias')->findOrFail($id);

        return response()->json($pasillo);
    }

    public function update(Request $request, $id)
    {
        $pasillo = Pasillo::findOrFail($id);

        $request->merge([
            'numero' => trim($request->input('numero')),
        ]);

        $validator = Validator::make($request->all(), [
            'numero'  => 'required|string|max:50|unique:pasillos,numero,' . $pasillo->id . ',id,sala_id,' . $request->input('sala_id'),
            'sala_id' => 'required|integer|exists:salas,id',
        ], [
            'numero.required' => 'El número del pasillo es obligatorio.',
            'numero.unique'   => 'Ya existe un pasillo con ese número en la sala seleccionada.',
            'numero.max'      => 'El número no puede superar los 50 caracteres.',
            'sala_id.required' => 'Debe seleccionar la sala a la que pertenece el pasillo.',
            'sala_id.exists'   => 'La sala seleccionada no existe.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sala = Sala::findOrFail($request->input('sala_id'));

        $datosAntes = [
            'numero'  => $pasillo->numero,
            'sala_id' => $pasillo->sala_id,
        ];

        $pasillo->update([
            'numero'  => $request->input('numero'),
            'sala_id' => $sala->id,
        ]);

        $pasillo->load('sala');

        Bitacora::registrar('update', 'pasillos', "Se actualizó el pasillo '{$pasillo->numero}' en la sala '{$pasillo->sala->nombre}'.", $datosAntes, [
            'numero'  => $pasillo->numero,
            'sala_id' => $pasillo->sala_id,
        ]);

        return redirect()->route('ubicacion.pasillos.index')
            ->with('success', 'Pasillo actualizado exitosamente');
    }

    public function destroy($id)
    {
        $pasillo = Pasillo::findOrFail($id);
        $pasillo->load('sala');

        $datosAntes = [
            'numero'  => $pasillo->numero,
            'sala_id' => $pasillo->sala_id,
        ];

        $pasillo->delete();

        Bitacora::registrar('delete', 'pasillos', "Se eliminó el pasillo '{$pasillo->numero}' de la sala '{$pasillo->sala->nombre}'.", $datosAntes, null);

        return redirect()->route('ubicacion.pasillos.index')
            ->with('success', 'Pasillo eliminado exitosamente');
    }
}
