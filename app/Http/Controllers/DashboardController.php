<?php

namespace App\Http\Controllers;

use App\CustomResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function obtenerHorasExtraPorEmpresa(Request $request)
    {
        $idEmpresa = $request->input('idEmpresa');

        $result = DB::table('areas as a')
            ->join('empresas as em', 'em.id', '=', 'a.empresa_id')
            ->join('empleados as e', 'a.id', '=', 'e.area_id')
            ->join('calculos_horas as ch', 'ch.empleado_id', '=', 'e.id')
            ->select('a.nombre as nombre_area', DB::raw('SUM(ch.salario_neto) as total_horas'))
            ->where('em.id', '=', $idEmpresa)
            ->whereRaw("DATE_FORMAT(ch.created_at, '%Y-%m') = DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m')")
            ->groupBy('a.nombre')
            ->get();
        return CustomResponse::make($result, '', 200, '');
    }

    public function obtenerTotalSalarioHorasExtra()
    {
        $result = DB::table('areas as a')
            ->join('empresas as em', 'em.id', '=', 'a.empresa_id')
            ->join('empleados as e', 'a.id', '=', 'e.area_id')
            ->join('calculos_horas as ch', 'ch.empleado_id', '=', 'e.id')
            ->select(
                'em.nombre as nombre_empresa',
                DB::raw('MONTH(ch.created_at) as periodo'),
                DB::raw('SUM(salario_neto) as total_horas')
            )
            ->whereBetween('ch.created_at', [now()->subMonths(3), now()])
            ->groupBy('em.nombre', 'periodo')
            ->orderBy('em.nombre', 'asc')
            ->orderBy('periodo', 'asc')
            ->get();

        return CustomResponse::make($result, '', 200, '');
    }
}
