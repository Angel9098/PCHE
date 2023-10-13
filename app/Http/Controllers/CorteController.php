<?php

namespace App\Http\Controllers;

use App\Corte;
use App\CustomResponse;
use Exception;
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

            return CustomResponse::make($corte, 'Corte agregado con exito', 201, null);
        }catch(QueryException $ex){
            return CustomResponse::make(null, 'Error al agregar el corte:', 500, $ex->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $corte = Corte::find($id);

            if (!$corte) {
                return CustomResponse::make(null, 'Corte no encontrado', 404, null);
            }
            $corteId = $request->input('id');

            $corte->descripcion = $request->input('descripcion');
            $corte->corteFecha = $request->input('fecha_corte');
            $corte->save();

            return CustomResponse::make($corte, 'Corte actualizado con exito', 201, null);
        }catch(QueryException $ex){
            return CustomResponse::make(null, 'Error al actualizar el corte:', 500, $ex->getMessage());
        }
    }

    public function getCorteVigente()
    {
        try{
            $vigente = Corte::where('vigente', 1)->first();
            //$vigente = DB::table('cortes')
                            //->select('fecha_corte')
                            //->where('vigente', '=', 1)
                            //->get();
            if($vigente != null){
                return CustomResponse::make($vigente, '', 200, null);
            }
            else{
                return CustomResponse::make(null, 'Corte vigente no encontrado', 404, null);
            }
        }catch(Exception $ex){
            return CustomResponse::make(null, 'Error al actualizar el corte:', 500, $ex->getMessage());
        }
    }
}
