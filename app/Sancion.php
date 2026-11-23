<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class Sancion extends Model
{
    protected $table = 'sanciones';

    use AsignaSede;

    protected $fillable = [
        'prestamo_id',
        'persona_id',
        'tipo_dano',
        'estado',
        'observacion_resolucion',
        'fecha_resolucion',
        'sede_id'
    ];

    public function historialDanos()
    {
        return $this->hasMany(HistorialDanoLibro::class, 'sancion_id');
    }

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'prestamo_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}