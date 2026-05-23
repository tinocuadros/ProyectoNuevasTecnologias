<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
<<<<<<< HEAD
    protected $primaryKey = 'id_venta';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente', 'id_usuario', 'fecha_venta', 'total', 'metodo_pago'
=======
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'cliente_id',
        'user_id',
        'fecha_venta',
        'total',
        'estado',
        'numero_factura'
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
    ];

    public function cliente()
    {
<<<<<<< HEAD
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
=======
        return $this->belongsTo(Cliente::class);
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
    }

    public function detalles()
    {
<<<<<<< HEAD
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }
}
=======
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
