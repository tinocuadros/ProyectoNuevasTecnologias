<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $nombre = $this->faker->unique()->word();
        return [
            'nombre' => ucfirst($nombre),
            'slug' => $nombre,
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
