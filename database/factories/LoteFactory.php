<?php

namespace Database\Factories;

use App\Models\Lote;
use App\Models\Articulo;
use App\Models\Compra;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoteFactory extends Factory
{
    protected $model = Lote::class;

    public function definition(): array
    {
        $cantidad = $this->faker->numberBetween(10, 500);

        return [
            'articulo_id' => Articulo::factory(),
            'cantidad' => $cantidad,
            'cantidad_disponible' => $cantidad,
            'costo_unitario' => $this->faker->numberBetween(100, 5000),
            'fecha_entrada' => $this->faker->date(),
            'compra_id' => Compra::factory(),
            'activo' => true,
        ];
    }
}
