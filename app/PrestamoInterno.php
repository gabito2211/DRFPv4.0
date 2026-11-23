<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class PrestamoInterno extends Model
{   
    use AsignaSede;
    protected $table = 'prestamos_internos';

    protected $fillable = [
        'cedula',
        'nombre_lector',
        'cota_libro',
        'titulo_libro',
        'fecha',
        'hora_prestamo',
        'hora_devolucion',
        'estado',
        'sede_id'
    ];
}