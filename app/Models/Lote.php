<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';

    protected $fillable = [
        'articulo_id',
        'cantidad',
        'cantidad_disponible',
        'costo_unitario',
        'fecha_entrada',
        'compra_id',
        'activo'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    // Obtener el lote más antiguo disponible (PEPS)
    public static function getLoteMasAntiguo($articuloId)
    {
        return self::where('articulo_id', $articuloId)
            ->where('activo', true)
            ->where('cantidad_disponible', '>', 0)
            ->orderBy('fecha_entrada', 'asc')
            ->orderBy('id', 'asc')
            ->first();
    }
}
