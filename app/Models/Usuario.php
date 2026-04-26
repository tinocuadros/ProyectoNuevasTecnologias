<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id_usuario';
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'username',
        'password',
        'rol',
        'estado',
    ];

    protected $hidden = [
        'password',
    ];
}