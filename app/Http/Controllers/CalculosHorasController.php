<?php

namespace App\Http\Controllers;

use App\CalculosExtra;
use App\Corte;
use App\CustomResponse;
use App\Empleado;
use App\HoraExtra;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CalculosHorasController extends Controller
{


    public function createCalculo(Request $request)
    {

        try {
            $registros = json_decode($request->getContent(), true);
            $isss = 7.5;
            $afp = 8.5;

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
                $nocturnas_descanso = $registro['nocturnas_descanso'];
                $diurnas_asueto = $registro['diurnas_asueto'];
                $nocturnas_asueto = $registro['nocturnas_asueto'];

                $empleadoRegistrado = CalculosExtra::where('empleado_id', $empleado->id)
                    ->where('fecha_calculo', $fechaFormateada)
                    ->first();

                if ($empleadoRegistrado == null) {
                    $salarioMensual = $empleado->salario;
                    $salarioDiario = $salarioMensual / 30;
                    $salarioPorHora = $salarioDiario / 8;

                    $salarioGanado = (($diurnas * $salarioPorHora) * 2) + (($nocturnas * $salarioPorHora) * 2.5) + (($diurnas_descanso * $salarioPorHora) * 3) + (($nocturnas_descanso * $salarioPorHora) * 3.75) + (($diurnas_asueto * $salarioPorHora) * 4) + (($nocturnas_asueto * $salarioPorHora) * 5);

                    $sumatoria = $diurnas + $nocturnas + $diurnas_descanso + $nocturnas_descanso + $diurnas_asueto + $nocturnas_asueto;

                    $salarioTotal = $salarioGanado + $salarioMensual;

                    $descuentoAfp = $salarioTotal * ($afp / 100);
                    $descuentoIsss = $salarioTotal * ($isss / 100);
                    $idCorte = Corte::where('vigente', 1)
                        ->first();

                    $salarioTotal = $salarioTotal - ($descuentoAfp + $descuentoIsss);

                    $CalculoHora = new CalculosExtra([
                        'empleado_id' => $empleado->id,
                        'jefe_area' => $jefeArea,
                        'id_corte' => $idCorte->id,
                        'fecha_calculo' => $fechaFormateada,
                        'salario_mensual' => $salarioMensual,
                        'descuento_AFP' => $descuentoAfp,
                        'descuento_ISSS' => $descuentoIsss,
                        'total_horas' => $sumatoria,
                        'salario_neto' => $salarioGanado,
                        'salario_total' => $salarioTotal,
                    ]);
                    $CalculoHora->save();

                    $horaDelete = HoraExtra::findOrFail($idEliminar);
                    $horaDelete->procesado = 1;
                    $horaDelete->save();
                }
            }

            return CustomResponse::make($registros, 'Cálculos realizados con éxito', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Error en los cálculos', 500, $e->getMessage());
        }
    }

    public function allCalculos(Request $request)
    {
        $idArea = $request->input('selectArea');
        $idEmpresa = $request->input('selectEmpresa');
        $fechaDesde = $request->input('fechaDesde');
        $fechaHasta = $request->input('fechaHasta');
        $dui = $request->input('dui');
        $nombre = $request->input('nombre');
        $email = $request->input('email');


        $horasExtrasQuery = CalculosExtra::query()->with('empleado.area.empresa');


        if ($idEmpresa !== "NA" && $idEmpresa !== null) {
            $horasExtrasQuery->whereHas('empleado.area', function ($query) use ($idEmpresa) {
                $query->where('empresa_id', $idEmpresa);
            });
        }

        if ($fechaDesde !== null) {
            $horasExtrasQuery->where('fecha_calculo', '>=', $fechaDesde);
        }
        if ($dui !== null) {
            $horasExtrasQuery->whereHas('empleado', function ($query) use ($idArea) {
                if (!empty($dui)) {
                    $query->where('dui', 'LIKE', "%$dui%");
                }
            });
        }
        if ($nombre !== null) {
            $horasExtrasQuery->whereHas('empleado', function ($query) use ($idArea) {
                if (!empty($nombre)) {
                    $query->where('nombres', 'LIKE', "%$nombre%");
                }
            });
        }
        if ($email !== null) {
            $horasExtrasQuery->whereHas('empleado', function ($query) use ($idArea) {
                if (!empty($email)) {
                    $query->where('email', 'LIKE', "%$email%");
                }
            });
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

    public function graficaCalculoDeHorasPorMesEmpresa(Request $request)
    {
        try {

            $resultado =  DB::table('calculos_horas')
                ->select('empresas.id', 'empresas.nombre', DB::raw('SUM(calculos_horas.salario_neto) as total_salario'))
                ->join('areas', 'calculos_horas.jefe_area', '=', 'areas.id')
                ->join('empresas', 'areas.empresa_id', '=', 'empresas.id')
                ->where('calculos_horas.created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 MONTH)'))
                ->groupBy('empresas.id', 'empresas.nombre')
                ->get();
            // $horario = Horario::all();

            return response()->json($resultado);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al traer registro', $e], 500);
        }
    }

    public function graficaCalculoDeHorasDeTresMeses(Request $request)
    {

        try {

            $resultado = DB::table('calculos_horas as c')
                ->selectRaw('MONTH(c.created_at) AS Mes, SUM(c.salario_neto) AS horas_pagadas')
                ->where('c.created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 3 MONTH)'))
                ->groupBy(DB::raw('MONTH(c.created_at)'))
                ->orderBy('Mes')
                ->get();

            return response()->json($resultado);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al traer registro de 3 meses antes', $e], 500);
        }
    }
}
