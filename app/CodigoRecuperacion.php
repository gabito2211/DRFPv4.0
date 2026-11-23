<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CodigoRecuperacion extends Model
{
    const DURACION_MINUTOS = 30;
    const MAX_INTENTOS = 5;

    protected $table = 'codigos_recuperacion';

    protected $fillable = [
        'user_id',
        'codigo_hash',
        'generado_por',
        'intentos',
        'expira',
        'usado',
        'usado_en',
    ];

    protected $dates = ['expira', 'usado_en'];

    const ALFABETO = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function generar($userId, $generadoPor = null, $codigo = null)
    {
        self::where('user_id', $userId)
            ->where('usado', false)
            ->update(array('usado' => true, 'usado_en' => Carbon::now()));

        if ($codigo === null) {
            $codigo = self::crearCodigo();
        }

        return self::create(array(
            'user_id' => $userId,
            'codigo_hash' => Hash::make(self::normalizar($codigo)),
            'generado_por' => $generadoPor,
            'intentos' => 0,
            'expira' => Carbon::now()->addMinutes(self::DURACION_MINUTOS),
            'usado' => false,
        ));
    }

    public static function crearCodigo()
    {
        $bytes = openssl_random_pseudo_bytes(8);
        $codigo = '';

        for ($i = 0; $i < 8; $i++) {
            $codigo .= self::ALFABETO[ord($bytes[$i]) % strlen(self::ALFABETO)];
        }

        return substr($codigo, 0, 4) . '-' . substr($codigo, 4, 4);
    }

    public static function normalizar($codigo)
    {
        return strtoupper(str_replace(array('-', ' '), '', $codigo));
    }

    public function estaExpirado()
    {
        return $this->expira !== null && Carbon::now()->greaterThan($this->expira);
    }

    public function esUtilizable()
    {
        return !$this->usado && !$this->estaExpirado() && $this->intentos < self::MAX_INTENTOS;
    }

    public static function buscarValido($userId, $codigoIngresado)
    {
        $normalizado = self::normalizar($codigoIngresado);

        if ($normalizado === '') {
            return null;
        }

        $codigos = self::where('user_id', $userId)
            ->where('usado', false)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($codigos as $codigo) {
            if (Hash::check($normalizado, $codigo->codigo_hash)) {
                return $codigo;
            }
        }

        return null;
    }

    public static function activosDe($userId)
    {
        return self::where('user_id', $userId)
            ->where('usado', false)
            ->get();
    }

    public function estado()
    {
        if ($this->usado) {
            return 'usado';
        }

        if ($this->estaExpirado()) {
            return 'expirado';
        }

        if ($this->intentos >= self::MAX_INTENTOS) {
            return 'agotado';
        }

        return 'activo';
    }

    public function minutosRestantes()
    {
        if ($this->expira === null || $this->estaExpirado()) {
            return 0;
        }

        return (int) Carbon::now()->diffInMinutes($this->expira, false);
    }
}