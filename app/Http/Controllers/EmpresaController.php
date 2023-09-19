<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;


class EmpresaController extends Controller
{

    public function allempresas()
    {
        $empresas = Empresa::all();
        return response()->json($empresas);
    }

    public function deleteEmpresa(Request $request)
    {
        try {
            $id = $request->input('id');

            // Encuentra la empresa por su ID junto con sus áreas y empleados relacionados
            $empresa = Empresa::with('areas.empleados')->findOrFail($id);

            // Recorre todas las áreas de la empresa y elimina a los empleados
            foreach ($empresa->areas as $area) {
                $area->empleados()->delete();
            }

            // Elimina las áreas de la empresa
            $empresa->areas()->delete();

            // Finalmente, elimina la empresa
            $empresa->delete();

            return response()->json(['message' => 'La empresa ha sido eliminada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La empresa no ha podido eliminarse'], 500);
        }
    }


    public function create(Request $request)
    {
        try {
            $imagen = $request->file('imagen');
            $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();
            $rutaImagen = $imagen->storeAs('public/imagenes', $nombreImagen);

            $empresa = new Empresa([
                'nombre' => $request->input('descripcion'),
                'direccion' => $request->input('fecha_corte'),
                'direccion' => $request->input('rubro'),
                'imagen' => $rutaImagen
            ]);

            $empresa->save();

            return response()->json(["message" => 'Empresa agregada con exito'], 201);
        } catch (QueryException $ex) {
            return response()->json(["message" => 'Error al agregar empresa: ' . $ex->getMessage()], 500);
        }
    }
}
