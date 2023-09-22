<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalculosHoras extends Model
{
    protected $fillable = [
        'id_corte', 'empleado_id', 'salario_mensual', 'total_horas', 'salario_neto', 'salario_total', 'fecha_calculo'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
