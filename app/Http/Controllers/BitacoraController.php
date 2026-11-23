<?php

namespace App\Http\Controllers;

use App\Bitacora;
use App\User;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $query = Bitacora::with('usuario')->orderBy('created_at', 'desc');

        if ($this->tieneValor($request, 'user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($this->tieneValor($request, 'modulo')) {
            $query->where('modulo', $request->input('modulo'));
        }

        if ($this->tieneValor($request, 'accion')) {
            $query->where('accion', $request->input('accion'));
        }

        if ($this->tieneValor($request, 'fecha_desde')) {
            $query->where('created_at', '>=', $request->input('fecha_desde') . ' 00:00:00');
        }

        if ($this->tieneValor($request, 'fecha_hasta')) {
            $query->where('created_at', '<=', $request->input('fecha_hasta') . ' 23:59:59');
        }

        $bitacoras = $query->paginate(50)->appends($request->query());

        $usuarios = User::orderBy('name')->get();
        $modulos = Bitacora::select('modulo')->distinct()->orderBy('modulo')->pluck('modulo');
        $acciones = Bitacora::select('accion')->distinct()->orderBy('accion')->pluck('accion');

        return view('bitacora', compact('bitacoras', 'usuarios', 'modulos', 'acciones'));
    }

    private function tieneValor(Request $request, $campo)
    {
        $valor = $request->input($campo);

        return $valor !== null && $valor !== '';
    }
}
