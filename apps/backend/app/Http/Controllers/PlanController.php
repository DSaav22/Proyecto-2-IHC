<?php

namespace App\Http\Controllers;

use App\Models\Cambio;
use App\Models\Plan;
use App\ReglasPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function lista()
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'No has iniciado sesión'], 401);
        }

        $planes = Plan::orderBy('id')->get(['id', 'user_id', 'nombre', 'fecha_limite', 'estado']);

        return response()->json(['planes' => $planes]);
    }

    public function cambios()
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'No has iniciado sesión'], 401);
        }

        $cambios = Cambio::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get(['id', 'plan_id', 'nombre', 'accion', 'resultado', 'created_at']);

        return response()->json(['cambios' => $cambios]);
    }

    public function crear(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'No has iniciado sesión'], 401);
        }

        $nombre = trim($request->input('nombre', ''));
        $fecha = trim($request->input('fecha', ''));
        if ($nombre === '' || $fecha === '') {
            return response()->json(['message' => 'Falta el nombre o la fecha.'], 400);
        }

        $plan = Plan::create([
            'user_id' => $user->id,
            'nombre' => $nombre,
            'fecha_limite' => $fecha,
            'estado' => 'borrador',
        ]);

        Cambio::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'nombre' => $plan->nombre,
            'accion' => 'creado',
            'resultado' => 'borrador',
        ]);

        return response()->json(['id' => $plan->id], 201);
    }

    public function actualizar(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'No has iniciado sesión'], 401);
        }

        $plan = Plan::where('id', $request->input('id'))
            ->where('user_id', $user->id)
            ->first();

        if (! $plan) {
            return response()->json(['message' => 'No encontré ese plan. Recarga la página.'], 404);
        }
        if (! ReglasPlan::puedeEditar($plan->user_id, $plan->estado, $user->id)) {
            return response()->json(['message' => 'Ese plan ya no se puede editar. Deja de editar y mira la lista.'], 400);
        }

        $nombre = trim($request->input('nombre', ''));
        $fecha = trim($request->input('fecha', ''));
        if ($nombre === '' || $fecha === '') {
            return response()->json(['message' => 'Falta el nombre o la fecha.'], 400);
        }

        $plan->nombre = $nombre;
        $plan->fecha_limite = $fecha;
        $plan->save();

        Cambio::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'nombre' => $plan->nombre,
            'accion' => 'editado',
            'resultado' => $nombre.' · '.$fecha,
        ]);

        return response()->json(['ok' => true]);
    }

    public function confirmar(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'No has iniciado sesión'], 401);
        }

        $plan = Plan::where('id', $request->input('id'))
            ->where('user_id', $user->id)
            ->first();

        if (! $plan) {
            return response()->json(['message' => 'No encontré ese plan. Recarga la página.'], 404);
        }
        if (! ReglasPlan::puedeConfirmar($plan->user_id, $plan->estado, $user->id)) {
            if ($plan->estado === 'cancelado') {
                return response()->json(['message' => 'Un plan cancelado no se puede confirmar. Recarga la página.'], 400);
            }

            return response()->json(['message' => 'Ese plan ya no se puede confirmar. Recarga la página.'], 400);
        }

        $plan->estado = 'confirmado';
        $plan->save();

        Cambio::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'nombre' => $plan->nombre,
            'accion' => 'confirmado',
            'resultado' => 'confirmado',
        ]);

        return response()->json(['ok' => true]);
    }

    public function cancelar(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'No has iniciado sesión'], 401);
        }

        $plan = Plan::where('id', $request->input('id'))->first();

        if (! $plan) {
            return response()->json(['message' => 'No encontré ese plan. Recarga la página.'], 404);
        }

        if ((int) $plan->user_id !== (int) $user->id) {
            return response()->json(['message' => 'Solo quien organiza el plan puede cancelarlo.'], 400);
        }

        if (! ReglasPlan::puedeCancelar($plan->user_id, $plan->estado, $user->id)) {
            if ($plan->estado === 'cancelado') {
                return response()->json(['message' => 'Ese plan ya está cancelado. Recarga la página para verlo.'], 400);
            }

            return response()->json(['message' => 'No puedes cancelar ese plan. Revisa el estado y vuelve a intentarlo.'], 400);
        }

        $plan->estado = 'cancelado';
        $plan->save();

        Cambio::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'nombre' => $plan->nombre,
            'accion' => 'cancelado',
            'resultado' => 'cancelado',
        ]);

        return response()->json(['ok' => true]);
    }
}
