<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class HistorialDanoLibro extends Model
{
    use AsignaSede;

    protected $table = 'historial_danos_libros';

    protected $fillable = [
        'libro_id',
        'prestamo_id',
        'sancion_id',
        'persona_id',
        'tipo_evento',
        'estado',
        'descripcion',
        'solucion',
        'fecha_evento',
        'fecha_resolucion',
        'sede_id'
    ];

    protected $dates = ['fecha_evento', 'fecha_resolucion', 'created_at', 'updated_at'];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'libro_id', 'cota');
    }

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'prestamo_id');
    }

    public function sancion()
    {
        return $this->belongsTo(Sancion::class, 'sancion_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
}
