<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HoraExtra extends Model
{
    protected $table = 'horas_extra';

    protected $fillable = [
        'empleado_id', 'diurnas', 'nocturnas', 'diurnas_descanso', 'diurnas_descanso', 'nocturnas_descanso', 'diurnas_asueto', 'nocturnas_asueto', 'total', 'fecha_registro', 'no_carga', 'jefe_area'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
