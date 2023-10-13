<?php

namespace App\Http\Controllers;

use App\Area;
use App\CustomResponse;
use Illuminate\Http\Request;
use App\Empleado;
use Exception;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        /*endpoint para devolver las areas recibiendo el id de una empresa y devolviendo las areas que corresponden a esa empresa */
        $idEmpresa = $request->input('idEmpresa');
        $areas = Area::where('empresa_id', $idEmpresa)->get();

        if ($areas->isEmpty()) {
            return CustomResponse::make(null, 'No se encontraron áreas para la empresa especificada', 400, null);
        }
        return CustomResponse::make($areas, 'Áreas de la empresa recuperadas con éxito', 200, null);
    }

    public function areaById(Request $request)
    {
        $idArea = $request->input('id');

        $area = Area::find($idArea);

        return CustomResponse::make($area, 'Area de empresa creada con éxito', 200, null);
    }

    /*public function horariosArea(Request $request)
    {
        try {
            $idArea = $request->input('idArea');

            $empleados = Empleado::where('area_id', $idArea)->get();
            $horarios = $empleados->pluck('idHorario')->unique();

            return CustomResponse::make($horarios, 'Horarios disponibles para el área recuperados con éxito', 200, null);
        } catch (Exception $e) {
            return CustomResponse::make(null, 'Error al obtener horarios', 500, $e->getMessage());
        }
    }*/

    /*public function empresaArea($id)
    {

        $areas = DB::table('empresas')
            ->join('areas', 'empresas.id', '=', 'areas.empresa_id')
            ->where('empresas.id', $id)
            ->select('empresas.nombre as empresa', 'areas.nombre as area')
            ->get();

        return response()->json($areas);
    }*/

    public function createArea(Request $request)
    {

        try {
            // Validar los datos antes de crear el área
            $validatedData = $request->validate([
                'nombre' => 'required|string',
                'empresa_id' => 'required|integer',
                'jefe_area' => 'integer',
            ]);

            $area = new Area();
            $area->nombre = $request->input('nombre');
            $area->empresa_id = $request->input('empresa_id');
            $area->jefe_area = $request->input('jefe_area');
            $area->save();

            return CustomResponse::make($area, 'Area de empresa creada con éxito', 200, null);
        } catch (\Exception $e) {

            return CustomResponse::make($area, 'No se pudo crear el área', 500, $e->getMessage());
        }
    }

    public function updateArea(Request $request)
    {

        try {
            $idArea = $request->input('id');
            $area = Area::find($idArea);

            if (!$area) {
                return CustomResponse::make($area, 'Area no encontrada', 404, null);
            }

            // Validar los datos antes de editar el área
            $validatedData = $request->validate([
                'nombre' => 'required|string',
                'empresa_id' => 'required|integer',
                'jefe_area' => 'required|integer',
            ]);

            $area->nombre = $request->input('nombre');
            $area->empresa_id = $request->input('empresa_id');
            $area->jefe_area = $request->input('jefe_area');
            $area->save();

            return CustomResponse::make($area, 'Area de empresa actualizada con éxito', 201, null);
        } catch (\Exception $e) {
            // Manejar cualquier error y devolver una respuesta de error adecuada
            return CustomResponse::make(null, 'No se pudo editar el área. Detalles:', 500, $e->getMessage());
        }
    }

    public function deleteArea(Request $request)
    {

        try {
            $id = $request->input('id');

            $area = Area::findOrFail($id);
            $area->delete();
            return CustomResponse::make($area, 'Area eliminada con éxito', 201, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Error al eliminar el area', 500, $e->getMessage());
        }
    }

    public function listaDetalleAreas(Request $request)
    {
        try {
            $idEmpresa = $request->input('id_empresa');

            // Realizar la consulta utilizando Eloquent
            $query = DB::table('areas as a')
                ->join('empresas as e', 'a.empresa_id', '=', 'e.id')
                ->leftJoin('empleados as em', 'em.id', '=', 'a.jefe_area')
                ->select('a.id as area_id', 'a.nombre as nombre_area', 'e.nombre as nombre_empresa', DB::raw('CONCAT(em.nombres, " ", em.apellidos) as nombre_jefe_area'), 'a.jefe_area as id_jefe', 'e.id as id_empresa');

            if (!empty($idEmpresa)) {
                $query->where('e.id', $idEmpresa);
            }

            $resultados = $query->get();

            if ($resultados == null) {
                return CustomResponse::make(null, 'No hay elementos disponibles', 400, null);
            }

            // Devolver los resultados en formato JSON
            return CustomResponse::make($resultados, '', 200, null);
        } catch (Exception $e) {
            return CustomResponse::make(null, 'Hubo un problema en el servidor', 500, $e->getMessage());
        }
    }

    public function listaJefeEmpleado()
    {
        try {
            $usuarioJefe = DB::table('empleados as em')
                ->join('areas as a', 'a.id', '=', 'em.area_id')
                ->join('empresas as e', 'a.empresa_id', '=', 'e.id')
                ->join('usuarios as u', 'u.empleado_id', '=', 'em.id')
                ->select('u.id', DB::raw('CONCAT(em.nombres, " ", em.apellidos) as nombre_jefe'))
                ->where('u.rol', '=', 'jefe')
                ->whereNull('a.jefe_area')
                ->get();

            if ($usuarioJefe == null) {
                return CustomResponse::make(null, 'No hay elementos disponibles', 400, null);
            }

            return CustomResponse::make($usuarioJefe, '', 200, null);
        } catch (Exception $e) {
            return CustomResponse::make(null, 'Hubo un error en el servidor', 500, $e->getMessage());
        }
    }
}
