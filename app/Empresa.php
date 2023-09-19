<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'nombre',
        'direccion',
        'rubro',
        'imagen'
    ];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
