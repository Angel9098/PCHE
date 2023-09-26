<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalculosExtra extends Model
{
    protected $table = 'calculos_horas';
    protected $fillable = [
        'id_corte', 'empleado_id', 'salario_mensual', 'total_horas', 'salario_neto', 'salario_total', 'fecha_calculo','jefe_area'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
