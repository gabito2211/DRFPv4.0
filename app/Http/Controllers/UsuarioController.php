<?php

namespace App\Http\Controllers;

use App\User;
use App\Sede;
use App\Bitacora;
use App\RespaldoBaseDatos;
use App\CodigoRecuperacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Validator;

class UsuarioController extends Controller
{
    /**
     * Mostrar todos los usuarios del sistema
     */

    public function login(Request $request)
{
    // 1. Validar los datos de entrada
    $this->validate($request, [
        'email'    => 'required|email',
        'password' => 'required|string',
        'sede_id'  => 'nullable|exists:sedes,id',
    ]);

    // 2. Intentar autenticar credenciales básicas
    $credenciales = $request->only('email', 'password');

    if (Auth::attempt($credenciales)) {
        $user = Auth::user();

            // Validar si está inhabilitado
        if ($user->estado === 'inhabilitado') {
        Auth::logout();
        $request->session()->invalidate();
        Bitacora::registrar('login-fallido', 'autenticacion', "Intento de acceso a una cuenta inhabilitada: '{$user->name}' ({$user->email}).", null, null, $user);
        return redirect()->route('login')
            ->withInput($request->only('email'))
            ->with('error', 'Su cuenta se encuentra inhabilitada. Contacte al administrador.');
        }

        // 3. VALIDACIÓN DE SEDE (Compatible con Laravel 5.4)
        $sedeSeleccionada = $request->input('sede_id');

        // Si el usuario tiene una sede asignada en BD y se envió una sede en el login
        // (Aplica para todos los usuarios: solo pueden ingresar si seleccionaron su sede asignada)
        if ($user->sede_id !== null && !empty($sedeSeleccionada)) {
            
            // Si la sede seleccionada NO coincide con la sede asignada al usuario
            if ((int)$sedeSeleccionada !== (int)$user->sede_id) {
                
                Auth::logout();
                $request->session()->invalidate();

                $sedeCorrecta = $user->sede ? $user->sede->biblioteca : 'su sede asignada';

                Bitacora::registrar('login-fallido', 'autenticacion', "Acceso denegado por sede incorrecta: '{$user->name}' ({$user->email}) intentó ingresar a una sede que no le corresponde.", null, null, $user);

                return redirect()->route('login')
                    ->withInput($request->only('email'))
                    ->with('error', "Acceso denegado: Su usuario pertenece a '$sedeCorrecta'. Seleccione la sede correcta para ingresar.");
            }
        }

        // Si la sede coincide o el usuario no tiene restricción (Admin General)
        $request->session()->regenerate();

        Bitacora::registrar('login', 'autenticacion', "El usuario '{$user->name}' inició sesión correctamente.");

        return redirect()->intended('/inicio');
    }

    // 4. Si las credenciales fallan
    Bitacora::registrar('login-fallido', 'autenticacion', "Intento de acceso con credenciales incorrectas para el correo '{$request->input('email')}'.", null, ['email' => $request->input('email')]);

    return redirect()->route('login')
        ->withInput($request->only('email'))
        ->with('error', 'Las credenciales no coinciden con nuestros registros.');
}

    /**
     * Cerrar sesión del usuario activo
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            Bitacora::registrar('logout', 'autenticacion', "El usuario '{$user->name}' cerró sesión.", null, null, $user);

            $this->generarRespaldo($user);
        }

        return redirect('/');
    }

    protected function generarRespaldo($user)
    {
        try {
            $resultado = (new RespaldoBaseDatos)->crear();

            if ($resultado['exito']) {
                Bitacora::registrar(
                    'backup',
                    'sistema',
                    "Se generó un respaldo de la base de datos al cerrar sesión '{$user->name}'.",
                    null,
                    array('archivo' => basename($resultado['archivo'])),
                    $user
                );
            } else {
                Bitacora::registrar(
                    'backup-fallido',
                    'sistema',
                    "Falló el respaldo de la base de datos al cerrar sesión '{$user->name}': " . $resultado['mensaje'],
                    null,
                    null,
                    $user
                );
            }
        } catch (\Exception $e) {
            Log::error('Error inesperado al generar el respaldo: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $user = Auth::user();
        $esPrincipal = $this->esPrincipal($user);

        // Solo la sede principal ve todos los usuarios;
        // las demás sedes solo ven a los usuarios que les pertenecen
        if ($esPrincipal) {
            $usuarios = User::with('sede')->orderBy('name')->get();
            $sedes = Sede::all();
        } else {
            $usuarios = User::with('sede')
                ->where('sede_id', $user->sede_id)
                ->orderBy('name')
                ->get();
            $sedes = Sede::where('id', $user->sede_id)->get();
        }

        return view('usuario', compact('usuarios', 'sedes', 'esPrincipal'));
    }

    /**
     * Registrar un nuevo usuario (cuenta de acceso al sistema)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'rol'      => 'required|integer|in:1,2,3',
            'sede_id'  => 'nullable|exists:sedes,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Limitación de sede: los admins de sedes secundarias solo pueden
        // crear usuarios para su propia sede
        $sedeIdAsignada = $request->input('sede_id');
        if (!$this->esPrincipal(Auth::user())) {
            $sedeIdAsignada = Auth::user()->sede_id;
        }

        $usuario = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'rol'      => $request->input('rol'),
            'sede_id'  => $sedeIdAsignada,
        ]);

        Bitacora::registrar('create', 'usuarios', "Se registró el usuario '{$usuario->name}' con email '{$usuario->email}' y rol {$usuario->rol}.", null, ['name' => $usuario->name, 'email' => $usuario->email, 'rol' => $usuario->rol, 'sede_id' => $usuario->sede_id]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario registrado exitosamente');
    }

    /**
    * editar un usuario
    */

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        // Limitación de sede: solo se pueden editar usuarios de la propia sede
        // (la sede principal puede editar a todos)
        if (!$this->puedeGestionar($usuario)) {
            return redirect()->back()->with('error', 'Solo puedes gestionar usuarios de tu propia sede.');
        }

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'rol'      => 'required|integer|in:1,2,3',
            'sede_id'  => 'nullable|exists:sedes,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Un admin de sede secundaria no puede reasignar usuarios a otra sede
        $sedeIdAsignada = $request->sede_id;
        if (!$this->esPrincipal(Auth::user())) {
            $sedeIdAsignada = Auth::user()->sede_id;
        }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;
        $usuario->sede_id = $sedeIdAsignada;

        if ($request->has('password') && !empty($request->input('password'))) {
        $usuario->password = Hash::make($request->password);
    }

        $datosAntes = ['name' => $usuario->name, 'email' => $usuario->email, 'rol' => $usuario->rol, 'sede_id' => $usuario->sede_id];

        $usuario->save();

        Bitacora::registrar('update', 'usuarios', "Se actualizó el usuario '{$usuario->name}'.", $datosAntes, ['name' => $usuario->name, 'email' => $usuario->email, 'rol' => $usuario->rol, 'sede_id' => $usuario->sede_id]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function toggleEstado($id)
    {
        $usuario = User::findOrFail($id);

        // Evitar que el usuario activo se inhabilite a sí mismo
        if (Auth::check() && Auth::id() == $usuario->id) {
            return redirect()->back()->with('error', 'No puedes inhabilitar tu propio usuario mientras tienes la sesión iniciada.');
        }

        // Limitación de sede
        if (!$this->puedeGestionar($usuario)) {
            return redirect()->back()->with('error', 'Solo puedes gestionar usuarios de tu propia sede.');
        }

        $usuario->estado = ($usuario->estado === 'habilitado') ? 'inhabilitado' : 'habilitado';
        $usuario->save();

        Bitacora::registrar('update', 'usuarios', "Se cambió el estado del usuario '{$usuario->name}' a '{$usuario->estado}'.", null, ['estado' => $usuario->estado]);

        return redirect()->route('usuarios.index')->with('success', 'Estado del usuario actualizado correctamente.');
    }


    /**
     * Eliminar un usuario
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        // Evitar que un usuario se elimine a sí mismo mientras tiene sesión activa
        if (Auth::check() && Auth::id() == $usuario->id) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propio usuario mientras tienes la sesión iniciada.');
        }

        // Limitación de sede
        if (!$this->puedeGestionar($usuario)) {
            return redirect()->back()->with('error', 'Solo puedes gestionar usuarios de tu propia sede.');
        }

        $datosAntes = ['name' => $usuario->name, 'email' => $usuario->email, 'rol' => $usuario->rol, 'sede_id' => $usuario->sede_id, 'estado' => $usuario->estado];

        $usuario->delete();

        Bitacora::registrar('delete', 'usuarios', "Se eliminó el usuario '{$usuario->name}'.", $datosAntes, null);

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
    }

    public function generarCodigoRecuperacion($id)
    {
        $usuario = User::findOrFail($id);

        // Limitación de sede
        if (!$this->puedeGestionar($usuario)) {
            return response()->json(array(
                'exito' => false,
                'mensaje' => 'Solo puedes gestionar usuarios de tu propia sede.',
            ), 403);
        }

        $admin = Auth::user();

        $codigo = CodigoRecuperacion::crearCodigo();

        $registro = CodigoRecuperacion::generar($usuario->id, $admin->name, $codigo);

        Bitacora::registrar(
            'codigo-recuperacion',
            'usuarios',
            "El administrador '{$admin->name}' generó un código de recuperación para '{$usuario->name}'.",
            null,
            array('expira' => $registro ? $registro->expira->format('d/m/Y H:i') : null)
        );

        return response()->json(array(
            'exito' => true,
            'codigo' => $codigo,
            'usuario' => $usuario->name,
            'expira' => $registro ? $registro->expira->format('d/m/Y H:i') : '',
            'minutos' => CodigoRecuperacion::DURACION_MINUTOS,
        ));
    }

    public function anularCodigos($id)
    {
        $usuario = User::findOrFail($id);

        // Limitación de sede
        if (!$this->puedeGestionar($usuario)) {
            return response()->json(array(
                'exito' => false,
                'mensaje' => 'Solo puedes gestionar usuarios de tu propia sede.',
            ), 403);
        }

        $admin = Auth::user();

        $anulados = CodigoRecuperacion::where('user_id', $usuario->id)
            ->where('usado', false)
            ->update(array('usado' => true, 'usado_en' => \Carbon\Carbon::now()));

        Bitacora::registrar(
            'codigo-recuperacion',
            'usuarios',
            "El administrador '{$admin->name}' anuló {$anulados} código(s) de recuperación de '{$usuario->name}'."
        );

        return response()->json(array(
            'exito' => true,
            'anulados' => $anulados,
        ));
    }

    /**
     * Determina si el usuario pertenece a la sede principal
     * (misma regla que el trait AsignaSede).
     */
    protected function esPrincipal($user)
    {
        return $user && ((int) $user->sede_id === 1 || empty($user->sede_id));
    }

    /**
     * Verifica si el usuario autenticado puede gestionar
     * al usuario indicado (mismo sede o sede principal).
     */
    protected function puedeGestionar($usuario)
    {
        $user = Auth::user();

        if ($this->esPrincipal($user)) {
            return true;
        }

        return $usuario->sede_id !== null && (int) $usuario->sede_id === (int) $user->sede_id;
    }
}
