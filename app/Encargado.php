<?php
// app/Encargado.php (NO en app/Models/)

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class Encargado extends Model
{
    use AsignaSede;
    protected $table = 'encargados';
    
    // Si usas 'id' como primary key (por defecto), no necesitas especificarlo
    // Pero si quieres ser explícito:
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'cedula', 
        'nombre', 
        'telefono',
        'telefono2',
        'sede_id'
    ];

    // Si tu tabla tiene timestamps (created_at, updated_at)
    public $timestamps = true;
}