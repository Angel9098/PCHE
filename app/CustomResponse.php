<?php

namespace App;

use Illuminate\Http\JsonResponse;

class CustomResponse
{
    public static function make($data = null, $message = '', $status = null, $error = '')
    {
        return new JsonResponse([
            'status' => $status,
            'object' => $data,
            'message' => $message,
            'error' => $error,
        ], $status);
    }
}
