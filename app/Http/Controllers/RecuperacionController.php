<?php

namespace App\Http\Controllers;

use App\Bitacora;
use App\CodigoRecuperacion;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class RecuperacionController extends Controller
{
    const INTENTOS_MAXIMOS = 5;
    const BLOQUEO_MINUTOS = 15;

    public function mostrarFormulario()
    {
        return view('recuperar_contrasena');
    }

    public function recuperar(Request $request)
    {
        $this->validate($request, array(
            'email' => 'required|email',
            'codigo' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ));

        $email = strtolower(trim($request->input('email')));
        $clave = $this->claveBloqueo($request, $email);

        if (Cache::get($clave) >= self::INTENTOS_MAXIMOS) {
            return redirect()->route('recuperar.contrasena')
                ->with('error', 'Demasiados intentos fallidos. Espera ' . self::BLOQUEO_MINUTOS . ' minutos antes de volver a intentarlo.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->registrarIntento($clave, $email);
            return redirect()->route('recuperar.contrasena')
                ->withInput($request->only('email'))
                ->with('error', 'Los datos no coinciden con ningún usuario registrado.');
        }

        $codigo = CodigoRecuperacion::buscarValido($user->id, $request->input('codigo'));

        if ($codigo === null || !$codigo->esUtilizable()) {
            $this->registrarIntento($clave, $email);

            if ($codigo !== null) {
                $codigo->intentos = $codigo->intentos + 1;
                $codigo->save();
            }

            $motivo = 'código inválido';
            if ($codigo !== null) {
                $motivo = 'código ' . $codigo->estado();
            }

            Bitacora::registrar(
                'recuperacion-fallida',
                'autenticacion',
                "Intento fallido de recuperación de contraseña para '{$email}': {$motivo}."
            );

            return redirect()->route('recuperar.contrasena')
                ->withInput($request->only('email'))
                ->with('error', 'El código no es válido, ya expiró o ya fue utilizado.');
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        $codigo->usado = true;
        $codigo->usado_en = \Carbon\Carbon::now();
        $codigo->save();

        Cache::forget($clave);

        Bitacora::registrar(
            'recuperacion-exitosa',
            'autenticacion',
            "El usuario '{$user->name}' recuperó su contraseña con un código generado por '{$codigo->generado_por}'.",
            null,
            null,
            $user
        );

        return redirect()->route('login')
            ->with('success', 'Tu contraseña fue actualizada. Ya puedes iniciar sesión.');
    }

    protected function claveBloqueo(Request $request, $email)
    {
        return 'recuperacion:' . md5($email . '|' . $request->ip());
    }

    protected function registrarIntento($clave, $email)
    {
        $intentos = Cache::get($clave, 0) + 1;
        Cache::put($clave, $intentos, self::BLOQUEO_MINUTOS);

        if ($intentos >= self::INTENTOS_MAXIMOS) {
            Bitacora::registrar(
                'recuperacion-bloqueada',
                'autenticacion',
                "Se bloqueó la recuperación de contraseña para '{$email}' por superar los intentos permitidos."
            );
        }
    }
}