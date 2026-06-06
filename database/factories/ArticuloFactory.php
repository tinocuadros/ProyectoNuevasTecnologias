<?php

namespace Database\Factories;

use App\Models\Articulo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticuloFactory extends Factory
{
    protected $model = Articulo::class;

    public function definition(): array
    {
        return [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{5}'),
            'nombre' => $this->faker->words(3, true),
            'descripcion' => $this->faker->paragraph(),
            'precio_venta' => $this->faker->numberBetween(1000, 100000),
            'stock' => $this->faker->numberBetween(0, 500),
            'stock_minimo' => $this->faker->numberBetween(5, 50),
            'ubicacion' => $this->faker->regexify('[A-Z][0-9]{1,2}'),
        ];
    }
}
