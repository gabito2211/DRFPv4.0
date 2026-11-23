<?php
// app/Actividades.php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\AsignaSede;

class Actividades extends Model
{
    use AsignaSede;
    protected $table = 'actividades';
    
    protected $fillable = [
        'plan_vacacional_id',
        'actividad',
        'hora', 
        'fecha',
        'descripcion',
        'objetivo',
        'cantidad',
        'sede_id',
        'responsable',
        'encargado_id',
    ];
    
    protected $dates = ['fecha'];
    
    public function planVacacional()
    {
        return $this->belongsTo(PlanVacacional::class, 'plan_vacacional_id');
    }
    
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
    
    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'encargado_id');
    }
    
    public function getFechaFormateadaAttribute()
    {
        return $this->fecha ? Carbon::parse($this->fecha)->format('d/m/Y') : null;
    }
    
    public function getHoraFormateadaAttribute()
    {
        return $this->hora ? Carbon::parse($this->hora)->format('H:i') : null;
    }
}