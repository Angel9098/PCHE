<?php

namespace App\Http\Controllers;

use App\Area;
use App\Empresa;
use App\CustomResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class EmpresaController extends Controller
{

    public function allempresas()
    {
        $empresas = Empresa::orderBy('id', 'asc')->paginate(5);
        return response()->json($empresas);
    }

    public function deleteEmpresa(Request $request)
    {
        try {
            $id = $request->input('id');

            // Encuentra la empresa por su ID junto con sus áreas y empleados relacionados
            $empresa = Empresa::with('areas.empleados')->findOrFail($id);

            // Recorre todas las áreas de la empresa y elimina a los empleados
            foreach ($empresa->areas as $area) {
                $area->empleados()->delete();
            }

            // Elimina las áreas de la empresa
            $empresa->areas()->delete();

            // Finalmente, elimina la empresa
            $empresa->delete();
            return CustomResponse::make($empresa, 'La empresa ha sido eliminada exitosamente', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'La empresa no ha podido eliminarse', 500, $e->getMessage());
        }
    }

    public function empresaById(Request $request)
    {
        try {
            $id = $request->input('id');

            $empresa = Empresa::where('id', $id)->first();

            // Construye la URL completa de la imagen
            if ($empresa->imagen) {
                $empresa->imagen_url = asset('storage/imagenes/' . $empresa->imagen);
            }

            return CustomResponse::make($empresa, 'Empresa recuperada con exito', 200, null);
        } catch (ModelNotFoundException $ex) {
            return CustomResponse::make(null, 'Empresa no encontrada', 404, $ex->getMessage());
        } catch (\Exception $e) {
            return CustomResponse::make(null, 'Error al obtener la empresa', 500, null);
        }
    }


    public function create(Request $request)
    {
        try {
            $nombreImagen = null;
            /* if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();
                $rutaImagen = $imagen->storeAs('public/imagenes', $nombreImagen);
            } */

            if ($request->has('imagen')) {
                $base64Image = $request->input('imagen');
                $imagen = base64_decode($base64Image);

                // Se genera un nombre único para la imagen
                $nombreImagen = Str::random(10) . '.png';

                // Se guarda la imagen en el almacenamiento
                $rutaImagen = storage_path('app/public/imagenes/' . $nombreImagen);
                file_put_contents($rutaImagen, $imagen);
            }



            $empresa = new Empresa([
                'nombre' => $request->input('nombre'),
                'direccion' => $request->input('direccion'),
                'rubro' => $request->input('rubro'),
                'imagen' => $nombreImagen
            ]);

            $empresa->save();

            return CustomResponse::make($empresa, 'Empresa creada exitosamente', 200, null);
        } catch (QueryException $ex) {
            return CustomResponse::make(null, 'Error al agregar empresa:', 500, $ex->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $empresaId = $request->input('id');

            $empresa = Empresa::findOrFail($empresaId);

/*             if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();
                $rutaImagen = $imagen->storeAs('public/imagenes', $nombreImagen);
                $empresa->imagen = $nombreImagen;
            } */

            if ($request->has('imagen')) {
                $base64Image = $request->input('imagen');
                $imagen = base64_decode($base64Image);

                // Genera un nuevo nombre único para la imagen
                $nombreImagen = Str::random(10) . '.png';

                // Elimina la imagen anterior si existe
                if ($empresa->imagen) {
                    $rutaImagenAnterior = storage_path('app/public/imagenes/' . $empresa->imagen);
                    if (file_exists($rutaImagenAnterior)) {
                        unlink($rutaImagenAnterior);
                    }
                }

                // Guarda la nueva imagen en el almacenamiento
                $rutaImagen = storage_path('app/public/imagenes/' . $nombreImagen);
                file_put_contents($rutaImagen, $imagen);

                // Actualiza el nombre de la imagen en el modelo
                $empresa->imagen = $nombreImagen;
            }

            $empresa->nombre = $request->input('nombre');
            $empresa->direccion = $request->input('direccion');
            $empresa->rubro = $request->input('rubro');

            $empresa->save();

            return CustomResponse::make($empresa, 'Empresa actualizada exitosamente', 200, null);
        } catch (ModelNotFoundException $ex) {
            return CustomResponse::make(null, 'Empresa no encontrada', 404, null);
        } catch (QueryException $ex) {
            return CustomResponse::make(null, 'Error al actualizar empresa:', 500, $ex->getMessage());
        }
    }

    public function getAreasEmpresa(Request $request)
    {

        try {
            $id = $request->input('id');

            $empresas = Empresa::find($id);

            if (!$empresas) {
                return CustomResponse::make(null, 'Empresa no encontrada', 404, null);
            }

            $areas = $empresas->areas;

            return CustomResponse::make($areas, '', 200, null);
        } catch (\Exception $e) {
            return CustomResponse::make(null, '', 500, $e->getMessage());
        }
    }
}
