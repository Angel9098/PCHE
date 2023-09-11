<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillable = [
        'turno', 'descripcion', 'horas_semana'
    ];

    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }
}
