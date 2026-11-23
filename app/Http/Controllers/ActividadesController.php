<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actividades;
use App\PlanVacacional;
use App\Sede;
use App\Encargado;
use App\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ActividadesController extends Controller
{
    public function index(Request $request)
    {
        $planesActivos = PlanVacacional::all();
        $planVigente = PlanVacacional::where('estado', 'activo')->first();
        
        return view('planVacacional', compact('planesActivos', 'planVigente'));
    }

    /**
     * Ver actividades de un plan específico
     * Esta es la función que maneja la ruta /actividades/plan/{id}
     */
    public function verPorPlan($planId)
    {
        try {
            $planSeleccionado = PlanVacacional::with(['actividades.sede.encargado'])->findOrFail($planId);
            
            // Actualizar estado automáticamente
            $planSeleccionado->actualizarEstadoAutomatico();
            
            // Determinar el estado del plan
            $planActivo = $planSeleccionado->estado === PlanVacacional::ESTADO_ACTIVO;
            $planCompletado = $planSeleccionado->estado === PlanVacacional::ESTADO_COMPLETADO;
            
            $actividadesDelPlan = $planSeleccionado->actividades;
            $sedes = Sede::all();
            
            return view('nuevaActividad', compact(
                'planSeleccionado', 
                'planActivo', 
                'planCompletado', 
                'actividadesDelPlan', 
                'sedes'
            ));
            
        } catch (\Exception $e) {
            return redirect()->route('planes.index')
                ->with('error', 'El plan solicitado no existe o no está disponible.');
        }
    }

    public function nueva(Request $request)
    {
        $planId = $request->get('plan_id');
        $planSeleccionado = PlanVacacional::find($planId);
        
        $planActivo = $planSeleccionado && $planSeleccionado->estado === 'activo';
        $planCompletado = $planSeleccionado && $planSeleccionado->estado === 'completado';
        
        $actividadesDelPlan = [];
        if ($planSeleccionado) {
            $actividadesDelPlan = Actividades::with(['planVacacional', 'sede', 'encargado'])
                ->where('plan_vacacional_id', $planId)
                ->orderBy('fecha', 'desc')
                ->get();
        }
        
        $sedes = Sede::with('encargado')->get();
        
        return view('nuevaActividad', compact('planSeleccionado', 'actividadesDelPlan', 'planActivo', 'planCompletado', 'sedes'));
    }

    public function getSedeData($id)
    {
        try {
            $sede = Sede::with('encargado')->findOrFail($id);
            return response()->json([
                'success' => true,
                'sede' => $sede,
                'encargado' => $sede->encargado ? [
                    'id' => $sede->encargado->id,
                    'nombre' => $sede->encargado->nombre,
                    'cedula' => $sede->encargado->cedula,
                    'telefono' => $sede->encargado->telefono
                ] : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los datos de la sede'
            ], 404);
        }
    }

    public function filtrar(Request $request)
    {
        $planId = $request->input('plan_id');
        
        if (!$planId) {
            return response()->json([
                'html' => '<tr><td colspan="8" style="text-align:center; padding: 20px; color: var(--text-muted);">Selecciona un plan vacacional para ver sus actividades</td></tr>',
                'total' => 0
            ]);
        }
        
        $plan = PlanVacacional::find($planId);
        $planActivo = $plan && $plan->estado === 'activo';
        
        $actividades = Actividades::with(['planVacacional', 'sede', 'encargado'])
            ->where('plan_vacacional_id', $planId)
            ->orderBy('fecha', 'desc')
            ->get();
        
        $html = '';
        if ($actividades->isEmpty()) {
            $html = '<tr><td colspan="8" style="text-align:center; padding: 20px; color: var(--text-muted);">No hay actividades registradas para este plan</td></tr>';
        } else {
            foreach ($actividades as $actividad) {
                $nombrePlan = $actividad->planVacacional ? $actividad->planVacacional->nombre : 'Sin plan';
                
                $nombreSede = 'Sin sede';
                if (isset($actividad->sede) && is_object($actividad->sede)) {
                    if (isset($actividad->sede->nombre_completo)) {
                        $nombreSede = $actividad->sede->nombre_completo;
                    }
                }
                
                $nombreEncargado = 'Sin responsable';
                if (isset($actividad->encargado) && is_object($actividad->encargado)) {
                    if (isset($actividad->encargado->nombre)) {
                        $nombreEncargado = $actividad->encargado->nombre;
                    }
                } elseif (isset($actividad->responsable) && !empty($actividad->responsable)) {
                    $nombreEncargado = $actividad->responsable;
                }
                
                $disabledEliminar = $planActivo ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : '';
                $colorEliminar = $planActivo ? '#b2bec3' : '#e74c3c';
                
                $html .= '<tr>
                    <td>' . e($nombrePlan) . '</td>
                    <td><strong>' . e($actividad->actividad) . '</strong></td>
                    <td>' . Carbon::parse($actividad->fecha)->format('d/m/Y') . '</td>
                    <td>' . e(Str::limit($actividad->descripcion, 50)) . '</td>
                    <td>' . e($nombreSede) . '</td>
                    <td>' . e($nombreEncargado) . '</td>
                    <td><span class="count-badge">' . $actividad->cantidad . '</span></td>
                    <td>
                        <button class="btn-icon" title="Ver" onclick="verActividad(' . $actividad->id . ')">
                            <i data-lucide="eye" style="color: #0984e3;"></i>
                        </button>
                        <button class="btn-icon" title="Editar" onclick="editarActividad(' . $actividad->id . ')">
                            <i data-lucide="edit-3" style="color: #00b894;"></i>
                        </button>
                        <button class="btn-icon" title="Eliminar" onclick="eliminarActividad(' . $actividad->id . ')" ' . $disabledEliminar . '>
                            <i data-lucide="trash-2" style="color: ' . $colorEliminar . ';"></i>
                        </button>
                    </td>
                </tr>';
            }
        }
        
        return response()->json([
            'html' => $html,
            'total' => $actividades->count(),
            'planActivo' => $planActivo
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'plan_vacacional_id' => 'required|exists:planes_vacacionales,id',
            'actividad'    => 'required|string|max:255',
            'hora'         => 'required|date_format:H:i',
            'fecha'        => 'required|date',
            'descripcion'  => 'nullable|string',
            'objetivo'     => 'required|string',
            'cantidad'     => 'required|integer|min:1',
            'sede_id'      => 'required|exists:sedes,id',
            'responsable'  => 'required|string|max:255',
        ]);

        $plan = PlanVacacional::findOrFail($request->plan_vacacional_id);
        if ($plan->estado === 'activo') {
            return redirect()->back()
                ->withInput()
                ->with('error', '⚠️ No se pueden registrar nuevas actividades en un plan activo. Desactive el plan primero.');
        }

        if (!$plan->fechaEnRango($request->fecha)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La fecha debe estar dentro del rango del Plan Vacacional seleccionado');
        }

        $sede = Sede::with('encargado')->findOrFail($request->sede_id);
        $encargadoId = $sede->encargado ? $sede->encargado->id : null;

        $actividad = Actividades::create([
            'plan_vacacional_id' => $request->plan_vacacional_id,
            'actividad'    => $request->actividad,
            'hora'         => $request->hora,
            'fecha'        => $request->fecha,
            'descripcion'  => $request->descripcion,
            'objetivo'     => $request->objetivo,
            'cantidad'     => $request->cantidad,
            'sede_id'      => $request->sede_id,
            'responsable'  => $request->responsable,
            'encargado_id' => $encargadoId,
        ]);

        Bitacora::registrar('create', 'actividades', "Se registró la actividad '{$actividad->actividad}'.", null, ['actividad' => $actividad->actividad, 'fecha' => $actividad->fecha, 'hora' => $actividad->hora, 'cantidad' => $actividad->cantidad, 'responsable' => $actividad->responsable]);

        return redirect()->back()->with('success', 'Actividad registrada con éxito');
    }

    public function show($id)
    {
        try {
            $actividad = Actividades::with(['sede', 'encargado'])->find($id);
            
            if (!$actividad) {
                return response()->json(['error' => 'Actividad no encontrada'], 404);
            }
            
            $response = [
                'id' => $actividad->id,
                'actividad' => $actividad->actividad,
                'hora' => $actividad->hora,
                'fecha' => \Carbon\Carbon::parse($actividad->fecha)->format('Y-m-d'), // 👈 CAMBIO
                'descripcion' => $actividad->descripcion,
                'objetivo' => $actividad->objetivo,
                'cantidad' => $actividad->cantidad,
                'sede_id' => $actividad->sede_id,
                'responsable' => $actividad->responsable,
                'encargado_id' => $actividad->encargado_id,
                'sede' => null,
                'encargado' => null
            ];
            
            if ($actividad->sede) {
                $response['sede'] = [
                    'id' => $actividad->sede->id,
                    'nombre_completo' => $actividad->sede->comunidad . ' - ' . $actividad->sede->biblioteca,
                    'comunidad' => $actividad->sede->comunidad,
                    'biblioteca' => $actividad->sede->biblioteca
                ];
            }
            
            if ($actividad->encargado) {
                $response['encargado'] = [
                    'id' => $actividad->encargado->id,
                    'nombre' => $actividad->encargado->nombre,
                    'cedula' => $actividad->encargado->cedula,
                    'telefono' => $actividad->encargado->telefono
                ];
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar la actividad: ' . $e->getMessage()
            ], 500);
        }
    }

        public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'edit_actividad'    => 'required|string|max:255',
                'edit_hora'         => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'], // 👈 arreglo
                'edit_fecha'        => 'required|date',
                'edit_descripcion'  => 'nullable|string',
                'edit_objetivo'     => 'required|string',
                'edit_cantidad'     => 'required|integer|min:1',
                'edit_sede_id'      => 'required|exists:sedes,id',
                'edit_responsable'  => 'required|string|max:255',
            ]);

            $actividad = Actividades::findOrFail($id);
            
            $plan = PlanVacacional::findOrFail($actividad->plan_vacacional_id);
            if (!$plan->fechaEnRango($request->edit_fecha)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La fecha debe estar dentro del rango del Plan Vacacional seleccionado');
            }
            
            $hora = $request->edit_hora;
            if (strlen($hora) > 5) {
                $hora = substr($hora, 0, 5);
            }
            
            $sede = Sede::with('encargado')->findOrFail($request->edit_sede_id);
            $encargadoId = $sede->encargado ? $sede->encargado->id : null;
            
            $datosAntes = ['actividad' => $actividad->actividad, 'fecha' => $actividad->fecha, 'hora' => $actividad->hora, 'cantidad' => $actividad->cantidad, 'responsable' => $actividad->responsable];

            $actividad->update([
                'actividad'    => $request->edit_actividad,
                'hora'         => $hora,
                'fecha'        => $request->edit_fecha,
                'descripcion'  => $request->edit_descripcion,
                'objetivo'     => $request->edit_objetivo,
                'cantidad'     => $request->edit_cantidad,
                'sede_id'      => $request->edit_sede_id,
                'responsable'  => $request->edit_responsable,
                'encargado_id' => $encargadoId,
            ]);

            Bitacora::registrar('update', 'actividades', "Se actualizó la actividad '{$actividad->actividad}'.", $datosAntes, ['actividad' => $actividad->actividad, 'fecha' => $actividad->fecha, 'hora' => $actividad->hora, 'cantidad' => $actividad->cantidad, 'responsable' => $actividad->responsable]);

            return redirect()->back()->with('success', 'Actividad actualizada correctamente');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la actividad: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $actividad = Actividades::findOrFail($id);
            
            $plan = PlanVacacional::find($actividad->plan_vacacional_id);
            if ($plan && $plan->estado === 'activo') {
                return response()->json([
                    'success' => false, 
                    'message' => '⚠️ No se pueden eliminar actividades de un plan activo. Desactive el plan primero.'
                ], 400);
            }
            
            $datosAntes = ['actividad' => $actividad->actividad, 'fecha' => $actividad->fecha, 'hora' => $actividad->hora, 'cantidad' => $actividad->cantidad, 'responsable' => $actividad->responsable];

            $actividad->delete();

            Bitacora::registrar('delete', 'actividades', "Se eliminó la actividad '{$actividad->actividad}'.", $datosAntes, null);

            return response()->json([
                'success' => true, 
                'message' => 'Actividad eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error al eliminar la actividad: ' . $e->getMessage()
            ], 500);
        }
    }
}