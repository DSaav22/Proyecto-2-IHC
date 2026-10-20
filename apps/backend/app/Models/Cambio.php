<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cambio extends Model
{
    protected $table = 'cambios';

    protected $fillable = ['user_id', 'plan_id', 'nombre', 'accion', 'resultado'];
}
