<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class Sala extends Model
{
    use AsignaSede;

    protected $table = 'salas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'sede_id',
    ];

    public function pasillos()
    {
        return $this->hasMany(Pasillo::class, 'sala_id');
    }
}
