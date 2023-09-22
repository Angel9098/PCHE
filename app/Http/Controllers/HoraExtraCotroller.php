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
            $noCarga = 1;

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
                } else {
                    $registrosDepurados[$registroExistente]['diurnas'] += $registro['diurnas'];
                    $registrosDepurados[$registroExistente]['nocturnas'] += $registro['nocturnas'];
                }
            }
            //dd($registrosDepurados);

            foreach ($registrosDepurados as $registroDepurado) {

                $id_empleado = $registroDepurado['idEmpleado'];
                $fechaRegis = $registro['fecha'];
                $diurnas = $registroDepurado['diurnas'];
                $nocturnas = $registroDepurado['nocturnas'];
                $diurnas_descanso = $registroDepurado['diurnasDescanso'];
                $nocturnas_descanso = $registroDepurado['nocturnasDescanso'];
                $diurnas_asueto = $registroDepurado['diurnasAsueto'];
                $nocturnas_asueto = $registroDepurado['nocturnasAsueto'];


                $hora_extra = new HoraExtra([
                    'empleado_id' => $id_empleado,
                    'fecha_registro' => $fechaRegis,
                    'diurnas' => $diurnas,
                    'nocturnas' => $nocturnas,
                    'diurnas_descanso' => $diurnas_descanso,
                    'nocturnas_descanso' => $nocturnas_descanso,
                    'diurnas_asueto' => $diurnas_asueto,
                    'nocturnas_asueto' => $nocturnas_asueto,
                    'no_carga' => $noCarga,
                    'jefe_area' => $jefeArea,
                ]);

                $hora_extra->save();
            }
            return response()->json(["message" => 'Horas extra agregado con exito'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function allHoras()
    {

        $empleadosConHorasExtra = HoraExtra::all();

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
