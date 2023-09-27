<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use App\Empleado;
use Exception;
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
        $idArea = $request->input('id');

        $area = Area::find($idArea);

        return response()->json($area);
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

    public function createArea(Request $request){

        try {
            // Validar los datos antes de crear el área
            $validatedData = $request->validate([
                'nombre' => 'required|string',
                'empresa_id' => 'required|integer',
                'jefe_area' => 'required|string',
            ]);

            $area = new Area();
            $area->nombre = $request->input('nombre');
            $area->empresa_id = $request->input('empresa_id');
            $area->jefe_area = $request->input('jefe_area');
            $area->save();

            return response()->json(['message' => 'Area de empresa creada con éxito', 'area' => $area, 201]);

        } catch (\Exception $e) {
            // Manejar cualquier error y devolver una respuesta de error adecuada
            return response()->json(['error' => 'No se pudo crear el área. Detalles: ' . $e->getMessage()], 500);
        }

    }

    public function updateArea(Request $request){

        try {
            $idArea = $request->input('id');
            $area = Area::find($idArea);

            if (!$area) {
                return response()->json(['message' => 'Area no encontrada'], 404);
            }

            // Validar los datos antes de editar el área
            $validatedData = $request->validate([
                'nombre' => 'required|string',
                'empresa_id' => 'required|integer',
                'jefe_area' => 'required|string',
            ]);

            $area->nombre = $request->input('nombre');
            $area->empresa_id = $request->input('empresa_id');
            $area->jefe_area = $request->input('jefe_area');
            $area->save();

            return response()->json(['message' => 'Area de empresa actualizada con éxito', 'area' => $area, 201]);

        } catch (\Exception $e) {
            // Manejar cualquier error y devolver una respuesta de error adecuada
            return response()->json(['error' => 'No se pudo editar el área. Detalles: ' . $e->getMessage()], 500);
        }

    }

    public function deleteArea(Request $request){

        try {
            $id = $request->input('id');

            $area = Area::findOrFail($id);
            $area->delete();

            return response()->json(['message' => 'Area eliminada con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el area'], 500);
        }

    }

    public function listaDetalleAreas(Request $request){

            $idArea = $request->input('id_area');

            // Realizar la consulta utilizando Eloquent
            $query = DB::table('areas as a')
            ->join('empresas as e', 'a.empresa_id', '=', 'e.id')
            ->join('empleados as em', 'a.jefe_area', '=', 'em.id')
            ->select('a.id', 'a.nombre as nombre_area', 'e.nombre as nombre_empresa',
                    DB::raw('CONCAT(em.nombres, " ", em.apellidos) as nombre_jefe_area'), 'a.jefe_area as id_jefe');

            if (!empty($idArea)) {
                $query->where('a.id', $idArea);
            }

            $resultados = $query->get();

            // Devolver los resultados en formato JSON
            return response()->json($resultados);
    }

    public function listaJefeEmpleado(){

        $usuarioJefe = DB::table('usuarios as u')
        ->join('empleados as em', 'u.empleado_id', '=', 'em.id')
        ->join('areas as a', 'a.jefe_area', '=', 'u.id')
        ->select('u.id', DB::raw('CONCAT(em.nombres, " ", em.apellidos) as nombre_jefe'))
        ->get();

        return response()->json($usuarioJefe);
    }
}
