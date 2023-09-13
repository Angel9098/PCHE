<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use \App\Empleado;
use App\UserAct;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    public function allEmpleados()
    {
        $empleados = Empleado::all();
        return response()->json($empleados);
    }

    public function empleadosBusquedaNombre(Request $request){
        try{
            $nombre = $request->input('nombres');
            $empleados = Empleado::where('nombres', 'LIKE', "%$nombre%")->get();
            return response()->json($empleados);
        } catch (\Exception $e) {
            return response()->json(['message' => 'ocurrio un error al generar la busqueada'], 500);
        }
    }

    public function actualizarEmpleados(Request $request)
    {
        try {

            $idEmpleado =  $request->input('id');
            $empleado = Empleado::findOrFail($idEmpleado);

            if (!$empleado) {
                return response()->json(['message' => 'Empleado no encontrado'], 404);
            }

            $empleado->nombres = $request->input('nombres');
            $empleado->apellidos = $request->input('apellidos');
            $empleado->cargo = $request->input('cargo');
            $empleado->email = $request->input('email');
            $empleado->area_id = $request->input('area_id');
            $empleado->dui = $request->input('dui');
            $empleado->horario_id=$request->input('horario_id');
            $empleado->numero_emergencia =$request->input('numero_emergencia');
            $empleado->nombre_persona = $request->input('nombre_persona');
            $empleado->save();



            // Retornar una respuesta JSON con los datos actualizados
            return response()->json(['message' => 'Empleado actualizado con éxito', 'empleado' => $empleado, 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el registro', $e], 500);
        }
    }

    public function crearEmpleado(Request $request)
    {
        try {

            $request->validate([
                'nombres' => 'required|string',
                'apellidos' => 'required|string',
                'cargo' => 'required|string',
                'email' => 'required|email|unique:empleados',
                'area_id' =>  'required|exists:areas,id',
                'email' => 'required|string',
                'horario_id'=> 'required|exists:horarios,id',
                'dui'=> 'required|string',
                'numero_emergencia'=>'required|string',
                'nombre_persona'=>'required|string'
            ]);


            $empleado = new Empleado();
            $empleado->nombres = $request->input('nombres');
            $empleado->apellidos = $request->input('apellidos');
            $empleado->cargo = $request->input('cargo');
            $empleado->email = $request->input('email');
            $empleado->area_id = $request->input('area_id');
            $empleado->dui = $request->input('dui');
            $empleado->horario_id=$request->input('horario_id');
            $empleado->numero_emergencia =$request->input('numero_emergencia');
            $empleado->nombre_persona = $request->input('nombre_persona');
            $empleado->save();


            return response()->json(['message' => 'Empleado creado con éxito', 'empleado' => $empleado, 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear registro', $e], 500);
        }
    }


    public function eliminarEmpleados(Request $request)
    {
        try {
            $id = $request->input('id');

            $empleados = Empleado::findOrFail($id);
            $empleados->delete();

            return response()->json(['message' => 'Registro eliminado con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el registro'], 500);
        }
    }

    public function empleadoByDui(Request $request)
    {
        try {

            $duiEmpleado = $request->input('dui');

            $empleado = Empleado::with('area.empresa')->where('dui', 'LIKE', "%$duiEmpleado%")->first();
            return response()->json($empleado);
        } catch (\Exception $e) {
            return response()->json(['ocurrio un error al obtener el empleado' => $e], 500);
        }
    }

    public function actualizarContrasenia(Request $request, UserAct $userAct)
    {
        try {

            $idUsuario = $userAct->idUsuario;
            $oldPassword = $userAct->oldPassword;
            $newPassword = $userAct->newPassword;
            $confirmPassword = $userAct->confirmPassword;


            // Retrieve the employee
            $empleado = Empleado::find($idUsuario);

            if (!$empleado) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            //verificar si la contraseña antigua esta correcta
            if (!Hash::check($oldPassword, $empleado->usuario->password)) {
                return response()->json(['error' => 'Contraseña antigua es incorrecta'], 400);
            }

            if ($newPassword != $confirmPassword) {
                return response()->json(['error' => 'Las contraseñas no coiciden'], 400);
            }

            $empleado->usuario->update([
                'password' => bcrypt($newPassword)
            ]);

            // Password updated successfully
            return response()->json(['message' => 'Password updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['ocurrio un error al obtener el usuario' => $e], 500);
        }
    }
}
