<?php

namespace Database\Factories;

use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompraFactory extends Factory
{
    protected $model = Compra::class;

    public function definition(): array
    {
        return [
            'numero_factura' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{7}'),
            'proveedor_id' => Proveedor::factory(),
            'fecha' => $this->faker->date(),
            'user_id' => User::factory(),
            'observaciones' => $this->faker->sentence(),
            'total' => $this->faker->numberBetween(10000, 500000),
        ];
    }
}
