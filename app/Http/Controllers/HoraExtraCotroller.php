<?php

namespace App\Http\Controllers;

use App\HoraExtra;
use Illuminate\Http\Request;

class HoraExtraCotroller extends Controller
{
    public function createHoraExtra(Request $request){

        $id_empleado = $request->input('id_empleado');
        $id_empleado = $request->input('lunes');
        $id_empleado = $request->input('martes');
        $id_empleado = $request->input('miercoles');
        $id_empleado = $request->input('jueves');
        $id_empleado = $request->input('viernes');
        $id_empleado = $request->input('sabado');
        $id_empleado = $request->input('domingo');



        $hora_extra = new HoraExtra([
            'id_empleado' => $request->input('id_empleado'),
            'lunes' => $request->input('lunes'),
            'martes' => $request->input('martes'),
            'miercoles' => $request->input('miercoles'),
            'jueves' => $request->input('jueves'),
            'viernes' => $request->input('viernes'),
            'sabado' => $request->input('sabado'),
            'domingo' => $request->input('domingo'),
            'total' => $request->input('total'),
        ]);

        $hora_extra ->save();
        return response()->json(["message" => 'Horas extra agregado con exito'], 201);
    }

    public function allHoras(){
        $horas = HoraExtra::all();
        return response()->json($horas);
    }
}
