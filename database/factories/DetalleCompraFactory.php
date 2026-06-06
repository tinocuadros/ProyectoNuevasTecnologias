<?php

namespace Database\Factories;

use App\Models\DetalleCompra;
use App\Models\Compra;
use App\Models\Articulo;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetalleCompraFactory extends Factory
{
    protected $model = DetalleCompra::class;

    public function definition(): array
    {
        $cantidad = $this->faker->numberBetween(1, 100);
        $precio_unitario = $this->faker->numberBetween(100, 10000);

        return [
            'compra_id' => Compra::factory(),
            'articulo_id' => Articulo::factory(),
            'cantidad' => $cantidad,
            'precio_unitario' => $precio_unitario,
            'subtotal' => $cantidad * $precio_unitario,
        ];
    }
}
