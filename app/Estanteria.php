<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class Estanteria extends Model
{
    use AsignaSede;

    protected $table = 'estanterias';

    protected $fillable = [
        'numero',
        'genero',
        'pasillo_id',
        'sede_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($estanteria) {
            if ($estanteria->pasillo_id) {
                $pasillo = Pasillo::withoutGlobalScope('sede')->find($estanteria->pasillo_id);

                if ($pasillo) {
                    $estanteria->sede_id = $pasillo->sede_id;
                }
            }
        });
    }

    public function pasillo()
    {
        return $this->belongsTo(Pasillo::class, 'pasillo_id');
    }
}
