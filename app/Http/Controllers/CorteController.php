<?php

namespace App\Http\Controllers;

use App\Corte;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CorteController extends Controller
{

    public function index()
    {
        $cortes = Corte::orderBy('created_at', 'desc')->paginate(5);
        return response()->json($cortes);
    }

    public function create(Request $request)
    {
        try{
            $corte = new Corte([
                'descripcion' => $request->input('descripcion'),
                'fecha_corte' => $request->input('fecha_corte'),
            ]);

            $corte->save();

            DB::statement('CALL actualizar_vigente()');

            return response()->json(["message" => 'Corte agregado con exito'], 201);
        }catch(QueryException $ex){
            return response()->json(["message" => 'Error al agregar el corte: ' . $ex->getMessage()], 500);
        }
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

    public function getCorteVigente()
    {

        $vigente = Corte::where('vigente', 1)->first();
        //$vigente = DB::table('cortes')
                        //->select('fecha_corte')
                        //->where('vigente', '=', 1)
                        //->get();
        if($vigente != null){
            return response()->json($vigente);
        }
        else{
            return response()->json(["message" => 'Corte vigente no encontrado'], 404);
        }
    }
}
