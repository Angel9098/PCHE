<?php

namespace App\Http\Controllers;

use App\Empleado;
use App\HoraExtra;
use App\CustomResponse;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HoraExtraCotroller extends Controller
{
    public function createHoraExtra(Request $request)
    {

        try {
            $registros = json_decode($request->getContent(), true);
            $registrosDepurados = [];
            $jefeArea = 1;
            $contador = 0;

            foreach ($registros as $registro) {
                $id_empleado = $registro['idEmpleado'];
                $fecha = $registro['fecha'];

                $registroExistente = null;
                foreach ($registrosDepurados as $index => $registroDepurado) {
                    if ($registroDepurado['idEmpleado'] === $id_empleado && $registroDepurado['fecha'] === $fecha) {
                        $registroExistente = $index;
                        break;
                    }
                }

                if ($registroExistente === null) {
                    $registrosDepurados[] = $registro;
                }
            }

            foreach ($registrosDepurados as $registroDepurado) {

                $id_empleado = $registroDepurado['idEmpleado'];

                $empleado = Empleado::where('dui', $id_empleado)->first();
                $fechaRegis = $registroDepurado['fecha'];
                $fechaFormateada = date('Y-m-d', strtotime(str_replace('/', '-', $fechaRegis)));

                $diurnas = $registroDepurado['diurnas'];
                $nocturnas = $registroDepurado['nocturnas'];
                $diurnas_descanso = $registroDepurado['diurnasDescanso'];
                $nocturnas_descanso = $registroDepurado['nocturnasDescanso'];
                $diurnas_asueto = $registroDepurado['diurnasAsueto'];
                $nocturnas_asueto = $registroDepurado['nocturnasAsueto'];
                $total = 0;

                $total = $diurnas + $nocturnas + $diurnas_descanso + $nocturnas_descanso + $diurnas_asueto + $nocturnas_asueto;

                $empleadoRegistrado = HoraExtra::where('empleado_id', $empleado->id)
                    ->where('fecha_registro', $fechaFormateada)
                    ->first();


                if ($diurnas != 0 || $nocturnas != 0 || $diurnas_descanso != 0 || $nocturnas_descanso != 0 || $diurnas_asueto != 0 || $nocturnas_asueto != 0) {
                    if ($empleadoRegistrado === null) {
                        $hora_extra = new HoraExtra([
                            'empleado_id' => $empleado->id,
                            'fecha_registro' => $fechaFormateada,
                            'diurnas' => $diurnas,
                            'nocturnas' => $nocturnas,
                            'diurnas_descanso' => $diurnas_descanso,
                            'nocturnas_descanso' => $nocturnas_descanso,
                            'diurnas_asueto' => $diurnas_asueto,
                            'nocturnas_asueto' => $nocturnas_asueto,
                            'total' => $total,
                            'procesado' => 0,
                            'jefe_area' => $jefeArea,
                        ]);

                        $hora_extra->save();
                        $contador++;
                    }
                }
            }
            if ($contador === 0) {
                return CustomResponse::make($hora_extra, 'Registros ya se encuentran guardados', 200, null);
            }
            return CustomResponse::make($hora_extra, 'Horas extra agregado con éxito', 201, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'error en la creacion de horas extra', 500, null);
        }
    }

    public function allHoras(Request $request)
    {
        $idArea = $request->input('selectArea');
        $idEmpresa = $request->input('selectEmpresa');
        $fechaDesde = $request->input('fechaDesde');
        $fechaHasta = $request->input('fechaHasta');
        $estado = $request->input('estado');

        $horasExtrasQuery = HoraExtra::query()->with('empleado');

        $horasExtrasQuery = HoraExtra::query()
            ->with('empleado')
            ->where('procesado', '!=', 1);

        if ($estado !== null && $estado == 1) {
            $horasExtrasQuery = HoraExtra::query()
                ->with('empleado')
                ->where('procesado', '=', 1);
        }
        if ($estado !== null && $estado == 0) {
            $horasExtrasQuery = HoraExtra::query()
                ->with('empleado')
                ->where('procesado', '=', 0);
        }

        if ($idEmpresa !== "NA" && $idEmpresa !== null) {
            $horasExtrasQuery->whereHas('empleado.area', function ($query) use ($idEmpresa) {
                $query->where('empresa_id', $idEmpresa);
            });
        }

        if ($fechaDesde !== null) {
            $horasExtrasQuery->where('fecha_registro', '>=', $fechaDesde);
        }

        if ($fechaHasta !== null) {
            $horasExtrasQuery->where('fecha_registro', '<=', $fechaHasta);
        }

        if ($idArea !== "NA" && $idArea !== null) {
            $horasExtrasQuery->whereHas('empleado', function ($query) use ($idArea) {
                $query->where('area_id', $idArea);
            });
        }

        $empleadosConHorasExtra = $horasExtrasQuery->get();
        return CustomResponse::make($empleadosConHorasExtra, '', 200, null);
    }

    public function horasProcesadaNo(Request $request)
    {

        $resultado = [];
        $horasProcesadasQuery = HoraExtra::query()
            ->where('procesado', 1)
            ->count();

        $horasNoProcesadasQuery = HoraExtra::query()
            ->where('procesado', 0)
            ->count();


        $resultado = [
            'noProcesadas' => $horasNoProcesadasQuery,
            'procesadas' => $horasProcesadasQuery

        ];


        return CustomResponse::make($resultado, '', 200, null);
    }
    public function limpiarTabla()
    {
        try {
            HoraExtra::truncate();
            return CustomResponse::make(null, 'Tabla HoraExtra limpiada con éxito', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Error en la ejecucion', 500, $e->getMessage());
        }
    }
}
