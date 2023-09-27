<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use \App\Empleado;
use App\HoraExtra;
use App\UserAct;
use App\Usuario;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function allEmpleados()
    {
        $empleados = Empleado::all();
        return response()->json($empleados);
    }

    public function empleadosBusquedaNombre(Request $request)
    {
        try {
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
            $empleado->horario_id = $request->input('horario_id');
            $empleado->numero_emergencia = $request->input('numero_emergencia');
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
                'horario_id' => 'required|exists:horarios,id',
                'dui' => 'required|string',
                'numero_emergencia' => 'required|string',
                'avisar_contacto' => 'required|string',
                'salario' => 'required|string'
            ]);

            $dui = $request->input('dui');

            $emp = Empleado::where('dui', $dui)->get();

            if (!$emp->isEmpty()) {
                return response()->json(['message' => 'Dui ya registrado', 'DuiDisponible' => '0'], 404);
            } else {
                $empleado = new Empleado();
                $empleado->nombres = $request->input('nombres');
                $empleado->apellidos = $request->input('apellidos');
                $empleado->cargo = $request->input('cargo');
                $empleado->email = $request->input('email');
                $empleado->area_id = $request->input('area_id');
                $empleado->dui = $request->input('dui');
                $empleado->horario_id = $request->input('horario_id');
                $empleado->numero_emergencia = $request->input('numero_emergencia');
                $empleado->avisar_contacto = $request->input('avisar_contacto');
                $empleado->salario = $request->input('salario');
                $empleado->save();

                return response()->json(['message' => 'Empleado creado con éxito', 'empleado' => $empleado, 200]);
            }
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

    public function empleadoById(Request $request)
    {
        try {

            $idEmpleado = $request->input('idEmpleado');
            $empleado = Empleado::where('id', $idEmpleado)->get();

            return response()->json($empleado);
        } catch (\Exception $e) {
            return response()->json(['ocurrio un error al obtener el empleado' => $e], 500);
        }
    }

    public function findEmpleadoById(Request $request)
    {
        $id = $request->input('id');
        $empleado = Empleado::find($id);
        return response()->json($empleado);
    }

    public function actualizarContrasenia(Request $request)
    {
        try {

            $idUsuario =   $request->input('idUsuario');
            $oldPassword =   $request->input('oldPassword');
            $newPassword =   $request->input('newPassword');
            $confirmPassword =   $request->input('confirmPassword');

            // Retrieve the employee
            $empleado = Usuario::find($idUsuario);
            //dd($empleado);
            if (!$empleado) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            //verificar si la contraseña antigua esta correcta
            if (!Hash::check($oldPassword, $empleado->password)) {
                return response()->json(['error' => 'Contraseña antigua es incorrecta'], 400);
            }
            if ($newPassword != $confirmPassword) {
                return response()->json(['error' => 'Las contraseñas no coiciden'], 400);
            }

            $empleado->update([
                'password' => bcrypt($newPassword)
            ]);

            // Password updated successfully
            return response()->json(['message' => 'Contraseña actualizada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['ocurrio un error al obtener el usuario' => $e->getMessage()], 500);
        }
    }


    public function busquedaEmpleadoPorEmpresa(Request $request)
    {
        try {
            $idEmpresa = $request->input('idEmpresa');
            $empleados = DB::table('empleados as e')
                ->join('areas as ar', 'ar.id', '=', 'e.area_id')
                ->select('e.*')
                ->where('ar.empresa_id', '=', $idEmpresa)
                ->get();
            return response()->json($empleados);
        } catch (\Exception $e) {
            return response()->json(['message' => 'ocurrio un error al generar la busqueada'], 500);
        }
    }

    public function allEmpleadosAreaEmpresa()
    {
        $empleados = Empleado::with("area.empresa")->paginate(5);

        return response()->json($empleados);
    }

    public function bEmpleadoF(Request $request)
    {
        try {

            $nombres = $request->input('nombre');
            $apellidos = $request->input('apellido');
            $dui = $request->input('dui');
            $cargo = $request->input('cargo');
            $email = $request->input('email');
            $idEmpresa = $request->input('selectedOption');



            $empleados = Empleado::with('area.empresa')
                ->where(function ($query) use ($nombres) {
                    if (!empty($nombres)) {
                        $query->where('nombres', 'LIKE', "%$nombres%");
                    }
                })
                ->where(function ($query) use ($apellidos) {
                    if (!empty($apellidos)) {
                        $query->where('apellidos', 'LIKE', "%$apellidos%");
                    }
                })
                ->where(function ($query) use ($dui) {
                    if (!empty($dui)) {
                        $query->where('dui', '=', $dui);
                    }
                })
                ->where(function ($query) use ($email) {
                    if (!empty($email)) {
                        $query->where('email', 'LIKE', "%$email%");
                    }
                })
                ->where(function ($query) use ($cargo) {
                    if (!empty($cargo)) {
                        $query->where('cargo', 'LIKE', "%$cargo%");
                    }
                })
                ->whereHas('area.empresa', function ($query) use ($idEmpresa) {
                    if (!empty($idEmpresa)) {
                        $query->where('id', $idEmpresa);
                    }
                })
                ->get();

            return response()->json($empleados);
        } catch (\Exception $e) {
            return response()->json(['message' => 'ocurrio un error al generar la busqueda'], 500);
        }
    }
    public function empleadosConHorasExtra(Request $request)
    {
        $empleadoId = $request->query('empleadoId');
        $areaId = $request->query('areaId');

        $query = HoraExtra::with('empleado.area.empresa');

        if ($empleadoId) {
            $query->whereHas('empleado', function ($query) use ($empleadoId) {
                $query->where('id', $empleadoId);
            });
        }

        if ($areaId) {
            $query->whereHas('empleado.area', function ($query) use ($areaId) {
                $query->where('id', $areaId);
            });
        }

        /*         $empleadosAreaHorasExtra = HoraExtra::with('empleado.area.empresa')
        ->whereHas('empleado', function($query) use ($empleadoId){
            $query->where('id',$empleadoId);
        }) ->get(); */

        $empleadosConHorasExtra = $query->get();

        return response()->json($empleadosConHorasExtra);
    }
}
