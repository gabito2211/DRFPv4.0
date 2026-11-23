<?php
// app/Http/Controllers/PlanVacacionalController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlanVacacional;
use App\Actividades;
use App\Sede;
use App\Bitacora;
use Carbon\Carbon;

class PlanVacacionalController extends Controller
{
    /**
     * Display the main plans view.
     */
    public function index()
    {
        // Obtener todos los planes y actualizar estados automáticamente
        $planesActivos = PlanVacacional::all()->map(function($plan) {
            $plan->actualizarEstadoAutomatico();
            return $plan;
        });
        
        // Buscar plan activo
        $planVigente = PlanVacacional::where('estado', PlanVacacional::ESTADO_ACTIVO)->first();
        
        return view('planVacacional', compact('planesActivos', 'planVigente'));
    }

    /**
     * Store a newly created plan.
     */
    public function storePlan(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:255',
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
        ]);

        $estado = $request->has('activo') ? PlanVacacional::ESTADO_ACTIVO : PlanVacacional::ESTADO_INACTIVO;

        // Si se activa, desactivar otros planes activos
        if ($estado === PlanVacacional::ESTADO_ACTIVO) {
            PlanVacacional::where('estado', PlanVacacional::ESTADO_ACTIVO)
                ->update(['estado' => PlanVacacional::ESTADO_INACTIVO]);
        }

        $plan = PlanVacacional::create([
            'nombre' => $request->nombre,
            'desde' => $request->desde,
            'hasta' => $request->hasta,
            'estado' => $estado,
        ]);

        Bitacora::registrar('create', 'planes_vacacionales', "Se registró el plan vacacional '{$plan->nombre}'.", null, ['nombre' => $plan->nombre, 'desde' => $plan->desde, 'hasta' => $plan->hasta]);

        return redirect()->route('planes.index')
            ->with('success', 'Plan vacacional creado con éxito.');
    }

    /**
     * Update the specified plan.
     */
    public function updatePlan(Request $request, $id)
    {
        $plan = PlanVacacional::findOrFail($id);
        
        // No permitir editar planes activos o completados
        $plan->actualizarEstadoAutomatico();
        if ($plan->estado === PlanVacacional::ESTADO_ACTIVO) {
            return redirect()->back()->withErrors(['error' => 'No se puede editar un plan activo.']);
        }
        if ($plan->estado === PlanVacacional::ESTADO_COMPLETADO) {
            return redirect()->back()->withErrors(['error' => 'No se puede editar un plan completado.']);
        }

        $this->validate($request, [
            'edit_nombre_plan' => 'required|string|max:255',
            'edit_desde_plan' => 'required|date',
            'edit_hasta_plan' => 'required|date|after_or_equal:edit_desde_plan',
        ]);

        $estado = $request->has('edit_activo_plan') ? PlanVacacional::ESTADO_ACTIVO : PlanVacacional::ESTADO_INACTIVO;

        // Si se activa, desactivar otros planes activos
        if ($estado === PlanVacacional::ESTADO_ACTIVO) {
            PlanVacacional::where('estado', PlanVacacional::ESTADO_ACTIVO)
                ->where('id', '!=', $id)
                ->update(['estado' => PlanVacacional::ESTADO_INACTIVO]);
        }

        $plan->update([
            'nombre' => $request->edit_nombre_plan,
            'desde' => $request->edit_desde_plan,
            'hasta' => $request->edit_hasta_plan,
            'estado' => $estado,
        ]);

        Bitacora::registrar('update', 'planes_vacacionales', "Se actualizó el plan vacacional '{$plan->nombre}'.", null, ['nombre' => $plan->nombre, 'desde' => $plan->desde, 'hasta' => $plan->hasta]);

        return redirect()->route('planes.index')
            ->with('success', 'Plan vacacional actualizado con éxito.');
    }

    /**
     * Activate a plan.
     */
    public function activarPlan($id)
    {
        try {
            $plan = PlanVacacional::findOrFail($id);
            $plan->actualizarEstadoAutomatico();
            
            if ($plan->estado === PlanVacacional::ESTADO_COMPLETADO) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ No se puede activar un plan completado.'
                ], 400);
            }
            
            if ($plan->estado === PlanVacacional::ESTADO_ACTIVO) {
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ El plan ya está activo.'
                ], 400);
            }

            // Desactivar otros planes activos
            PlanVacacional::where('estado', PlanVacacional::ESTADO_ACTIVO)
                ->update(['estado' => PlanVacacional::ESTADO_INACTIVO]);

            $plan->estado = PlanVacacional::ESTADO_ACTIVO;
            $plan->save();

            Bitacora::registrar('update', 'planes_vacacionales', "Se activó el plan vacacional '{$plan->nombre}'.");

            return response()->json([
                'success' => true,
                'message' => '✅ Plan activado correctamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al activar el plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified plan.
     */
    public function destroyPlan($id)
    {
        try {
            $plan = PlanVacacional::findOrFail($id);
            $plan->actualizarEstadoAutomatico();
            
            if ($plan->estado === PlanVacacional::ESTADO_ACTIVO) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ No se puede eliminar un plan activo.'
                ], 400);
            }
            
            if ($plan->estado === PlanVacacional::ESTADO_COMPLETADO) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ No se puede eliminar un plan completado.'
                ], 400);
            }

            $datosAntes = ['nombre' => $plan->nombre, 'desde' => $plan->desde, 'hasta' => $plan->hasta, 'estado' => $plan->estado];

            // Eliminar actividades asociadas
            $plan->actividades()->delete();
            $plan->delete();

            Bitacora::registrar('delete', 'planes_vacacionales', "Se eliminó el plan vacacional '{$plan->nombre}'.", $datosAntes, null);

            return response()->json([
                'success' => true,
                'message' => '✅ Plan eliminado correctamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get plan details for AJAX requests.
     */
    public function detallesPlan($id)
    {
        try {
            $plan = PlanVacacional::with('actividades')->findOrFail($id);
            $plan->actualizarEstadoAutomatico();
            
            $actividades = $plan->actividades->map(function($act) {
                return [
                    'actividad' => $act->actividad,
                    'dia' => $act->dia,
                    'hora' => $act->hora,
                    'fecha' => $act->fecha,
                    'objetivo' => $act->objetivo,
                    'cantidad' => $act->cantidad,
                    'responsable' => $act->responsable,
                    'descripcion' => $act->descripcion,
                    'sede' => $act->sede ? $act->sede->nombre : null,
                ];
            });

            return response()->json([
                'id' => $plan->id,
                'nombre' => $plan->nombre,
                'desde' => $plan->desde,
                'hasta' => $plan->hasta,
                'estado' => $plan->estado,
                'dias' => $plan->getDiasDuracion(),
                'total_actividades' => $plan->actividades->count(),
                'actividades' => $actividades,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Plan no encontrado: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get plan data for editing.
     */
    public function showPlan($id)
    {
        try {
            $plan = PlanVacacional::findOrFail($id);
            $plan->actualizarEstadoAutomatico();
            
            return response()->json([
                'id' => $plan->id,
                'nombre' => $plan->nombre,
                'desde' => $plan->desde,
                'hasta' => $plan->hasta,
                'estado' => $plan->estado,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Plan no encontrado'], 404);
        }
    }
}