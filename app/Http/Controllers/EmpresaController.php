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
