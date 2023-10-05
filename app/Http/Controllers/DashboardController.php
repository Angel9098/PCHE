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
        $idEmpresa = $request->input('empresa_id');

        $resultados = DB::table('empresas as em')
            ->join('areas as a', 'em.id', '=', 'a.empresa_id')
            ->leftJoin('empleados as e', 'a.id', '=', 'e.area_id')
            ->leftJoin('horas_extra as he', 'he.empleado_id', '=', 'e.id')
            ->where('em.id', $idEmpresa)
            ->groupBy('a.nombre')
            ->select(
                'a.nombre as nombre_area',
                DB::raw('IFNULL(SUM(he.diurnas + he.nocturnas + he.diurnas_descanso + he.nocturnas_descanso + he.diurnas_asueto + he.nocturnas_asueto),0) as total_horas')
            )
            ->get();

        return response()->json($resultados);
    }
}
