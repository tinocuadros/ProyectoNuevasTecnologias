<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetupPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permiso de acceso admin (por si no existe)
        $permiso = \App\Models\Permiso::firstOrCreate(
            ['slug' => 'acceso-admin'],
            [
                'nombre' => 'Acceso Administración',
                'descripcion' => 'Permite acceder a los módulos de administración',
                'modulo' => 'Administración'
            ]
        );

        // Crear rol administrador
        $rolAdmin = \App\Models\Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso total al sistema'
            ]
        );

        // Asignar permiso al rol admin
        $rolAdmin->permisos()->syncWithoutDetaching([$permiso->id]);

        // Asignar rol admin al primer usuario (y crear usuario admin si no existe)
        $user = \App\Models\User::first();
        
        if (!$user) {
            $user = \App\Models\User::create([
                'name' => 'Administrador',
                'email' => 'admin@vetsync.com',
                'password' => bcrypt('admin123'),
                'primer_nombre' => 'Admin',
                'primer_apellido' => 'VetSync',
                'username' => 'admin',
                'activo' => true,
                'email_verified_at' => now()
            ]);
        }
        
        // Asignar rol admin al usuario
        $user->roles()->syncWithoutDetaching([$rolAdmin->id]);
        
        echo "Usuario admin: {$user->email} (password: admin123)\n";
        echo "Rol 'Administrador' creado con permiso 'acceso-admin'\n";
    }
}
