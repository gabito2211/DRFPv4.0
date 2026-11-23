<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','rol', 'sede_id', 'estado',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getNombreRolAttribute()
    {
        $roles = [
            1 => 'Administrador',
            2 => 'Recepcionista',
            3 => 'Almacenista'
        ];
        
    return isset($roles[$this->rol]) ? $roles[$this->rol] : 'Sin Rol';    
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
    
}
