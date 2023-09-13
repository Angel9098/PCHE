<?php

namespace App\Http\Controllers;

use App\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{

    public function index()
    {
        $horarios = Horario::all();
        return response()->json($horarios);
    }

    public function create()
    {

    }


    public function store(Request $request)
    {
        $horario = new Horario([
            'turno' => $request->input('turno'),
            'descripcion' => $request->input('descripcion'),
            'horas_semana' => $request->input('horas_semana')

        ]);

        $horario->save();

        return response()->json(['message' => 'Post creado exitosamente'], 201);
    }


    public function show(Horario $horario)
    {

    }


    public function edit(Horario $horario)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Horario $horario)
    {

    }


    public function destroy(Horario $horario)
    {

    }
}
