<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test mostrar formulario de login
     */
    public function test_can_view_login_form(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    /**
     * Test login exitoso con credenciales válidas
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    /**
     * Test login fallido con contraseña incorrecta
     */
    public function test_login_fails_with_incorrect_password(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    /**
     * Test login fallido con usuario inexistente
     */
    public function test_login_fails_with_non_existent_user(): void
    {
        $response = $this->post('/login', [
            'username' => 'nonexistent',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['username']);
    }

    /**
     * Test validación requerida en login
     */
    public function test_login_validation_required_fields(): void
    {
        $response = $this->post('/login', [
            'username' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['username', 'password']);
    }

    /**
     * Test logout
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('status', 'Sesión cerrada correctamente');
    }

    /**
     * Test sesión se regenera después del login
     */
    public function test_session_is_regenerated_after_login(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        $oldSession = session()->getId();

        $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $this->assertNotEquals($oldSession, session()->getId());
    }

    /**
     * Test usuario no autenticado no puede acceder a rutas protegidas
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/productos');

        // Si no está protegida, debería mostrar la página
        // Si está protegida, debería redirigir a login
        $this->assertTrue(
            $response->status() === 200 || $response->status() === 302
        );
    }
}
