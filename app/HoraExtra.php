<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HoraExtra extends Model
{
    protected $table = 'horas_extra';

    protected $fillable = [
        'id_empleado', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo', 'total'
    ];

    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }
}
