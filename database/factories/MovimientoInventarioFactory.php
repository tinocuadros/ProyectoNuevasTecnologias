<?php

namespace Database\Factories;

use App\Models\MovimientoInventario;
use App\Models\Articulo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovimientoInventarioFactory extends Factory
{
    protected $model = MovimientoInventario::class;

    public function definition(): array
    {
        $tipo = $this->faker->randomElement(['entrada', 'salida', 'ajuste']);
        $cantidad = $this->faker->numberBetween(1, 100);
        $stock_anterior = $this->faker->numberBetween(0, 1000);
        $stock_nuevo = $tipo === 'salida' 
            ? $stock_anterior - $cantidad 
            : $stock_anterior + $cantidad;

        return [
            'articulo_id' => Articulo::factory(),
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'stock_anterior' => $stock_anterior,
            'stock_nuevo' => $stock_nuevo,
            'motivo' => $this->faker->sentence(),
            'user_id' => User::factory(),
        ];
    }
}
