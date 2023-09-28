<?php

namespace App\Http\Controllers;

use App\CalculosExtra;
use App\Corte;
use App\Empleado;
use App\HoraExtra;
use Illuminate\Http\Request;

class CalculosHorasController extends Controller
{


    public function createCalculo(Request $request)
    {

        try {
            $registros = json_decode($request->getContent(), true);
            foreach ($registros as $registro) {
                $id_empleado = $registro['empleado_id'];
                $empleado = Empleado::where('id', $id_empleado)->first();

                $idEliminar = $registro['id'];
                $jefeArea = $registro['jefe_area'];
                $fecha = $registro['fecha_registro'];
                $fechaFormateada = date('Y-m-d', strtotime($fecha));
                $diurnas = $registro['diurnas'];
                $nocturnas = $registro['nocturnas'];
                $diurnas_descanso = $registro['diurnas_descanso'];
                $nocturnas_descanso = $registro['nocturnas_asueto'];
                $diurnas_asueto = $registro['diurnas_asueto'];
                $nocturnas_asueto = $registro['diurnas_descanso'];

                $empleadoRegistrado = CalculosExtra::where('empleado_id', $empleado->id)
                    ->where('fecha_calculo', $fechaFormateada)
                    ->first();

                if ($empleadoRegistrado == null) {
                    $salarioMensual = $empleado->salario;
                    $salarioDiario = $salarioMensual / 30;
                    $salarioGanado = (($diurnas * $salarioDiario) * 2) + (($nocturnas * $salarioDiario) * 2.5) + (($diurnas_descanso * $salarioDiario) * 3) + (($nocturnas_descanso * $salarioDiario) * 3.75) + (($diurnas_asueto * $salarioDiario) * 4) + (($nocturnas_asueto * $salarioDiario) * 5);


                    $sumatoria = $diurnas + $nocturnas + $diurnas_descanso + $nocturnas_descanso + $diurnas_asueto + $nocturnas_asueto;
                    //dd($sumatoria, $salarioGanado, $salarioDiario);

                    $salarioTotal = $salarioGanado + $salarioMensual;


                    $idCorte = Corte::where('vigente', 1)
                        ->first();

                    $CalculoHora = new CalculosExtra([
                        'empleado_id' => $empleado->id,
                        'jefe_area' => $jefeArea,
                        'id_corte' => $idCorte->id,
                        'fecha_calculo' => $fechaFormateada,
                        'salario_mensual' => $salarioMensual,
                        'total_horas' => $sumatoria,
                        'salario_neto' => $salarioGanado,
                        'salario_total' => $salarioTotal,
                    ]);
                    $CalculoHora->save();

                    $horaDelete = HoraExtra::findOrFail($idEliminar);
                    $horaDelete->delete();
                }
            }


            return response()->json(["message" => 'Calculos realizados con exito'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function allCalculos(Request $request)
    {
        $idArea = $request->input('selectArea');
        $idEmpresa = $request->input('selectEmpresa');
        $fechaDesde = $request->input('fechaDesde');
        $fechaHasta = $request->input('fechaHasta');

        $horasExtrasQuery = CalculosExtra::query()->with('empleado.area.empresa');

        if ($idEmpresa !== "NA" && $idEmpresa !== null) {
            $horasExtrasQuery->whereHas('empleado.area', function ($query) use ($idEmpresa) {
                $query->where('empresa_id', $idEmpresa);
            });
        }

        if ($fechaDesde !== null) {
            $horasExtrasQuery->where('fecha_calculo', '>=', $fechaDesde);
        }

        if ($fechaHasta !== null) {
            $horasExtrasQuery->where('fecha_calculo', '<=', $fechaHasta);
        }

        if ($idArea !== "NA" && $idArea !== null) {
            $horasExtrasQuery->whereHas('empleado', function ($query) use ($idArea) {
                $query->where('area_id', $idArea);
            });
        }

        $empleadosConCalculos = $horasExtrasQuery->get();

        return response()->json($empleadosConCalculos);
    }
}
