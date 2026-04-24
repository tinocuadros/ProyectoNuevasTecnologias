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
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'ventas_ibfk_1')->references(['id_cliente'])->on('clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_usuario'], 'ventas_ibfk_2')->references(['id_usuario'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign('ventas_ibfk_1');
            $table->dropForeign('ventas_ibfk_2');
        });
    }
};
