<?php

namespace App\Http\Controllers;

use App\Estanteria;
use App\Pasillo;
use App\Bitacora;
use Illuminate\Http\Request;
use Validator;

class EstanteriaController extends Controller
{
    public function index()
    {
        $estanterias = Estanteria::with('pasillo.sala')->orderBy('numero')->get();
        $pasillos = Pasillo::with('sala')->orderBy('numero')->get();

        return view('ubicacion.estanterias', compact('estanterias', 'pasillos'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'numero' => trim($request->input('numero')),
            'genero' => trim($request->input('genero')),
        ]);

        $validator = Validator::make($request->all(), [
            'numero'     => 'required|string|max:50|unique:estanterias,numero,NULL,id,pasillo_id,' . $request->input('pasillo_id'),
            'genero'     => 'required|string|max:100',
            'pasillo_id' => 'required|integer|exists:pasillos,id',
        ], [
            'numero.required' => 'El número de la estantería es obligatorio.',
            'numero.unique'   => 'Ya existe una estantería con ese número en el pasillo seleccionado.',
            'numero.max'      => 'El número no puede superar los 50 caracteres.',
            'genero.required' => 'El género de la estantería es obligatorio.',
            'genero.max'      => 'El género no puede superar los 100 caracteres.',
            'pasillo_id.required' => 'Debe seleccionar el pasillo al que pertenece la estantería.',
            'pasillo_id.exists'   => 'El pasillo seleccionado no existe.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pasillo = Pasillo::findOrFail($request->input('pasillo_id'));

        $estanteria = Estanteria::create([
            'numero'     => $request->input('numero'),
            'genero'     => $request->input('genero'),
            'pasillo_id' => $pasillo->id,
        ]);

        $estanteria->load('pasillo.sala');

        Bitacora::registrar('create', 'estanterias', "Se registró la estantería '{$estanteria->numero}' en el pasillo '{$estanteria->pasillo->numero}'.", null, [
            'numero'     => $estanteria->numero,
            'genero'     => $estanteria->genero,
            'pasillo_id' => $estanteria->pasillo_id,
        ]);

        return redirect()->route('ubicacion.estanterias.index')
            ->with('success', 'Estantería registrada exitosamente');
    }

    /**
     * Registrar una nueva estantería vía AJAX (usado desde el modal rápido
     * dentro del formulario de Inventario, sin salir de esa pantalla).
     */
    public function storeAjax(Request $request)
    {
        $request->merge([
            'numero' => trim($request->input('numero')),
            'genero' => trim($request->input('genero')),
        ]);

        $validator = Validator::make($request->all(), [
            'numero'     => 'required|string|max:50|unique:estanterias,numero,NULL,id,pasillo_id,' . $request->input('pasillo_id'),
            'genero'     => 'required|string|max:100',
            'pasillo_id' => 'required|integer|exists:pasillos,id',
        ], [
            'numero.required' => 'El número de la estantería es obligatorio.',
            'numero.unique'   => 'Ya existe una estantería con ese número en el pasillo seleccionado.',
            'numero.max'      => 'El número no puede superar los 50 caracteres.',
            'genero.required' => 'El género de la estantería es obligatorio.',
            'genero.max'      => 'El género no puede superar los 100 caracteres.',
            'pasillo_id.required' => 'Debe seleccionar el pasillo al que pertenece la estantería.',
            'pasillo_id.exists'   => 'El pasillo seleccionado no existe.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $pasillo = Pasillo::findOrFail($request->input('pasillo_id'));

        $estanteria = Estanteria::create([
            'numero'     => $request->input('numero'),
            'genero'     => $request->input('genero'),
            'pasillo_id' => $pasillo->id,
        ]);

        $estanteria->load('pasillo.sala');

        Bitacora::registrar('create', 'estanterias', "Se registró la estantería '{$estanteria->numero}' en el pasillo '{$estanteria->pasillo->numero}'.", null, [
            'numero'     => $estanteria->numero,
            'genero'     => $estanteria->genero,
            'pasillo_id' => $estanteria->pasillo_id,
        ]);

        return response()->json([
            'success'    => true,
            'estanteria' => [
                'id'             => $estanteria->id,
                'numero'         => $estanteria->numero,
                'genero'         => $estanteria->genero,
                'pasillo_id'     => $estanteria->pasillo_id,
                'pasillo_numero' => $estanteria->pasillo ? $estanteria->pasillo->numero : null,
                'sala_nombre'    => ($estanteria->pasillo && $estanteria->pasillo->sala) ? $estanteria->pasillo->sala->nombre : null,
            ],
        ]);
    }

    public function show($id)
    {
        $estanteria = Estanteria::with('pasillo.sala')->findOrFail($id);

        return response()->json($estanteria);
    }

    public function update(Request $request, $id)
    {
        $estanteria = Estanteria::findOrFail($id);

        $request->merge([
            'numero' => trim($request->input('numero')),
            'genero' => trim($request->input('genero')),
        ]);

        $validator = Validator::make($request->all(), [
            'numero'     => 'required|string|max:50|unique:estanterias,numero,' . $estanteria->id . ',id,pasillo_id,' . $request->input('pasillo_id'),
            'genero'     => 'required|string|max:100',
            'pasillo_id' => 'required|integer|exists:pasillos,id',
        ], [
            'numero.required' => 'El número de la estantería es obligatorio.',
            'numero.unique'   => 'Ya existe una estantería con ese número en el pasillo seleccionado.',
            'numero.max'      => 'El número no puede superar los 50 caracteres.',
            'genero.required' => 'El género de la estantería es obligatorio.',
            'genero.max'      => 'El género no puede superar los 100 caracteres.',
            'pasillo_id.required' => 'Debe seleccionar el pasillo al que pertenece la estantería.',
            'pasillo_id.exists'   => 'El pasillo seleccionado no existe.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pasillo = Pasillo::findOrFail($request->input('pasillo_id'));

        $datosAntes = [
            'numero'     => $estanteria->numero,
            'genero'     => $estanteria->genero,
            'pasillo_id' => $estanteria->pasillo_id,
        ];

        $estanteria->update([
            'numero'     => $request->input('numero'),
            'genero'     => $request->input('genero'),
            'pasillo_id' => $pasillo->id,
        ]);

        $estanteria->load('pasillo.sala');

        Bitacora::registrar('update', 'estanterias', "Se actualizó la estantería '{$estanteria->numero}' en el pasillo '{$estanteria->pasillo->numero}'.", $datosAntes, [
            'numero'     => $estanteria->numero,
            'genero'     => $estanteria->genero,
            'pasillo_id' => $estanteria->pasillo_id,
        ]);

        return redirect()->route('ubicacion.estanterias.index')
            ->with('success', 'Estantería actualizada exitosamente');
    }

    public function destroy($id)
    {
        $estanteria = Estanteria::findOrFail($id);
        $estanteria->load('pasillo.sala');

        $datosAntes = [
            'numero'     => $estanteria->numero,
            'genero'     => $estanteria->genero,
            'pasillo_id' => $estanteria->pasillo_id,
        ];

        $estanteria->delete();

        Bitacora::registrar('delete', 'estanterias', "Se eliminó la estantería '{$estanteria->numero}' del pasillo '{$estanteria->pasillo->numero}'.", $datosAntes, null);

        return redirect()->route('ubicacion.estanterias.index')
            ->with('success', 'Estantería eliminada exitosamente');
    }
}
