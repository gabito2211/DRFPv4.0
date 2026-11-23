<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class Pasillo extends Model
{
    use AsignaSede;

    protected $table = 'pasillos';

    protected $fillable = [
        'numero',
        'sala_id',
        'sede_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($pasillo) {
            if ($pasillo->sala_id) {
                $sala = Sala::withoutGlobalScope('sede')->find($pasillo->sala_id);

                if ($sala) {
                    $pasillo->sede_id = $sala->sede_id;
                }
            }
        });
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }

    public function estanterias()
    {
        return $this->hasMany(Estanteria::class, 'pasillo_id');
    }
}
