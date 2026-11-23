<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class Prestamo extends Model
{
    use AsignaSede;
    protected $table = 'prestamo';
    
    protected $fillable = [
        'lector_id', 
        'libro_id',
        'fecha_salida',
        'fecha_devolucion',
        'condiciones_salida',  
        'condiciones_regreso',
        'estatus',
        'sede_id'  
    ];

    // Relación con el inventario
    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'libro_id', 'cota');
    }

    // Relación con la persona a través del carnet
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'lector_id', 'cedula');
    }

    // Relación con el carnet del lector
    public function carnet()
    {
        return $this->belongsTo(Carnet::class, 'lector_id', 'num_carnet');
    }

    // Método para verificar si el lector tiene carnet válido
    public function lectorTieneCarnetValido()
    {
        $persona = Persona::where('cedula', $this->lector_id)->first();
        
        if (!$persona) {
            return false;
        }
        
        return Carnet::where('persona_id', $persona->id)
                     ->where('estado', 'Activo')
                     ->where('fecha_vencimiento', '>=', \Carbon\Carbon::now())
                     ->exists();
    }
        public function sancion()
    {
        return $this->hasOne(Sancion::class, 'prestamo_id');
    }
}