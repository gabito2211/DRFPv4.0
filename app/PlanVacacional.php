<?php
// app/PlanVacacional.php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\AsignaSede;

class PlanVacacional extends Model
{
    use AsignaSede;
    protected $table = 'planes_vacacionales';
    
    protected $fillable = [
        'nombre', 'desde', 'hasta', 'estado', 'sede_id'
    ];

    // Constantes para los estados
    const ESTADO_ACTIVO = 'activo';
    const ESTADO_INACTIVO = 'inactivo';
    const ESTADO_COMPLETADO = 'completado';

    protected $dates = [
        'desde',
        'hasta',
        'created_at',
        'updated_at'
    ];

    public function actividades()
    {
        return $this->hasMany(Actividades::class, 'plan_vacacional_id');
    }

    /**
     * Verifica si una fecha está dentro del rango del plan
     */
    public function fechaEnRango($fecha)
    {
        $fechaCarbon = Carbon::parse($fecha);
        $desde = Carbon::parse($this->desde);
        $hasta = Carbon::parse($this->hasta);
        
        return $fechaCarbon->between($desde, $hasta);
    }

    /**
     * Verificar si el plan está completado
     */
    public function isCompletado()
    {
        return $this->estado === self::ESTADO_COMPLETADO;
    }

    /**
     * Verificar si el plan está activo
     */
    public function isActivo()
    {
        return $this->estado === self::ESTADO_ACTIVO;
    }

    /**
     * Verificar si el plan está inactivo
     */
    public function isInactivo()
    {
        return $this->estado === self::ESTADO_INACTIVO;
    }

    /**
     * Actualizar el estado automáticamente basado en la fecha
     * Retorna true si se actualizó el estado
     */
    public function actualizarEstadoAutomatico()
    {
        // Solo procesar si está activo
        if ($this->estado !== self::ESTADO_ACTIVO) {
            return false;
        }
        
        $hasta = Carbon::parse($this->hasta);
        
        // Si la fecha ya pasó, marcarlo como completado
        if ($hasta->isPast()) {
            $this->estado = self::ESTADO_COMPLETADO;
            $this->save();
            return true;
        }
        
        return false;
    }

    /**
     * Obtener los días de duración del plan
     */
    public function getDiasDuracion()
    {
        if (!$this->desde || !$this->hasta) {
            return 0;
        }
        return Carbon::parse($this->desde)->diffInDays(Carbon::parse($this->hasta)) + 1;
    }

    /**
     * Scope para obtener solo planes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }

    /**
     * Scope para obtener solo planes inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->where('estado', self::ESTADO_INACTIVO);
    }

    /**
     * Scope para obtener solo planes completados
     */
    public function scopeCompletados($query)
    {
        return $query->where('estado', self::ESTADO_COMPLETADO);
    }
}