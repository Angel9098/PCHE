<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAct extends Model
{
    public $idUsuario;
    public $oldPassword;
    public $newPassword;
    public $confirmPassword;
}
