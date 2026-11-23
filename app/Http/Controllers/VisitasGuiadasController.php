<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VisitaGuiada;
use Carbon\Carbon;
use App\Sala;
use App\Bitacora;

class VisitasGuiadasController extends Controller
{
    /**
     * Carga la vista con el historial de grupos
     */
    public function index()
    {
        $visitas = VisitaGuiada::with('sala')
            ->orderBy('fecha_visita', 'desc')
            ->orderBy('hora', 'asc')
            ->paginate(10);
        $salas = Sala::orderBy('nombre')->get();

        return view('visitas_guiadas', compact('visitas', 'salas'));
    }

    /**
     * Guarda el registro de la nueva visita guiada
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'institucion'  => 'required|string|max:255',
            'responsable'  => 'required|string|max:255',
            'encargado'    => 'required|string|max:255',
            'cantidad'     => 'required|integer|min:1',
            'fecha_visita' => 'required|date',
            'hora'         => 'required|date_format:H:i',
            'sala_id'      => 'required|integer|exists:salas,id',
        ], [
            'hora.required'    => 'Debes indicar la hora de la visita.',
            'hora.date_format' => 'La hora debe tener el formato HH:MM.',
        ]);

        $sala = Sala::findOrFail($request->sala_id);
        $horaFormateada = substr($request->hora, 0, 5);

        // Validar anti-solapamiento: misma fecha + misma hora + misma sala
        $existe = VisitaGuiada::where('fecha_visita', $request->fecha_visita)
            ->where(function($q) use ($request, $horaFormateada) {
                $q->where('hora', $request->hora)
                  ->orWhere('hora', $horaFormateada . ':00');
            })
            ->where(function($q) use ($request, $sala) {
                $q->where('sala_id', $request->sala_id)
                  ->orWhere('tipo_recorrido', $sala->nombre);
            })
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe una visita guiada en la sala "' . $sala->nombre . '" programada para el ' .
                    Carbon::parse($request->fecha_visita)->format('d/m/Y') .
                    ' a las ' . $horaFormateada . '. Por favor elige otra hora o sala.');
        }

        $visita = VisitaGuiada::create([
            'institucion'    => $request->institucion,
            'responsable'    => $request->responsable,
            'cantidad'       => $request->cantidad,
            'encargado'      => $request->encargado,
            'fecha_visita'   => $request->fecha_visita,
            'hora'           => $request->hora,
            'tipo_recorrido' => $sala->nombre,
            'sala_id'        => $sala->id,
        ]);

        Bitacora::registrar(
            'create',
            'visitas_guiadas',
            "Se registró una visita guiada de la institución '{$visita->institucion}'.",
            null,
            [
                'institucion'  => $visita->institucion,
                'cantidad'     => $visita->cantidad,
                'fecha_visita' => $visita->fecha_visita,
                'hora'         => $visita->hora,
            ]
        );

        return redirect()->back()->with('success', 'Visita guiada registrada con éxito.');
    }

    /**
     * Retorna los datos de una visita para el modal de visualización / edición
     */
    public function datos($id)
    {
        $visita = VisitaGuiada::with('sala')->find($id);
        return response()->json($visita);
    }

    /**
     * Actualiza los datos desde el modal de edición
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'institucion'  => 'required|string|max:255',
            'responsable'  => 'required|string|max:255',
            'encargado'    => 'required|string|max:255',
            'cantidad'     => 'required|integer|min:1',
            'fecha_visita' => 'required|date',
            'hora'         => 'required|date_format:H:i',
            'sala_id'      => 'required|integer|exists:salas,id',
        ], [
            'hora.required'    => 'Debes indicar la hora de la visita.',
            'hora.date_format' => 'La hora debe tener el formato HH:MM.',
        ]);

        $sala = Sala::findOrFail($request->sala_id);
        $horaFormateada = substr($request->hora, 0, 5);

        // Validar anti-solapamiento: misma fecha + misma hora + misma sala, excluyendo el registro actual
        $existe = VisitaGuiada::where('fecha_visita', $request->fecha_visita)
            ->where(function($q) use ($request, $horaFormateada) {
                $q->where('hora', $request->hora)
                  ->orWhere('hora', $horaFormateada . ':00');
            })
            ->where(function($q) use ($request, $sala) {
                $q->where('sala_id', $request->sala_id)
                  ->orWhere('tipo_recorrido', $sala->nombre);
            })
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe otra visita guiada en la sala "' . $sala->nombre . '" programada para el ' .
                    Carbon::parse($request->fecha_visita)->format('d/m/Y') .
                    ' a las ' . $horaFormateada . '. Por favor elige otra hora o sala.');
        }

        $visita = VisitaGuiada::findOrFail($id);
        $visita->update([
            'institucion'    => $request->institucion,
            'responsable'    => $request->responsable,
            'cantidad'       => $request->cantidad,
            'encargado'      => $request->encargado,
            'fecha_visita'   => $request->fecha_visita,
            'hora'           => $request->hora,
            'tipo_recorrido' => $sala->nombre,
            'sala_id'        => $sala->id,
        ]);

        Bitacora::registrar(
            'update',
            'visitas_guiadas',
            "Se actualizó la visita guiada #{$visita->id}.",
            null,
            [
                'institucion'  => $visita->institucion,
                'cantidad'     => $visita->cantidad,
                'fecha_visita' => $visita->fecha_visita,
                'hora'         => $visita->hora,
            ]
        );

        return redirect()->back()->with('success', 'Registro actualizado correctamente.');
    }
}
