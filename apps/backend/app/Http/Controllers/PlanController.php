<?php

namespace App\Http\Controllers;

use App\Models\Plan;
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

        $planes = Plan::where('user_id', $user->id)->get(['id', 'nombre', 'fecha_limite', 'estado']);

        return response()->json(['planes' => $planes]);
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

        return response()->json(['id' => $plan->id], 201);
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
            return response()->json(['message' => 'No encontré ese plan.'], 404);
        }
        if ($plan->estado !== 'borrador') {
            return response()->json(['message' => 'Ese plan ya no se puede confirmar.'], 400);
        }

        $plan->estado = 'confirmado';
        $plan->save();

        return response()->json(['ok' => true]);
    }
}
