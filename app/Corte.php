<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Corte extends Model
{

    protected $table = 'cortes';

    protected $fillable = [
        'descripcion', 'fecha_corte'
    ];


}
