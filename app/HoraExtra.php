<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HoraExtra extends Model
{
    protected $table = 'horas_extra';

    protected $fillable = [
        'empleado_id', 'diurnas', 'diurnas_descanso', 'diurnas_descanso', 'nocturnas_descanso', 'diurnas_asueto', 'nocturnas_asueto', 'total', 'fecha_registro'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
