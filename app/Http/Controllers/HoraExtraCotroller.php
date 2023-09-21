<?php

namespace App\Http\Controllers;

use App\Empleado;
use App\HoraExtra;
use Illuminate\Http\Request;

class HoraExtraCotroller extends Controller
{
    public function createHoraExtra(Request $request)
    {

        $registros = json_decode($request->input('registros'), true);
        dd($registros);
        foreach ($registros as $registro) {
            $id_empleado = $registro['idEmpleado'];
            $id_empleado = $registro['fecha'];
            $diurnas = $registro['diurnas'];
            $nocturnas = $registro['nocturnas'];
            $diurnas_descanso = $registro['diurnas_descanso'];
            $nocturnas_descanso = $registro['nocturnas_descanso'];
            $diurnas_asueto = $registro['diurnas_asueto'];
            $nocturnas_asueto = $registro['nocturnas_asueto'];
        }

        /* $hora_extra = new HoraExtra([
            'id_empleado' => $request->input('id_empleado'),
            'diurnas' => $request->input('diurnas'),
            'nocturnas' => $request->input('nocturnas'),
            'diurnas_descanso' => $request->input('diurnas_descanso'),
            'nocturnas_descanso' => $request->input('nocturnas_descanso'),
            'diurnas_asueto' => $request->input('diurnas_asueto'),
            'nocturnas_asueto' => $request->input('nocturnas_asueto'),
            'total_efectivo' => $request->input('total_efectivo'),
            'total' => $request->input('total'),
        ]);*/

        // $hora_extra ->save();
        return response()->json(["message" => 'Horas extra agregado con exito'], 201);
    }

    public function allHoras()
    {

        $horas = HoraExtra::with('empleados.horas_extra')->get();

        return response()->json($horas);
    }
}
