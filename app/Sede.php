<?php
// app/Sede.php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $fillable = [
        'comunidad', 
        'biblioteca', 
        'direccion',
        'encargado_id', // ← CLAVE FORÁNEA
        'cedula'        // ← LO MANTENEMOS PERO NO LO USAMOS PARA LA RELACIÓN
    ];

    // Relación con Encargado usando 'encargado_id' como clave foránea
    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'encargado_id');
        //                                         ↑
        //                                   campo en sede (encargado_id)
    }

    public function usuarios()
    {
        return $this->hasMany(User::class, 'sede_id');
    }

    public function actividades()
    {
        return $this->hasMany(Actividades::class, 'sede_id');
    }

    public function getNombreCompletoAttribute()
    {
        return $this->comunidad . ' - ' . $this->biblioteca;
    }
}