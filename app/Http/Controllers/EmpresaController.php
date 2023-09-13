<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;


class EmpresaController extends Controller
{


    public function subirArchivo(Request $request)
    {
        // Verificar si se ha enviado una imagen
        if ($request->hasFile('imagen')) {
            // Obtener la imagen del formulario
            $imagen = $request->file('imagen');

            // Generar un nombre único para la imagen
            $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();

            // Almacenar la imagen en el almacenamiento local
            $rutaImagen = $imagen->storeAs('public/rutas_personalizadas', $nombreImagen);

            // Obtener la URL de la imagen
            $urlImagen = asset('storage/' . $rutaImagen);

            return response()->json($urlImagen);
            // Ahora $urlImagen contiene la URL pública de la imagen recién almacenada
        } else {
            // Manejar el caso en el que no se haya enviado una imagen
            // ...
        }
    }


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
