<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\Permiso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_has_permisos_relationship(): void
    {
        $role = Role::factory()->create();
        $permiso1 = Permiso::factory()->create();
        $permiso2 = Permiso::factory()->create();

        $role->permisos()->attach([$permiso1->id, $permiso2->id]);

        $this->assertCount(2, $role->permisos);
        $this->assertTrue($role->permisos->contains($permiso1));
        $this->assertTrue($role->permisos->contains($permiso2));
    }

    public function test_role_has_usuarios_relationship(): void
    {
        $role = Role::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $role->usuarios()->attach([$user1->id, $user2->id]);

        $this->assertCount(2, $role->usuarios);
        $this->assertTrue($role->usuarios->contains($user1));
        $this->assertTrue($role->usuarios->contains($user2));
    }

    public function test_role_fillable_attributes(): void
    {
        $data = [
            'nombre' => 'Administrator',
            'slug' => 'admin',
            'descripcion' => 'Sistema Administrator role'
        ];

        $role = Role::create($data);

        $this->assertEquals('Administrator', $role->nombre);
        $this->assertEquals('admin', $role->slug);
        $this->assertEquals('Sistema Administrator role', $role->descripcion);
    }

    public function test_role_with_multiple_permissions(): void
    {
        $role = Role::factory()->create();
        
        $permisos = Permiso::factory()->count(5)->create();
        $role->permisos()->attach($permisos->pluck('id'));

        $this->assertCount(5, $role->permisos);
    }

    public function test_role_with_multiple_users(): void
    {
        $role = Role::factory()->create();
        
        $users = User::factory()->count(5)->create();
        $role->usuarios()->attach($users->pluck('id'));

        $this->assertCount(5, $role->usuarios);
    }
}
