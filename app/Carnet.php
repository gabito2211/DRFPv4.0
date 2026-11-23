<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class Carnet extends Model
{
    // Campos que permitimos registrar
    use AsignaSede;
    protected $fillable = [
        'persona_id', 
        'num_carnet', 
        'foto', 
        'profesion', 
        'fecha_emision', 
        'fecha_vencimiento', 
        'estado',
        'sede_id'
    ];

    // Relación con la tabla Persona
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
}
