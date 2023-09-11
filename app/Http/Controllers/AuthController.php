<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    /*public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return redirect()->back()->withErrors(['usuario' => 'Credenciales inválidas']);
    }*/

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user(); // Obtener el usuario autenticado
            return response()->json(['message' => 'Inicio de sesión exitoso','Usuario' => $user], 200);
        }

        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }

    public function showRegistrationForm()
    {
        return view('registro');
    }

    public function register(Request $request)
    {
        // Validar datos de registro
        $this->validate($request, [
            'correo' => 'required|string|max:255',
            'contrasenia' => 'required|string|min:6',
        ]);

        // Crear usuario
        $usuario = Usuario::create([
            'email' => $request->input('correo'),
            'password' => bcrypt($request->input('contrasenia')),
            'empleado_id' => $request->input('idEmpleado'),
        ]);

        // Iniciar sesión automáticamente
        Auth::login($usuario);

        // Redirigir al usuario a la página deseada después del registro
        return response()->json(['message' => 'Usuario creado con exito'], 201);
    }
}
