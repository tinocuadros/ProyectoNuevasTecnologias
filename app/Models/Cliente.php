<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
<<<<<<< HEAD
    protected $primaryKey = 'id_cliente';
    public $timestamps = false;
    protected $fillable = ['nombre', 'telefono', 'direccion', 'correo'];
}
=======
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'codigo',
        'nombre',
        'cedula',
        'telefono',
        'email',
        'direccion',
        'activo'
    ];
}
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
