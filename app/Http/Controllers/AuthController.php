<?php

namespace App\Http\Controllers;

use App\Usuuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('usuario', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return redirect()->back()->withErrors(['usuario' => 'Credenciales inválidas']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validar datos de registro
        $this->validate($request, [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'usuario' => 'required|string|unique:usuarios',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Crear usuario
        Usuuario::create([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'cargo' => $request->cargo,
            'empresa' => $request->empresa,
            'usuario' => $request->usuario,
            'password' => bcrypt($request->password),
        ]);

        // Iniciar sesión después de registrar al usuario
        Auth::attempt($request->only('usuario', 'password'));

        return redirect()->route('dashboard');
    }
}
