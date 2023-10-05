<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function obtenerHorasExtraPorEmpresa(Request $request)
    {
        $idEmpresa = $request->input('idEmpresa'); // Asegúrate de que el valor de idEmpresa esté disponible en la solicitud.

        $result = DB::table('areas as a')
            ->join('empresas as em', 'em.id', '=', 'a.empresa_id')
            ->join('empleados as e', 'a.id', '=', 'e.area_id')
            ->join('calculos_horas as ch', 'ch.empleado_id', '=', 'e.id')
            ->select('a.nombre as nombre_area', DB::raw('SUM(ch.salario_neto) as total_horas'))
            ->where('em.id', '=', $idEmpresa)
            ->whereRaw("DATE_FORMAT(ch.created_at, '%Y-%m') = DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m')")
            ->groupBy('a.nombre')
            ->get();

        return response()->json($result);

    }
}
