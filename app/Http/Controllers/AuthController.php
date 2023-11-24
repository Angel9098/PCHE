<?php

namespace App\Http\Controllers;

use App\CustomResponse;
use App\Usuario;
use Exception;
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

        $mail = $request->input('email');

        $mailbase = Usuario::where('email', $mail)->first();

        if ($mailbase == null) {
            return CustomResponse::make(null, 'Correo electronico invalido', 401, null);
        }
        if (Auth::guard('web')->attempt($credentials)) {

            $user = Auth::user(); // Obtener el usuario autenticado
            return CustomResponse::make($user, 'Inicio de sesión exitoso', 200, null);
        }
        return CustomResponse::make(null, 'Contraseña invalida', 401, null);
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
        try {
            // Validar datos de registro

            // Crear usuario

            $mail = $request->input('correo');
            $contra = $request->input('contrasenia');
            if (strlen($contra) < 6) {
                return CustomResponse::make(null, 'Contraseña debe tener un mínimo de 6 caracteres', 500, null);
            }


            $mailExis = Usuario::where('email', '=', $mail);
            if ($mailExis !=  null) {
                return CustomResponse::make(null, 'Usuario ya se encuentra registrado', 500, null);
            }
            $usuario = Usuario::create([
                'email' => $mail,
                'password' => bcrypt($contra),
                'empleado_id' => $request->input('idEmpleado'),
                'rol' => $request->input('rol'),
            ]);

            Auth::login($usuario);

            return CustomResponse::make($usuario, 'Usuario creado con exito', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Error al crear usuario', 500, $e->getMessage());
        }
    }

    public function editarPerfilUser(Request $request)
    {
        try {
            $imagen = $request->file('imagen');
            $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();
            $rutaImagen = $imagen->storeAs('public/imagenes', $nombreImagen);
            $urlImagen = asset('storage/' . $rutaImagen);
            $idEmpleado =  $request->input('id');
            $usuario = Usuario::findOrFail($idEmpleado);
            if (!$usuario) {
                return CustomResponse::make($usuario, 'Usuario no encontrado', 404, null);
            }
            $usuario->imagen = $nombreImagen;
            $usuario->save();

            return CustomResponse::make($usuario, 'Perfil usuario modificado con exito', 200, null);
        } catch (Exception $e) {
            return CustomResponse::make(null, 'Error en el servidor', 500, $e->getMessage());
        }
    }


    public function recuperarPass(Request $request)
    {
        try {

            $request->input('correo');

            return CustomResponse::make(null, 'Usuario creado con exito', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Error al crear usuario', 500, $e->getMessage());
        }
    }
}
