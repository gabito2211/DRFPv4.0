<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlanVacacional;
use App\Actividades;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReportePlanVacacionalController extends Controller
{
    public function reportes(Request $request)
    {
        $tipo    = $request->get('tipo', 'general');
        $planId  = $request->get('plan_id');
        $search  = $request->get('search', '');

        $planes = PlanVacacional::orderBy('desde', 'desc')->get();

        $plan = null;

        if ($planId) {
            $plan = PlanVacacional::find($planId);
        } else {
            $plan = PlanVacacional::where('estado', PlanVacacional::ESTADO_ACTIVO)->first();

            if (!$plan && $planes->isNotEmpty()) {
                $plan = $planes->first();
            }
        }

        $actividades = collect();
        if ($plan) {
            $plan->actualizarEstadoAutomatico();
            $query = $plan->actividades()
                ->with(['sede.encargado', 'encargado'])
                ->orderBy('fecha', 'asc')
                ->orderBy('hora', 'asc');

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('actividad', 'LIKE', '%' . $search . '%')
                      ->orWhere('descripcion', 'LIKE', '%' . $search . '%')
                      ->orWhere('responsable', 'LIKE', '%' . $search . '%')
                      ->orWhereHas('sede', function ($s) use ($search) {
                          $s->where('comunidad', 'LIKE', '%' . $search . '%')
                            ->orWhere('biblioteca', 'LIKE', '%' . $search . '%');
                      });
                });
            }
            $actividades = $query->get();
        }

        $reporte = $this->construirReporte($tipo, $plan, $actividades);

        $urlCsv = route('reportes.plan.csv', array_filter(array(
            'tipo'    => $tipo,
            'plan_id' => $planId,
            'search'  => $search,
        )));

        return view('reportes.plan_vacacional_reporte', compact(
            'reporte', 'planes', 'plan', 'tipo', 'planId', 'search', 'urlCsv'
        ));
    }

    private function construirReporte($tipo, $plan, $actividades)
    {
        if (!$plan) {
            return array(
                'titulo'        => 'Reporte de Plan Vacacional',
                'columnas'      => array(),
                'numericas'     => array(),
                'grupos'        => array(),
                'etiquetaGrupo' => '',
                'resumen'       => 'No hay plan seleccionado.',
                'vacio'         => true,
            );
        }

        switch ($tipo) {
            case 'por_tipo':
                return $this->reportePorTipo($plan, $actividades);
            case 'por_sede':
                return $this->reportePorSede($plan, $actividades);
            case 'por_objetivo':
                return $this->reportePorObjetivo($plan, $actividades);
            case 'cronologico':
                return $this->reporteCronologico($plan, $actividades);
            default:
                return $this->reporteGeneral($plan, $actividades);
        }
    }

    /**
     * Agrupa y ordena SIN perder los modelos Eloquent.
     * Devuelve un array de [clave => Collection de modelos].
     */
    private function agruparYOrdenar($actividades, $callback)
    {
        $grupos = array();

        foreach ($actividades as $actividad) {
            $clave = call_user_func($callback, $actividad);
            if (!isset($grupos[$clave])) {
                $grupos[$clave] = collect();
            }
            $grupos[$clave]->push($actividad);
        }

        ksort($grupos);
        return $grupos;
    }

    private function reporteGeneral($plan, $actividades)
    {
        $grupos = array();

        $agrupado = $this->agruparYOrdenar($actividades, function ($a) {
            return Carbon::parse($a->fecha)->format('Y-m-d');
        });

        foreach ($agrupado as $fecha => $items) {
            $fechaTxt = Carbon::parse($fecha)->format('d/m/Y');
            $totalPersonas = $items->sum('cantidad');
            $grupos[] = array(
                'nombre' => ucfirst($fechaTxt),
                'nota'   => $items->count() . ' actividades · ' . $totalPersonas . ' participantes',
                'filas'  => $items->map(function ($a) {
                    return $this->filaActividad($a);
                })->toArray(),
            );
        }

        return array(
            'titulo'        => "Listado General - {$plan->nombre}",
            'columnas'      => array('Hora', 'Tipo', 'Descripción', 'Sede', 'Responsable', 'Encargado', 'Objetivo', 'Cant.'),
            'numericas'     => array(7),
            'grupos'        => $grupos,
            'etiquetaGrupo' => 'Fecha',
            'resumen'       => $this->resumenGeneral($plan, $actividades),
            'vacio'         => $actividades->isEmpty(),
        );
    }

    private function reportePorTipo($plan, $actividades)
    {
        $grupos = array();

        $agrupado = $this->agruparYOrdenar($actividades, function ($a) {
            return $a->actividad;
        });

        foreach ($agrupado as $tipo => $items) {
            $totalPersonas = $items->sum('cantidad');
            $grupos[] = array(
                'nombre' => ucfirst($tipo),
                'nota'   => $items->count() . ' registros · ' . $totalPersonas . ' participantes',
                'filas'  => $items->map(function ($a) {
                    return $this->filaActividad($a);
                })->toArray(),
            );
        }

        return array(
            'titulo'        => "Reporte por Tipo de Actividad - {$plan->nombre}",
            'columnas'      => array('Fecha', 'Hora', 'Descripción', 'Sede', 'Responsable', 'Encargado', 'Objetivo', 'Cant.'),
            'numericas'     => array(7),
            'grupos'        => $grupos,
            'etiquetaGrupo' => 'Tipo de actividad',
            'resumen'       => $this->resumenGeneral($plan, $actividades),
            'vacio'         => $actividades->isEmpty(),
        );
    }

    private function reportePorSede($plan, $actividades)
    {
        $grupos = array();

        $agrupado = $this->agruparYOrdenar($actividades, function ($a) {
            return $a->sede
                ? $a->sede->comunidad . ' - ' . $a->sede->biblioteca
                : 'Sin sede';
        });

        foreach ($agrupado as $sede => $items) {
            $totalPersonas = $items->sum('cantidad');
            $grupos[] = array(
                'nombre' => $sede,
                'nota'   => $items->count() . ' actividades · ' . $totalPersonas . ' participantes',
                'filas'  => $items->map(function ($a) {
                    return $this->filaActividad($a);
                })->toArray(),
            );
        }

        return array(
            'titulo'        => "Reporte por Sede - {$plan->nombre}",
            'columnas'      => array('Fecha', 'Hora', 'Tipo', 'Descripción', 'Responsable', 'Encargado', 'Objetivo', 'Cant.'),
            'numericas'     => array(7),
            'grupos'        => $grupos,
            'etiquetaGrupo' => 'Sede',
            'resumen'       => $this->resumenGeneral($plan, $actividades),
            'vacio'         => $actividades->isEmpty(),
        );
    }

    private function reportePorObjetivo($plan, $actividades)
    {
        $grupos = array();

        $agrupado = $this->agruparYOrdenar($actividades, function ($a) {
            return $a->objetivo;
        });

        foreach ($agrupado as $obj => $items) {
            $totalPersonas = $items->sum('cantidad');
            $grupos[] = array(
                'nombre' => ucfirst($obj),
                'nota'   => $items->count() . ' actividades · ' . $totalPersonas . ' participantes',
                'filas'  => $items->map(function ($a) {
                    return $this->filaActividad($a);
                })->toArray(),
            );
        }

        return array(
            'titulo'        => "Reporte por Objetivo - {$plan->nombre}",
            'columnas'      => array('Fecha', 'Hora', 'Tipo', 'Descripción', 'Sede', 'Responsable', 'Encargado', 'Cant.'),
            'numericas'     => array(7),
            'grupos'        => $grupos,
            'etiquetaGrupo' => 'Objetivo',
            'resumen'       => $this->resumenGeneral($plan, $actividades),
            'vacio'         => $actividades->isEmpty(),
        );
    }

    private function reporteCronologico($plan, $actividades)
    {
        $filas = $actividades->map(function ($a) {
            return array(
                Carbon::parse($a->fecha)->format('d/m/Y'),
                $a->hora ? Carbon::parse($a->hora)->format('H:i') : '—',
                ucfirst($a->actividad),
                $a->descripcion ? Str::limit($a->descripcion, 60) : '—',
                $a->sede ? $a->sede->comunidad . ' - ' . $a->sede->biblioteca : 'Sin sede',
                $a->responsable ? $a->responsable : '—',
                $a->encargado ? $a->encargado->nombre : 'N/A',
                $a->objetivo ? ucfirst($a->objetivo) : '—',
                $a->cantidad,
            );
        })->toArray();

        return array(
            'titulo'        => "Cronograma Completo - {$plan->nombre}",
            'columnas'      => array('Fecha', 'Hora', 'Tipo', 'Descripción', 'Sede', 'Responsable', 'Encargado', 'Objetivo', 'Cant.'),
            'numericas'     => array(8),
            'grupos'        => array(
                array(
                    'nombre' => null,
                    'nota'   => null,
                    'filas'  => $filas,
                )
            ),
            'etiquetaGrupo' => '',
            'resumen'       => $this->resumenGeneral($plan, $actividades),
            'vacio'         => $actividades->isEmpty(),
        );
    }

    /**
     * Recibe SIEMPRE un modelo Actividades (nunca un array)
     */
    private function filaActividad($a)
    {
        return array(
            $a->hora ? Carbon::parse($a->hora)->format('H:i') : '—',
            ucfirst($a->actividad),
            $a->descripcion ? Str::limit($a->descripcion, 55) : '—',
            $a->sede ? $a->sede->comunidad . ' - ' . $a->sede->biblioteca : 'Sin sede',
            $a->responsable ? $a->responsable : '—',
            $a->encargado ? $a->encargado->nombre : 'N/A',
            $a->objetivo ? ucfirst($a->objetivo) : '—',
            $a->cantidad,
        );
    }

    private function resumenGeneral($plan, $actividades)
    {
        $totalAct  = $actividades->count();
        $totalPers = $actividades->sum('cantidad');
        $dias      = $plan->getDiasDuracion();
        $diasConAct = $actividades->pluck('fecha')
            ->map(function ($f) {
                return Carbon::parse($f)->format('Y-m-d');
            })
            ->unique()->count();

        return "Plan: {$plan->nombre} · Estado: " . strtoupper($plan->estado)
            . " · Duración: {$dias} días · Días con actividad: {$diasConAct}"
            . " · Total actividades: {$totalAct} · Total participantes: {$totalPers}";
    }

    public function exportCsv(Request $request)
    {
        $tipo   = $request->get('tipo', 'general');
        $planId = $request->get('plan_id');
        $search = $request->get('search', '');

        $plan = $planId ? PlanVacacional::find($planId) : null;
        if (!$plan) {
            return redirect()->route('reportes.plan.index')
                ->with('error', 'Debe seleccionar un plan vacacional para exportar.');
        }

        $plan->actualizarEstadoAutomatico();
        $query = $plan->actividades()
            ->with(['sede.encargado', 'encargado'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('actividad', 'LIKE', '%' . $search . '%')
                  ->orWhere('descripcion', 'LIKE', '%' . $search . '%')
                  ->orWhere('responsable', 'LIKE', '%' . $search . '%');
            });
        }
        $actividades = $query->get();

        $filename = 'reporte_plan_' . Str::slug($plan->nombre) . '_' . date('Y-m-d') . '.csv';
        $headers = array(
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        );

        $callback = function () use ($plan, $actividades) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, array('REPORTE DE PLAN VACACIONAL'));
            fputcsv($file, array('Plan:', $plan->nombre));
            fputcsv($file, array('Desde:', Carbon::parse($plan->desde)->format('d/m/Y')));
            fputcsv($file, array('Hasta:', Carbon::parse($plan->hasta)->format('d/m/Y')));
            fputcsv($file, array('Estado:', strtoupper($plan->estado)));
            fputcsv($file, array('Total actividades:', $actividades->count()));
            fputcsv($file, array('Total participantes:', $actividades->sum('cantidad')));
            fputcsv($file, array());

            fputcsv($file, array(
                'N°', 'Fecha', 'Hora', 'Tipo', 'Descripción', 'Sede',
                'Responsable', 'Encargado', 'Objetivo', 'Cantidad'
            ));

            $i = 1;
            foreach ($actividades as $a) {
                fputcsv($file, array(
                    $i++,
                    Carbon::parse($a->fecha)->format('d/m/Y'),
                    $a->hora ? Carbon::parse($a->hora)->format('H:i') : '',
                    $a->actividad,
                    $a->descripcion,
                    $a->sede ? $a->sede->comunidad . ' - ' . $a->sede->biblioteca : 'Sin sede',
                    $a->responsable,
                    $a->encargado ? $a->encargado->nombre : 'N/A',
                    $a->objetivo,
                    $a->cantidad,
                ));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}