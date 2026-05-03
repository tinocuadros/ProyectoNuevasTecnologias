<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precio_venta',
        'stock',
        'stock_minimo',
        'ubicacion',
        'activo'
    ];

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'articulo_id')->where('activo', true)->where('cantidad_disponible', '>', 0);
    }
}
