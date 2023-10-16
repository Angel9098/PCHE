<?php

namespace App\Http\Controllers;

use App\Area;
use App\CalculosExtra;
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
    public function planillaQuincenal(Request $request)
    {
        //$empleados = Empleado::where('nombres', 'LIKE', "%$nombre%")->get();

        $empleados = Empleado::all();
        $anio = date('Y');

        $mes = intval(date('m')) - 1;

        $dia = intval(date('d'));

        $datos = [];
        $resultados = [];

        foreach ($empleados as $empleado) {

            if ($dia >= 1 && $dia <= 15) {
                $sueldoGanadoQuincena1 = CalculosExtra::query()
                    ->select('calculos_horas.empleado_id')
                    ->selectRaw("SUM(calculos_horas.salario_neto) as total_salario_neto")
                    ->selectRaw("SUM(calculos_horas.total_horas) as total_horas_extras")
                    ->join('empleados', 'calculos_horas.empleado_id', '=', 'empleados.id')
                    ->where('calculos_horas.empleado_id', $empleado->id)
                    ->whereMonth('calculos_horas.fecha_calculo', $mes)
                    ->whereYear('calculos_horas.fecha_calculo', $anio)
                    ->groupBy('calculos_horas.empleado_id')
                    ->first();

                $sueldoQuincenal = $sueldoGanadoQuincena1 != null ? (($empleado->salario / 2) + $sueldoGanadoQuincena1->total_salario_neto) : ($empleado->salario / 2);
                $isssQuincenal = ($sueldoQuincenal * 0.030) <= 30  ? ($sueldoQuincenal * 0.030) : 30;
                $afpQuincenal = $sueldoQuincenal  * 0.0725;

                $aplicableRenta = $sueldoQuincenal  - ($isssQuincenal + $afpQuincenal);
                $rentaTotal = 0;
                if ($aplicableRenta > 236.01 && $aplicableRenta < 447.62) {
                    if ($aplicableRenta > 236.00) {
                        $exceso = $aplicableRenta - 236.00;
                        $excesoRenta = $exceso * 0.1;
                        $cuotaFija = 8.83;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 447.63 && $aplicableRenta <= 1019.05) {
                    if ($aplicableRenta > 447.62) {
                        $exceso = $aplicableRenta - 447.62;
                        $excesoRenta = $exceso * 0.2;
                        $cuotaFija = 30;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 1019.06) {
                    if ($aplicableRenta > 1019.05) {
                        $exceso = $aplicableRenta - 1020;
                        $excesoRenta = $exceso * 0.3;
                        $cuotaFija = 144.28;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                }
                $resultados["sueldoMesual"] = $sueldoQuincenal;
                $resultados["imponibleRenta"] = $aplicableRenta;
                $resultados["afp"] = $afpQuincenal;
                $resultados["isss"] = $isssQuincenal;
                $resultados["TotalPagar"] = $aplicableRenta - $rentaTotal;
                $resultados["idEmpleado"] = $empleado->id;
                $resultados["horasExtra"] = $sueldoGanadoQuincena1 != null ? $sueldoGanadoQuincena1->total_salario_neto : 0;
                $resultados["totalHorasExtras"] = $sueldoGanadoQuincena1 != null ? $sueldoGanadoQuincena1->total_horas_extras : 0;
                $resultados["dui"] = $empleado->dui;

                array_push($datos, $resultados);
            } elseif ($dia > 15 && $dia <= cal_days_in_month(CAL_GREGORIAN, $mes, $anio)) {
                /* $sueldoGanadoQuincena2 = CalculosExtra::query()
                    ->select('calculos_horas.empleado_id')
                    ->selectRaw("SUM(calculos_horas.salario_neto) as total_salario_neto")
                    ->join('empleados', 'calculos_horas.empleado_id', '=', 'empleados.id')
                    ->where('calculos_horas.empleado_id', $empleado->id)
                    ->whereMonth('calculos_horas.fecha_calculo', $mes)
                    ->whereYear('calculos_horas.fecha_calculo', $anio)
                    ->whereDay('calculos_horas.fecha_calculo', '>', 15)
                    ->groupBy('calculos_horas.empleado_id')
                    ->first();*/

                $sueldoMensual = $empleado->salario;
                $isssMensual = ($sueldoMensual * 0.030);

                $afpMensual = $sueldoMensual  * 0.0725;

                $aplicableRentaMensual = $sueldoMensual - ($isssMensual + $afpMensual);
                $rentaTotalMensual = 0;
                if ($aplicableRentaMensual >= 472.01 && $aplicableRentaMensual <= 895.24) {
                    if ($aplicableRentaMensual > 472.00) {
                        $exceso = $aplicableRentaMensual - 472.00;
                        $excesoRenta = $exceso * 0.1;
                        $cuotaFija = 17.67;
                        $rentaTotalMensual = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRentaMensual >= 895.25 && $aplicableRentaMensual <= 2038.10) {
                    if ($aplicableRentaMensual > 895.24) {
                        $exceso = $aplicableRentaMensual - 896.24;
                        $excesoRenta = $exceso * 0.2;
                        $cuotaFija = 60;
                        $rentaTotalMensual = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRentaMensual >= 2038.11) {
                    if ($aplicableRentaMensual > 2038.10) {
                        $exceso = $aplicableRentaMensual - 2038.10;
                        $excesoRenta = $exceso * 0.3;
                        $cuotaFija = 288.57;
                        $rentaTotalMensual = $excesoRenta + $cuotaFija;
                    }
                }

                /**---------------------calculo quincenal para diferencia---------------- */
                $sueldoQuincenal = $empleado->salario / 2;
                $isssQuincenal = ($sueldoQuincenal * 0.030);
                $afpQuincenal = $sueldoQuincenal  * 0.0725;

                $aplicableRenta = $sueldoQuincenal  - ($isssQuincenal + $afpQuincenal);
                $rentaTotalQuincela = 0;
                if ($aplicableRenta > 236.01 && $aplicableRenta < 447.62) {
                    if ($aplicableRenta > 236.00) {
                        $exceso = $aplicableRenta - 236.00;
                        $excesoRenta = $exceso * 0.1;
                        $cuotaFija = 8.83;
                        $rentaTotalQuincela = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 447.63 && $aplicableRenta <= 1019.05) {
                    if ($aplicableRenta > 447.62) {
                        $exceso = $aplicableRenta - 447.62;
                        $excesoRenta = $exceso * 0.2;
                        $cuotaFija = 30;
                        $rentaTotalQuincela = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 1019.06) {
                    if ($aplicableRenta > 1019.05) {
                        $exceso = $aplicableRenta - 1020;
                        $excesoRenta = $exceso * 0.3;
                        $cuotaFija = 144.28;
                        $rentaTotalQuincela = $excesoRenta + $cuotaFija;
                    }
                }
                $resultados["sueldoMesual"] = $sueldoMensual;
                $resultados["imponibleRenta"] = $aplicableRentaMensual - $aplicableRenta;
                $resultados["afp"] = $afpMensual - $afpQuincenal;
                $resultados["isss"] = ($isssMensual - $isssQuincenal) <= 30 ? ($isssMensual - $isssQuincenal) : 30;
                $resultados["TotalPagar"] = ($aplicableRentaMensual - $rentaTotalMensual) - ($aplicableRenta - $rentaTotalQuincela);
                $resultados["idEmpleado"] = $empleado->id;
                $resultados["horasExtra"] = 0;
                $resultados["totalHorasExtras"] =  0;
                $resultados["dui"] = $empleado->dui;

                array_push($datos, $resultados);
            }
        }

        return CustomResponse::make($datos, '', 200, null);
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
            // $empleados->delete();

            if (!$empleados) {
                return CustomResponse::make(null, 'Usuario no encontrado', 400, null);
            }

            $empleados->eliminar = 0;

            $empleados->save();
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
        try {
            $id = $request->input('id');
            $empleado = Empleado::find($id);

            return CustomResponse::make($empleado, '', 200, null);
        } catch (Exception $ex) {
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
        try {
            $empleados = Empleado::with("area.empresa")->paginate(5);
            return CustomResponse::make($empleados, '', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Ocurrio un error al generar la busqueada', 500, $e->getMessage());
        }
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
