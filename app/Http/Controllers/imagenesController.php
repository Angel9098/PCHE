<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class imagenesController extends Controller
{
    public function mostrar($nombre)
    {
        // Ruta completa de la imagen
        $rutaImagen = 'public/imagenes/' . $nombre;
        // Verifica si la imagen existe en el almacenamiento
        if (Storage::disk('public')->exists($rutaImagen)) {
            // Obtener la imagen del directorio de almacenamiento público
            $imagen = Storage::disk('public')->get($rutaImagen);

            // Determinar el tipo de contenido
            $tipoContenido = Storage::disk('public')->mimeType($rutaImagen);

            // Devolver la imagen con el tipo de contenido adecuado
            return response($imagen)->header('Content-Type', $tipoContenido);
        } else {
            // Si la imagen no existe, puedes mostrar una imagen de reemplazo o un mensaje de error
            abort(404);
        }
    }
}
