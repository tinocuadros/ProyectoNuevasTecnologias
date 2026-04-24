<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('id_usuario', true);
            $table->string('nombre', 100);
            $table->string('username', 50)->unique('username');
            $table->string('password');
            $table->enum('rol', ['Administrador', 'Vendedor', 'Auxiliar Compras', 'Auxiliar Inventario', 'Jefe Ventas']);
            $table->dateTime('fecha_creacion')->nullable()->useCurrent();
            $table->dateTime('fecha_actualizacion')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->boolean('estado')->nullable()->default(true);
            $table->integer('id_rol')->nullable()->index('fk_usuarios_roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
