<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;


class EmpresaController extends Controller
{

    public function allempresas()
    {
        $empresas = Empresa::all();
        return response()->json($empresas);
    }

    public function create()
    {
        // Crear una nueva empresa
        $empresa = Empresa::create([
            'nombre' => 'Mi Empresa',
            'direccion' => '123 Calle Principal',
            'rubro' => 'Tecnología',
        ]);

        // Agregar áreas a la empresa
        $empresa->areas()->create([
            'nombre' => 'Área de Desarrollo',
        ]);

        $empresa->areas()->create([
            'nombre' => 'Área de Ventas',
        ]);
    }
}
