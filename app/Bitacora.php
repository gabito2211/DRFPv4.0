<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Bitacora extends Model
{
    protected $table = 'bitacoras';

    protected $fillable = [
        'user_id',
        'user_name',
        'accion',
        'modulo',
        'descripcion',
        'datos_antes',
        'datos_despues',
        'ip_address',
        'user_agent',
    ];

    public $timestamps = true;

    public function usuario()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function registrar($accion, $modulo, $descripcion, $datosAntes = null, $datosDespues = null, $usuario = null)
    {
        if (!$usuario) {
            $usuario = Auth::user();
        }

        return self::create([
            'user_id' => $usuario ? $usuario->id : null,
            'user_name' => $usuario ? $usuario->name : 'Sistema',
            'accion' => $accion,
            'modulo' => $modulo,
            'descripcion' => $descripcion,
            'datos_antes' => $datosAntes ? json_encode($datosAntes, JSON_UNESCAPED_UNICODE) : null,
            'datos_despues' => $datosDespues ? json_encode($datosDespues, JSON_UNESCAPED_UNICODE) : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }
}
