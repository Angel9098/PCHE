<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use App\Empleado;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function showAreas()
    {
        return view('areas');
    }

    public function index(Request $request)
    {
        // Obtener el valor del parámetro de consulta 'idEmpresa'
        $idEmpresa = $request->input('idEmpresa');

        // Realizar una consulta para obtener todas las áreas de la empresa especificada
        $areas = Area::where('empresa_id', $idEmpresa)->get();

        // Verificar si se encontraron áreas para la empresa
        if ($areas->isEmpty()) {
            return response()->json(['message' => 'No se encontraron áreas para la empresa especificada'], 404);
        }

        return response()->json(['message' => 'Áreas de la empresa recuperadas con éxito', 'areas' => $areas], 200);
    }

    public function areaById(Request $request)
    {
        $idArea = $request->input('idArea');

        $areas = Area::where('id', $idArea)->get();

        return response()->json($areas);
    }

    public function horariosArea(Request $request)
    {
        $idArea = $request->input('idArea');

        $empleados = Empleado::where('area_id', $idArea)->get();

        $horarios = $empleados->pluck('idHorario')->unique();

        return response()->json(['message' => 'Horarios disponibles para el área recuperados con éxito', 'horarios' => $horarios], 200);
    }

    public function empresaArea($id)
    {

        $areas = DB::table('empresas')
            ->join('areas', 'empresas.id', '=', 'areas.empresa_id')
            ->where('empresas.id', $id)
            ->select('empresas.nombre as empresa', 'areas.nombre as area')
            ->get();

        return response()->json($areas);
    }
}
