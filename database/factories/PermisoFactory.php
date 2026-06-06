<?php

namespace Database\Factories;

use App\Models\Permiso;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermisoFactory extends Factory
{
    protected $model = Permiso::class;

    public function definition(): array
    {
        $nombre = $this->faker->unique()->word();
        return [
            'nombre' => ucfirst($nombre),
            'slug' => str_replace(' ', '.', strtolower($nombre)),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
