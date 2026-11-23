<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class Visita extends Model // <--- Asegúrate que NO tenga una S al final
{
    use AsignaSede;
    protected $table = 'visitas'; // Esto le dice a Laravel que la tabla se llama visitas aunque el modelo sea Visita

    protected $fillable = [
        'persona_id', 'fecha', 'hora_entrada', 'departamento', 'sala_id', 'sede_id'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }
}