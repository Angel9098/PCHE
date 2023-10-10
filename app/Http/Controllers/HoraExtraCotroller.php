<?php

namespace App\Http\Controllers;

use App\Empleado;
use App\HoraExtra;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;

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
                $fechaFormateada = date('Y-m-d', strtotime($fechaRegis));
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
                return response()->json(["message" => 'Registros ya se encuentran guardados'], 200);
            }
            return response()->json(["message" => 'Horas extra agregado con exito'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function allHoras(Request $request)
    {
        $idArea = $request->input('selectArea');
        $idEmpresa = $request->input('selectEmpresa');
        $fechaDesde = $request->input('fechaDesde');
        $fechaHasta = $request->input('fechaHasta');

        $horasExtrasQuery = HoraExtra::query()->with('empleado');

        $horasExtrasQuery = HoraExtra::query()
            ->with('empleado')
            ->where('jefe_area', '!=', 0);

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

        return response()->json($empleadosConHorasExtra);
    }

    public function limpiarTabla()
    {
        try {
            HoraExtra::truncate();
            return response()->json(['message' => 'Tabla HoraExtra limpiada con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
