<?php

namespace App\Http\Controllers;

use App\CustomResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CalculosExtra;

class DashboardController extends Controller
{

    public function obtenerHorasExtraPorEmpresa(Request $request)
    {
        $idEmpresa = $request->input('idEmpresa');

        /*         $result = DB::table('areas as a')
            ->join('empresas as em', 'em.id', '=', 'a.empresa_id')
            ->join('empleados as e', 'a.id', '=', 'e.area_id')
            ->join('calculos_horas as ch', 'ch.empleado_id', '=', 'e.id')
            ->select('a.nombre as nombre_area', DB::raw('SUM(ch.salario_neto) as total_horas'))
            ->where('em.id', '=', $idEmpresa)
            ->whereRaw("MONTH(ch.created_at) = (SELECT MONTH(MAX(ch.created_at))) AND YEAR(ch.created_at) = (SELECT YEAR(MAX(ch.created_at)))")
            ->groupBy('a.nombre')
            ->get(); */

        $result = DB::table('areas as a')
            ->selectRaw('SUM(ch.salario_neto) as total_horas, a.nombre as nombre_area')
            ->join('empleados as e', 'a.id', '=', 'e.area_id')
            ->join('empresas as em', 'em.id', '=', 'a.empresa_id')
            ->join('calculos_horas as ch', 'ch.empleado_id', '=', 'e.id')
            ->where('em.id', '=', $idEmpresa)
            ->whereRaw("DATE_FORMAT(ch.fecha_calculo, '%Y-%m') = (SELECT DATE_FORMAT(MAX(ch2.fecha_calculo), '%Y-%m') FROM calculos_horas as ch2 WHERE ch2.empleado_id = e.id)")
            ->groupBy('a.nombre')
            ->get();


        return CustomResponse::make($result, '', 200, '');
    }

    public function obtenerTotalSalarioHorasExtra()
    {
        /*         $trimestre = DB::table('calculos_horas')
        ->select(
            DB::raw("DATE_FORMAT(MAX(created_at),'%Y-%m') as actual"),
            DB::raw("DATE_FORMAT(DATE_SUB(MAX(created_at), INTERVAL 3 MONTH),'%Y-%m') as inicioTrimestre")
        )
        ->first();

        $result = DB::table('areas as a')
            ->join('empresas as em', 'em.id', '=', 'a.empresa_id')
            ->join('empleados as e', 'a.id', '=', 'e.area_id')
            ->join('calculos_horas as ch', 'ch.empleado_id', '=', 'e.id')
            ->select(
                'em.nombre as nombre_empresa',
                DB::raw('MONTH(ch.created_at) as periodo'),
                DB::raw('SUM(salario_neto) as total_horas')
            )
            ->whereBetween(DB::raw("DATE_FORMAT(ch.created_at, '%Y-%m')"), [$trimestre->inicioTrimestre, $trimestre->actual])
            ->groupBy('em.nombre', 'periodo')
            ->orderBy('em.nombre', 'asc')
            ->orderBy('periodo', 'asc')
            ->get(); */


        $mesFinal = date('m') - 1;
        $mesInicial = date('m') - 4;
        $anio = date('Y');

        $resultados = CalculosExtra::select('empresas.nombre as nombre_empresa')
            ->selectRaw('MONTH(calculos_horas.fecha_calculo) as periodo')
            ->selectRaw('SUM(calculos_horas.salario_neto) as total_horas')
            ->join('empleados', 'calculos_horas.empleado_id', '=', 'empleados.id')
            ->join('areas', 'empleados.area_id', '=', 'areas.id')
            ->join('empresas', 'areas.empresa_id', '=', 'empresas.id')
            ->where(function ($query) use ($mesInicial, $mesFinal, $anio) {
                $query->whereRaw('YEAR(calculos_horas.fecha_calculo) = ?', [$anio])
                    ->whereBetween(DB::raw('MONTH(calculos_horas.fecha_calculo)'), [$mesInicial, $mesFinal]);
            })
            ->groupBy('empresas.nombre', 'periodo')
            ->get();


        return CustomResponse::make($resultados, '', 200, '');
    }

    public function obtenerFechaReciente()
    {
        $fechaReciente = DB::table('calculos_horas')
            ->where('created_at', '>=', now()->subMonth())
            ->max('created_at');

        $formattedDate = date('m-Y', strtotime($fechaReciente));

        return response()->json(['fecha_reciente' => $formattedDate]);
    }
}
