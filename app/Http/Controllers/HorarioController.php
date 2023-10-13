<?php

namespace App\Http\Controllers;

use App\Horario;
use App\CustomResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{

    public function index()
    {
        $horarios = Horario::all();
        return response()->json($horarios);
    }

    public function consultaDehorarioPorEmpleado(Request $request){

        try {
            $nombreEmpleado = $request->input('nombres');

            $horario = DB::table('empleados')
            ->join('horarios', 'empleados.horario_id', '=', 'horarios.id')
            ->select('horarios.*')
            ->where('empleados.nombres', 'LIKE', "%$nombreEmpleado%")
            ->get();
           // $horario = Horario::all();
           return CustomResponse::make($horario, '', 200, null);

        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Error al crear registro', 500, $e->getMessage());
        }

    }
}
