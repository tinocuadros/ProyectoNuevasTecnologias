<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Role;
use App\Models\Permiso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_permission_returns_true_when_permission_exists(): void
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => 'admin']);
        $permiso = Permiso::factory()->create(['slug' => 'users.create']);

        $user->roles()->attach($role);
        $role->permisos()->attach($permiso);

        $this->assertTrue($user->hasPermission('users.create'));
    }

    public function test_user_has_permission_returns_false_when_permission_not_exists(): void
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => 'user']);
        $permiso = Permiso::factory()->create(['slug' => 'users.create']);

        $user->roles()->attach($role);
        $role->permisos()->attach($permiso);

        $this->assertFalse($user->hasPermission('users.delete'));
    }

    public function test_user_has_permission_with_multiple_roles(): void
    {
        $user = User::factory()->create();
        $roleAdmin = Role::factory()->create(['slug' => 'admin']);
        $roleUser = Role::factory()->create(['slug' => 'user']);

        $permisoCreate = Permiso::factory()->create(['slug' => 'users.create']);
        $permisoDelete = Permiso::factory()->create(['slug' => 'users.delete']);

        $user->roles()->attach([$roleAdmin->id, $roleUser->id]);
        $roleAdmin->permisos()->attach($permisoCreate);
        $roleUser->permisos()->attach($permisoDelete);

        $this->assertTrue($user->hasPermission('users.create'));
        $this->assertTrue($user->hasPermission('users.delete'));
    }

    public function test_user_has_permission_returns_false_when_no_roles(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->hasPermission('users.create'));
    }

    public function test_user_has_permission_with_multiple_permissions_in_role(): void
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => 'admin']);

        $permiso1 = Permiso::factory()->create(['slug' => 'users.create']);
        $permiso2 = Permiso::factory()->create(['slug' => 'users.edit']);
        $permiso3 = Permiso::factory()->create(['slug' => 'users.delete']);

        $user->roles()->attach($role);
        $role->permisos()->attach([$permiso1->id, $permiso2->id, $permiso3->id]);

        $this->assertTrue($user->hasPermission('users.create'));
        $this->assertTrue($user->hasPermission('users.edit'));
        $this->assertTrue($user->hasPermission('users.delete'));
        $this->assertFalse($user->hasPermission('users.view'));
    }

    public function test_user_fillable_attributes(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'hashed_password',
            'cedula' => '1234567890',
            'primer_nombre' => 'John',
            'segundo_nombre' => 'Michael',
            'primer_apellido' => 'Doe',
            'segundo_apellido' => 'Smith',
            'username' => 'johndoe',
            'activo' => true
        ];

        $user = User::create($data);

        foreach ($data as $key => $value) {
            if ($key !== 'password') {
                $this->assertEquals($value, $user->{$key});
            }
        }
    }

    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create();
        $this->assertNotEquals('password', $user->password);
    }

    public function test_user_roles_relationship(): void
    {
        $user = User::factory()->create();
        $role1 = Role::factory()->create();
        $role2 = Role::factory()->create();

        $user->roles()->attach([$role1->id, $role2->id]);

        $this->assertCount(2, $user->roles);
        $this->assertTrue($user->roles->contains($role1));
        $this->assertTrue($user->roles->contains($role2));
    }

    public function test_user_permisos_relationship(): void
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();
        $permiso = Permiso::factory()->create();

        $user->roles()->attach($role);
        $role->permisos()->attach($permiso);

        $this->assertTrue($user->permisos->contains($permiso));
    }

    public function test_user_email_verification_cast(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => '2026-06-06 12:00:00'
        ]);

        $this->assertInstanceOf(\DateTime::class, $user->email_verified_at);
    }
}
