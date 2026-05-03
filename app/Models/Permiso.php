<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permiso extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'modulo',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permiso_rol', 'permiso_id', 'rol_id');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'usuario_rol');
    }
}