<?php

namespace App\Http\Controllers;

use App\Corte;
use Illuminate\Http\Request;


class CorteController extends Controller
{

    public function index()
    {
        $cortes = Corte::all();
        return response()->json($cortes);
    }

    public function create(Request $request)
    {

        $corte = new Corte([
            'descripcion' => $request->input('descripcion'),
            'fecha_corte' => $request->input('fecha_corte'),
            'vigente' => true
        ]);

        $corte->save();

        return response()->json(["message" => 'Corte agregado con exito'], 201);
    }

    public function update(Request $request, $id)
    {

        $corte = Corte::find($id);

        if (!$corte) {
            return response()->json(['message' => 'Post no encontrado'], 404);
        }
        $corteId = $request->input('id');

        $corte->descripcion = $request->input('descripcion');
        $corte->corteFecha = $request->input('fecha_corte');
        $corte->save();

        return response()->json(["message" => 'Corte actualizado con exito'], 201);
    }
}
