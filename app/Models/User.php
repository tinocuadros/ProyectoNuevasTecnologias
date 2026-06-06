<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'username', 'password', 'rol', 'estado'
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberTokenName()
    {
        return null;
    }
}
