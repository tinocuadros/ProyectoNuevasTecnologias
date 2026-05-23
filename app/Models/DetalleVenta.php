<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
<<<<<<< HEAD
    protected $primaryKey = 'id_detalle';
    public $timestamps = false;

    protected $fillable = [
        'id_venta', 'id_producto', 'cantidad', 'precio_unitario'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
=======
    use HasFactory;

    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id',
        'articulo_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
