<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Traits\AsignaSede;

class Persona extends Model
{   //use AsignaSede;
    protected $fillable = ['cedula', 'nombre', 'sexo', 'telefono','telefono2','correo', 'comunidad', 'direccion','sede_id'];

    // Una persona tiene muchas visitas
    public function visitas() {
        return $this->hasMany(Visita::class);
    }

    // Los préstamos se guardan con la cédula en "lector_id" (texto libre, no un id numérico)
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'lector_id', 'cedula');
    }

    // Método para verificar si la persona tiene préstamos pendientes por devolver
    public function tienePrestamosPendientes()
    {
        return $this->prestamos()->where('estatus', 'pendiente')->exists();
    }
}
