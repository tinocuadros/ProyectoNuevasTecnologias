<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_compras';

    protected $fillable = [
        'compra_id',
        'articulo_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}
