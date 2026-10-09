<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CuentaController extends Controller
{
    public function registrar(Request $request)
    {
        $nombre = trim($request->input('nombre', ''));
        $correo = strtolower(trim($request->input('correo', '')));
        $clave = $request->input('clave', '');

        $errores = [];
        if ($nombre === '') {
            $errores['nombre'] = 'Ingresa tu nombre.';
        }
        if (! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores['correo'] = 'Ingresa un correo válido.';
        }
        if (strlen($clave) < 8) {
            $errores['clave'] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        if ($errores) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $errores], 400);
        }

        if (User::where('email', $correo)->exists()) {
            return response()->json(['message' => 'Ya existe una cuenta con ese correo'], 409);
        }

        $user = User::create([
            'name' => $nombre,
            'email' => $correo,
            'password' => $clave,
        ]);

        Auth::login($user);

        return response()->json(['user' => $this->publico($user)], 201);
    }

    public function entrar(Request $request)
    {
        $correo = strtolower(trim($request->input('correo', '')));
        $clave = $request->input('clave', '');

        if (! Auth::attempt(['email' => $correo, 'password' => $clave])) {
            return response()->json(['message' => 'Correo o contraseña incorrectos'], 401);
        }

        $request->session()->regenerate();

        return response()->json(['user' => $this->publico(Auth::user())]);
    }

    public function salir(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function yo()
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'No has iniciado sesión'], 401);
        }

        return response()->json(['user' => $this->publico($user)]);
    }

    // no hay correo de verdad: devolvemos el token para pegarlo en la pagina
    public function recuperar(Request $request)
    {
        $correo = strtolower(trim($request->input('correo', '')));
        if (! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => ['correo' => 'Ingresa un correo válido.'],
            ], 400);
        }

        $mensaje = 'Si el correo existe, se generó un token de recuperación.';
        $user = User::where('email', $correo)->first();
        if (! $user) {
            return response()->json(['message' => $mensaje]);
        }

        $token = bin2hex(random_bytes(24));
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $correo],
            ['token' => hash('sha256', $token), 'created_at' => now()]
        );

        return response()->json(['message' => $mensaje, 'token' => $token]);
    }

    public function confirmar(Request $request)
    {
        $correo = strtolower(trim($request->input('correo', '')));
        $token = trim($request->input('token', ''));
        $clave = $request->input('clave', '');

        $errores = [];
        if (! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores['correo'] = 'Ingresa un correo válido.';
        }
        if ($token === '') {
            $errores['token'] = 'Ingresa el token.';
        }
        if (strlen($clave) < 8) {
            $errores['clave'] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        if ($errores) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $errores], 400);
        }

        $fila = DB::table('password_reset_tokens')->where('email', $correo)->first();
        $vigente = $fila && $fila->created_at
            && Carbon::parse($fila->created_at)->gte(now()->subMinutes(15));
        $sirve = $vigente && hash_equals($fila->token, hash('sha256', $token));

        if (! $sirve) {
            return response()->json(['message' => 'Token inválido o expirado'], 400);
        }

        $user = User::where('email', $correo)->first();
        $user->password = $clave;
        $user->save();
        DB::table('password_reset_tokens')->where('email', $correo)->delete();

        return response()->json(['message' => 'Contraseña actualizada']);
    }

    private function publico(User $user)
    {
        return [
            'id' => $user->id,
            'correo' => $user->email,
            'nombre' => $user->name,
        ];
    }
}
