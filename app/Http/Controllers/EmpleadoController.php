<?php

namespace App\Http\Controllers;

use App\Area;
use App\CustomResponse;
use Illuminate\Http\Request;
use \App\Empleado;
use App\HoraExtra;
use App\UserAct;
use App\Usuario;
use Exception;
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
            return CustomResponse::make($empleados, '', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Ocurrio un error al generar la busqueada', 500, $e->getMessage());
        }
    }

    public function actualizarEmpleados(Request $request)
    {
        try {

            $idEmpleado =  $request->input('id');
            $empleado = Empleado::findOrFail($idEmpleado);

            if (!$empleado) {
                return CustomResponse::make(null, 'Empleado no encontrado', 404, null);
            }

            $empleado->nombres = $request->input('nombres');
            $empleado->apellidos = $request->input('apellidos');
            $empleado->cargo = $request->input('cargo');
            $empleado->email = $request->input('email');
            $empleado->salario = $request->input('salario');
            $empleado->area_id = $request->input('area_id');
            $empleado->dui = $request->input('dui');
            $empleado->horario_id = $request->input('horario_id');
            $empleado->numero_emergencia = $request->input('numero_emergencia');
            $empleado->avisar_contacto = $request->input('avisar_contacto');

            $empleado->save();

            // Retornar una respuesta JSON con los datos actualizados
            return CustomResponse::make($empleado, 'Empleado actualizado con éxito', 201, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Error al actualizar el registro', 500, $e->getMessage());
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

                return CustomResponse::make($empleado, 'Empleado creado con éxito', 201, null);
            }
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Error al crear el registro', 500, $e->getMessage());
        }
    }

    public function eliminarEmpleados(Request $request)
    {
        try {
            $id = $request->input('id');

            $empleados = Empleado::findOrFail($id);
            $empleados->delete();

            return CustomResponse::make($empleados, 'Empleado eliminado con éxito', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Error al eliminar el registro', 500, $e->getMessage());
        }
    }

    //endpoint 1
    public function empleadoByDui(Request $request)
    {
        try {

            $duiEmpleado = $request->input('dui');

            $empleado = Empleado::with('area.empresa')->where('dui', 'LIKE', "%$duiEmpleado%")->first();
            if ($empleado === null) {
                return CustomResponse::make(null, 'Empleado no encontrado', 400, null);
            }
            return CustomResponse::make($empleado, '', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make($empleado, 'Error al obtener empleado', 500, $e->getMessage());
        }
    }

    public function empleadoById(Request $request)
    {
        try {

            $idEmpleado = $request->input('idEmpleado');
            $empleado = Empleado::where('id', $idEmpleado)->with('area.empresa')->get();
            return CustomResponse::make($empleado, '', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'ocurrio un error al obtener el empleado', 500, null);
        }
    }

    public function findEmpleadoById(Request $request)
    {
        try{
            $id = $request->input('id');
            $empleado = Empleado::find($id);

            return CustomResponse::make($empleado, '', 200, null);
        }catch(Exception $ex){
            return CustomResponse::make(null, 'ocurrio un error al obtener el empleado', 500, null);
        }
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
                return CustomResponse::make(null, 'Usuario no encontrado', 400, null);
            }

            //verificar si la contraseña antigua esta correcta
            if (!Hash::check($oldPassword, $empleado->password)) {
                return CustomResponse::make(null, 'Contraseña antigua es incorrecta', 400, null);
            }
            if ($newPassword != $confirmPassword) {
                return CustomResponse::make(null, 'Las contraseñas no coiciden', 400, null);
            }

            $empleado->update([
                'password' => bcrypt($newPassword)
            ]);

            // Password updated successfully
            return CustomResponse::make($empleado, 'Contraseña actualizada exitosamente', 201, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Ocurrio un error al obtener el usuario', 500, $e->getMessage());
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
            return CustomResponse::make($empleados, '', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Ocurrio un error al generar la busqueada', 500, $e->getMessage());
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
                        $query->where('dui', 'LIKE', "%$dui%");
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
                return CustomResponse::make($empleados, '', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Ocurrio un error al generar la busqueda', 500, $e->getMessage());
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
        $empleadosConHorasExtra = $query->get();

        return CustomResponse::make($empleadosConHorasExtra, '', 200, null);
    }

    public function validarEmail(Request $request)
    {
        $email = $request->input('email');

        // Verificar si el correo electrónico ya existe en la base de datos
        $empleado = Empleado::where('email', $email)->first();

        if ($empleado) {
            // El correo electrónico ya existe
            return CustomResponse::make(null, 'El correo electrónico ya está en uso', 400, null);
        }

        // El correo electrónico no existe
        return CustomResponse::make(null, 'El correo electrónico está disponible', 200, null);
    }

    public function validarDui(Request $request)
    {
        $email = $request->input('dui');

        // Verificar si el dui ya existe en la base de datos
        $empleado = Empleado::where('dui', $email)->first();

        if ($empleado) {
            // El dui ya existe
            return CustomResponse::make(null, 'El dui ya está en uso', 400, null);
        }

        // El dui no existe
        return CustomResponse::make(null, 'El dui esta disponible', 200, null);
    }
}
