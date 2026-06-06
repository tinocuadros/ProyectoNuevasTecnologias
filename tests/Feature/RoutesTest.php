<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutesTest extends TestCase
{
    /**
     * Test ruta home está registrada
     */
    public function test_home_route_exists(): void
    {
        $response = $this->get('/');

        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test ruta login está registrada
     */
    public function test_login_route_exists(): void
    {
        $response = $this->get('/login');

        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test ruta productos está registrada
     */
    public function test_productos_route_exists(): void
    {
        $response = $this->get('/productos');

        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test ruta crear producto está registrada
     */
    public function test_create_producto_route_exists(): void
    {
        $response = $this->get('/productos/create');

        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test POST route para login
     */
    public function test_post_login_route_exists(): void
    {
        $response = $this->post('/login', [
            'username' => 'test',
            'password' => 'test',
        ]);

        // No debe ser 404
        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test POST route para logout
     */
    public function test_post_logout_route_exists(): void
    {
        $response = $this->post('/logout');

        // No debe ser 404 (puede ser 302 redirect o 200)
        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test resource routes para productos
     */
    public function test_resource_routes_registered(): void
    {
        // Index
        $this->assertNotEquals(404, $this->get('/productos')->status());
        // Create
        $this->assertNotEquals(404, $this->get('/productos/create')->status());
    }
}
