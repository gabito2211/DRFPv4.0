<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Sede;

trait AsignaSede
{
    /**
     * Boot del Trait para engancharse al evento "creating" del Modelo Eloquent.
     */
    public static function bootAsignaSede()
    {
        // 1. FILTRADO AUTOMÁTICO (Global Scope)
        static::addGlobalScope('sede', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();

                // Si el usuario pertenece a una sede secundaria (sede_id != 1),
                // filtra exclusivamente por su sede_id.
                // Si el usuario es de la Sede Principal (sede_id == 1) o no tiene sede asignada,
                // no se aplica el filtro WHERE sede_id y puede ver los registros de todas las sedes.
                if (!empty($user->sede_id) && (int)$user->sede_id !== 1) {
                    $builder->where($builder->getModel()->getTable() . '.sede_id', $user->sede_id);
                }
            }
        });

        // 2. ASIGNACIÓN AUTOMÁTICA AL CREAR
        static::creating(function ($model) {
            if (Auth::check()) {
                $user = Auth::user();

                if (empty($model->sede_id) && !empty($user->sede_id)) {
                    $model->sede_id = $user->sede_id;
                }
            }
        });
    }

    /**
     * Relación con la Sede
     */
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
}
