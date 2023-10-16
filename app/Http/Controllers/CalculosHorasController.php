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

            foreach ($registros as $registro) {
                $id_empleado = $registro['empleado_id'];
                $empleado = Empleado::where('id', $id_empleado)->first();

                $idEliminar = $registro['id'];
                $jefeArea = $registro['jefe_area'];
                $fechaRegis = $registro['fecha_registro'];
                $fechaFormateada = date('Y-m-d', strtotime(str_replace('/', '-', $fechaRegis)));

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

                    $idCorte = Corte::where('vigente', 1)
                        ->first();

                    $CalculoHora = new CalculosExtra([
                        'empleado_id' => $empleado->id,
                        'jefe_area' => $jefeArea,
                        'id_corte' => $idCorte->id,
                        'fecha_calculo' => $fechaFormateada,
                        'salario_mensual' => $salarioMensual,
                        'descuento_AFP' => null,
                        'descuento_ISSS' => null,
                        'total_horas' => $sumatoria,
                        'salario_neto' => $salarioGanado,
                        'salario_total' => 0,
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
        $mes = $request->input('mes');
        $anio =  $request->input('anio');
        $dui = $request->input('dui');
        $nombre = $request->input('nombre');
        $email = $request->input('email');
        if ($anio === null || $anio === "") {
            $anio = date('Y');
        }
        if ($mes === null || $mes === "") {
            $mes = date('m') - 1;
        }

        $horasExtrasQuery = CalculosExtra::query()
            ->select(
                'calculos_horas.empleado_id',
                DB::raw("CONCAT(empleados.nombres, ' ', empleados.apellidos) as nombres"),
                'empleados.dui as dui',
                'areas.nombre as nombre_area',
                'empresas.nombre as nombre_empresa',
                'calculos_horas.jefe_area',
                'calculos_horas.fecha_calculo',
                'calculos_horas.descuento_AFP',
                'calculos_horas.descuento_ISSS',
                'calculos_horas.salario_neto',
                'calculos_horas.total_horas',
                'calculos_horas.salario_mensual',
                'calculos_horas.salario_total'

            )
            ->addSelect(DB::raw('CASE WHEN calculos_horas.fecha_calculo = MAX_DATE.fecha_calculo THEN subquery.total_salario_neto ELSE NULL END AS total_salario_neto'))
            ->join(
                DB::raw('(SELECT empleado_id, SUM(salario_neto) AS total_salario_neto FROM calculos_horas GROUP BY empleado_id) AS subquery'),
                'calculos_horas.empleado_id',
                '=',
                'subquery.empleado_id'
            )
            ->join(
                DB::raw('(SELECT empleado_id, MAX(fecha_calculo) AS fecha_calculo FROM calculos_horas GROUP BY empleado_id) AS MAX_DATE'),
                'calculos_horas.empleado_id',
                '=',
                'MAX_DATE.empleado_id'
            )
            ->join('empleados', 'calculos_horas.empleado_id', '=', 'empleados.id')
            ->join('areas', 'empleados.area_id', '=', 'areas.id')
            ->join('empresas', 'areas.empresa_id', '=', 'empresas.id')
            ->whereRaw('MONTH(calculos_horas.fecha_calculo) = ?', [$mes])
            ->whereRaw('YEAR(calculos_horas.fecha_calculo) = ?', [$anio])
            ->orderBy('calculos_horas.empleado_id', 'asc')
            ->orderBy('calculos_horas.fecha_calculo', 'asc');



        if ($idEmpresa !== "NA" && $idEmpresa !== null) {
            $horasExtrasQuery->whereHas('empleado.area', function ($query) use ($idEmpresa) {
                $query->where('empresa_id', $idEmpresa);
            });
        }

        if ($dui !== null && $dui !== '') {
            $horasExtrasQuery->whereHas('empleado', function ($query) use ($dui) {
                if (!empty($dui)) {
                    $query->where('dui', 'LIKE', "%$dui%");
                }
            });
        }

        if ($nombre !== null && $nombre !== '') {
            $horasExtrasQuery->whereHas('empleado', function ($query) use ($nombre) {
                if (!empty($nombre)) {
                    $query->where('nombres', 'LIKE', "%$nombre%");
                }
            });
        }

        if ($email !== null && $email !== '') {
            $horasExtrasQuery->whereHas('empleado', function ($query) use ($email) {
                if (!empty($email)) {
                    $query->where('email', 'LIKE', "%$email%");
                }
            });
        }

        if ($idArea !== "NA" && $idArea !== null) {
            $horasExtrasQuery->whereHas('empleado', function ($query) use ($idArea) {
                $query->where('area_id', $idArea);
            });
        }

        $empleadosConCalculos = $horasExtrasQuery->get();

        foreach ($empleadosConCalculos as $horasMensual) {
            if ($horasMensual->total_salario_neto !== null) {
                $sueldoGanadoMensual = $horasMensual->total_salario_neto;
                $sueldoBaseMensual = $horasMensual->salario_mensual;
                $afpMensual = ($sueldoGanadoMensual + $sueldoBaseMensual) * 0.0825;
                $isssMensual = (($sueldoGanadoMensual + $sueldoBaseMensual) * 0.035) > 30 ? 30 : (($sueldoGanadoMensual + $sueldoGanadoMensual) * 0.035);

                $aplicableRenta = $sueldoBaseMensual + $sueldoGanadoMensual - ($afpMensual + $isssMensual);

                $rentaTotal = 0;
                if ($aplicableRenta >= 472.01 && $aplicableRenta <= 895.24) {
                    if ($aplicableRenta > 472.00) {
                        $exceso = $aplicableRenta - 472.00;
                        $excesoRenta = $exceso * 0.1;
                        $cuotaFija = 17.67;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 895.25 && $aplicableRenta <= 2038.10) {
                    if ($aplicableRenta > 895.24) {
                        $exceso = $aplicableRenta - 896.24;
                        $excesoRenta = $exceso * 0.2;
                        $cuotaFija = 60;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 2038.11) {
                    if ($aplicableRenta > 2038.10) {
                        $exceso = $aplicableRenta - 2038.10;
                        $excesoRenta = $exceso * 0.3;
                        $cuotaFija = 288.57;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                }
                $horasMensual->descuento_AFP = $afpMensual;
                $horasMensual->descuento_ISSS = $isssMensual;
                $horasMensual->salario_total = $aplicableRenta - $rentaTotal;
            } else {
                $horasMensual->salario_total = 0;
            }
        }


        return CustomResponse::make($empleadosConCalculos, '', 200, null);
    }

    public function datosExport(Request $request)
    {
        $idArea = $request->input('selectArea');
        $idEmpresa = $request->input('selectEmpresa');
        $mes = 9; //$request->input('mes');
        $anio = 2023; //$request->input('anio');
        $dui = $request->input('dui');
        $nombre = $request->input('nombre');
        $email = $request->input('email');

        $horasExtrasMensual = CalculosExtra::query()
            ->select(
                'calculos_horas.empleado_id',
                DB::raw("CONCAT(empleados.nombres, ' ', empleados.apellidos) as nombres"),
                'empleados.dui as dui',
                'areas.nombre as nombre_area',
                'empresas.nombre as nombre_empresa',
                'calculos_horas.jefe_area',
                'calculos_horas.fecha_calculo',
                'calculos_horas.descuento_AFP',
                'calculos_horas.descuento_ISSS',
                'calculos_horas.salario_neto',
                'calculos_horas.total_horas',
                'calculos_horas.salario_mensual',
                'calculos_horas.salario_total'

            )
            ->addSelect(DB::raw('CASE WHEN calculos_horas.fecha_calculo = MAX_DATE.fecha_calculo THEN subquery.total_salario_neto ELSE NULL END AS total_salario_neto'))
            ->join(
                DB::raw('(SELECT empleado_id, SUM(salario_neto) AS total_salario_neto FROM calculos_horas GROUP BY empleado_id) AS subquery'),
                'calculos_horas.empleado_id',
                '=',
                'subquery.empleado_id'
            )
            ->join(
                DB::raw('(SELECT empleado_id, MAX(fecha_calculo) AS fecha_calculo FROM calculos_horas GROUP BY empleado_id) AS MAX_DATE'),
                'calculos_horas.empleado_id',
                '=',
                'MAX_DATE.empleado_id'
            )
            ->join('empleados', 'calculos_horas.empleado_id', '=', 'empleados.id')
            ->join('areas', 'empleados.area_id', '=', 'areas.id')
            ->join('empresas', 'areas.empresa_id', '=', 'empresas.id')
            ->whereRaw('MONTH(calculos_horas.fecha_calculo) = ?', [$mes])
            ->whereRaw('YEAR(calculos_horas.fecha_calculo) = ?', [$anio])
            ->orderBy('calculos_horas.empleado_id', 'asc')
            ->orderBy('calculos_horas.fecha_calculo', 'asc');



        $horasExtrasPrimerQuincena = CalculosExtra::query()
            ->select(
                'calculos_horas.empleado_id',
                DB::raw("CONCAT(empleados.nombres, ' ', empleados.apellidos) as nombres"),
                'empleados.dui as dui',
                'areas.nombre as nombre_area',
                'empresas.nombre as nombre_empresa',
                'calculos_horas.jefe_area',
                'calculos_horas.fecha_calculo',
                'calculos_horas.descuento_AFP',
                'calculos_horas.descuento_ISSS',
                'calculos_horas.salario_neto',
                'calculos_horas.total_horas',
                'calculos_horas.salario_mensual'
            )
            ->addSelect(DB::raw('CASE WHEN calculos_horas.fecha_calculo = MAX_DATE.fecha_calculo THEN subquery.total_salario_neto ELSE NULL END AS total_salario_neto'))
            ->join(
                DB::raw('(SELECT empleado_id, SUM(salario_neto) AS total_salario_neto FROM calculos_horas GROUP BY empleado_id) AS subquery'),
                'calculos_horas.empleado_id',
                '=',
                'subquery.empleado_id'
            )
            ->join(
                DB::raw('(SELECT empleado_id, MAX(fecha_calculo) AS fecha_calculo FROM calculos_horas GROUP BY empleado_id) AS MAX_DATE'),
                'calculos_horas.empleado_id',
                '=',
                'MAX_DATE.empleado_id'
            )
            ->join('empleados', 'calculos_horas.empleado_id', '=', 'empleados.id')
            ->join('areas', 'empleados.area_id', '=', 'areas.id')
            ->join('empresas', 'areas.empresa_id', '=', 'empresas.id')
            ->whereRaw('MONTH(calculos_horas.fecha_calculo) = ?', [$mes])
            ->whereRaw('YEAR(calculos_horas.fecha_calculo) = ?', [$anio])
            ->whereRaw('DAY(calculos_horas.fecha_calculo) <= 15')
            ->orderBy('calculos_horas.empleado_id', 'asc')
            ->orderBy('calculos_horas.fecha_calculo', 'asc');

        $horasExtrasSegundaQuincena = CalculosExtra::query()
            ->select(
                'calculos_horas.empleado_id',
                DB::raw("CONCAT(empleados.nombres, ' ', empleados.apellidos) as nombres"),
                'empleados.dui as dui',
                'areas.nombre as nombre_area',
                'empresas.nombre as nombre_empresa',
                'calculos_horas.jefe_area',
                'calculos_horas.fecha_calculo',
                'calculos_horas.salario_neto',
                'calculos_horas.total_horas',
                'calculos_horas.salario_mensual'
            )
            ->addSelect(DB::raw('CASE WHEN calculos_horas.fecha_calculo = MAX_DATE.fecha_calculo THEN subquery.total_salario_neto ELSE NULL END AS total_salario_neto'))
            ->join(
                DB::raw('(SELECT empleado_id, SUM(salario_neto) AS total_salario_neto FROM calculos_horas GROUP BY empleado_id) AS subquery'),
                'calculos_horas.empleado_id',
                '=',
                'subquery.empleado_id'
            )
            ->join(
                DB::raw('(SELECT empleado_id, MAX(fecha_calculo) AS fecha_calculo FROM calculos_horas GROUP BY empleado_id) AS MAX_DATE'),
                'calculos_horas.empleado_id',
                '=',
                'MAX_DATE.empleado_id'
            )
            ->join('empleados', 'calculos_horas.empleado_id', '=', 'empleados.id')
            ->join('areas', 'empleados.area_id', '=', 'areas.id')
            ->join('empresas', 'areas.empresa_id', '=', 'empresas.id')
            ->whereRaw('MONTH(calculos_horas.fecha_calculo) = ?', [$mes])
            ->whereRaw('YEAR(calculos_horas.fecha_calculo) = ?', [$anio])
            ->whereRaw('DAY(calculos_horas.fecha_calculo) > 15')
            ->orderBy('calculos_horas.empleado_id', 'asc')
            ->orderBy('calculos_horas.fecha_calculo', 'asc');


        $empleadosConCalculos = [
            'primer_quincena' => $horasExtrasPrimerQuincena->get(),
            'segunda_quincena' => $horasExtrasSegundaQuincena->get(),
            'horasExtrasMensual' => $horasExtrasMensual->get(),
        ];


        foreach ($empleadosConCalculos['primer_quincena'] as $registroQuincena) {
            if ($registroQuincena->total_salario_neto !== null) {
                $sueldoGanadoQuincena1 = $registroQuincena->total_salario_neto;
                $sueldoBaseQuincena1 = $registroQuincena->salario_mensual / 2;
                $afpQuincena1 = ($sueldoGanadoQuincena1 + $sueldoBaseQuincena1) * 0.0825;
                $isssQuincena1 = (($sueldoGanadoQuincena1 + $sueldoBaseQuincena1) * 0.035) > 30 ? 30 : (($sueldoGanadoQuincena1 + $sueldoBaseQuincena1) * 0.035);

                $aplicableRenta = $sueldoBaseQuincena1 + $sueldoGanadoQuincena1 - ($afpQuincena1 + $isssQuincena1);

                $rentaTotal = 0;
                if ($aplicableRenta > 236 && $aplicableRenta < 447) {
                    if ($aplicableRenta > 236) {
                        $exceso = $aplicableRenta - 236;
                        $excesoRenta = $exceso * 0.1;
                        $cuotaFija = 9;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 448 && $aplicableRenta <= 1019) {
                    if ($aplicableRenta > 448) {
                        $exceso = $aplicableRenta - 448;
                        $excesoRenta = $exceso * 0.2;
                        $cuotaFija = 30;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 1020) {
                    if ($aplicableRenta > 1020) {
                        $exceso = $aplicableRenta - 1020;
                        $excesoRenta = $exceso * 0.3;
                        $cuotaFija = 144;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                }
                $registroQuincena->descuento_AFP = $afpQuincena1;
                $registroQuincena->descuento_ISSS = $isssQuincena1;
                $registroQuincena->total_horas = $aplicableRenta - $rentaTotal;
            }
        }

        foreach ($empleadosConCalculos['segunda_quincena'] as $registroQuincena2) {
            if ($registroQuincena->total_salario_neto !== null) {
                $sueldoGanadoQuincena1 = $registroQuincena->total_salario_neto;
                $sueldoBaseQuincena1 = $registroQuincena->salario_mensual / 2;
                $afpQuincena1 = ($sueldoGanadoQuincena1 + $sueldoBaseQuincena1) * 0.0825;
                $isssQuincena1 = (($sueldoGanadoQuincena1 + $sueldoBaseQuincena1) * 0.035) > 30 ? 30 : (($sueldoGanadoQuincena1 + $sueldoBaseQuincena1) * 0.035);

                $aplicableRenta = $sueldoBaseQuincena1 + $sueldoGanadoQuincena1 - ($afpQuincena1 + $isssQuincena1);

                $rentaTotal = 0;
                if ($aplicableRenta > 236 && $aplicableRenta < 447) {
                    if ($aplicableRenta > 236) {
                        $exceso = $aplicableRenta - 236;
                        $excesoRenta = $exceso * 0.1;
                        $cuotaFija = 9;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 448 && $aplicableRenta <= 1019) {
                    if ($aplicableRenta > 448) {
                        $exceso = $aplicableRenta - 448;
                        $excesoRenta = $exceso * 0.2;
                        $cuotaFija = 30;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 1020) {
                    if ($aplicableRenta > 1020) {
                        $exceso = $aplicableRenta - 1020;
                        $excesoRenta = $exceso * 0.3;
                        $cuotaFija = 144;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                }
                $registroQuincena->descuento_AFP = $afpQuincena1;
                $registroQuincena->descuento_ISSS = $isssQuincena1;
                $registroQuincena->total_horas = $aplicableRenta - $rentaTotal;
            }
        }

        foreach ($empleadosConCalculos['horasExtrasMensual'] as $horasMensual) {
            if ($horasMensual->total_salario_neto !== null) {
                $sueldoGanadoMensual = $horasMensual->total_salario_neto;
                $sueldoBaseMensual = $horasMensual->salario_mensual;
                $afpMensual = ($sueldoGanadoMensual + $sueldoBaseMensual) * 0.0825;
                $isssMensual = (($sueldoGanadoMensual + $sueldoBaseMensual) * 0.035) > 30 ? 30 : (($sueldoGanadoMensual + $sueldoGanadoMensual) * 0.035);

                $aplicableRenta = $sueldoBaseMensual + $sueldoGanadoMensual - ($afpMensual + $isssMensual);

                $rentaTotal = 0;
                if ($aplicableRenta >= 472.01 && $aplicableRenta <= 895.24) {
                    if ($aplicableRenta > 472.00) {
                        $exceso = $aplicableRenta - 472.00;
                        $excesoRenta = $exceso * 0.1;
                        $cuotaFija = 17.67;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 895.25 && $aplicableRenta <= 2038.10) {
                    if ($aplicableRenta > 895.24) {
                        $exceso = $aplicableRenta - 896.24;
                        $excesoRenta = $exceso * 0.2;
                        $cuotaFija = 60;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                } else if ($aplicableRenta >= 2038.11) {
                    if ($aplicableRenta > 2038.10) {
                        $exceso = $aplicableRenta - 2038.10;
                        $excesoRenta = $exceso * 0.3;
                        $cuotaFija = 288.57;
                        $rentaTotal = $excesoRenta + $cuotaFija;
                    }
                }
                $horasMensual->descuento_AFP = $afpMensual;
                $horasMensual->descuento_ISSS = $isssMensual;
                $horasMensual->salario_total = $aplicableRenta - $rentaTotal;
            }
        }

        return CustomResponse::make($empleadosConCalculos, '', 200, null);
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
