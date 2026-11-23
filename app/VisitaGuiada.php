<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class VisitaGuiada extends Model
{
    use AsignaSede;
    protected $table = 'visitas_guiadas';
    protected $fillable = [
        'institucion',
        'responsable',
        'encargado',
        'cantidad',
        'fecha_visita',
        'hora',
        'tipo_recorrido',
        'sala_id',
        'sede_id'
    ];

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }
}
