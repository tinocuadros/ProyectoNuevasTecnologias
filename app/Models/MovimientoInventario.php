<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'articulo_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'motivo',
        'documento_id',
        'documento_type',
        'user_id'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function documento()
    {
        return $this->morphTo();
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
