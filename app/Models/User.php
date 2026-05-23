<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'username', 'password', 'rol', 'estado'
    ];

    protected $hidden = ['password'];
=======
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Mail;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cedula',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'username',
        'ultimo_login',
        'session_id',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'usuario_rol', 'usuario_id', 'rol_id');
    }

    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(Permiso::class, 'usuario_rol')
            ->withPivot('rol_id');
    }

    public function hasPermission($slug): bool
    {
        foreach ($this->roles as $role) {
            foreach ($role->permisos as $permiso) {
                if ($permiso->slug === $slug) {
                    return true;
                }
            }
        }
        return false;
    }
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8

    protected function casts(): array
    {
        return [
<<<<<<< HEAD
=======
            'email_verified_at' => 'datetime',
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
            'password' => 'hashed',
        ];
    }

<<<<<<< HEAD
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    // ✅ Esto evita el error de remember_token
    public function getRememberTokenName()
    {
        return null;
    }
=======

    public function sendEmailVerificationNotification()
        {

            // 1. Generamos la URL firmada que Laravel espera
            $urlVerification = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'verification.verify', // El nombre de la ruta que tienes en web.php
                now()->addMinutes(60), // La llave vence en una hora
                ['id' => $this->getKey(), 'hash' => sha1($this->getEmailForVerification())]
            );

            
            Mail::to($this->email)->send(new \App\Mail\BienvenidaVetSync($this, $urlVerification));
        }
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
}