<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articulo_id')->constrained('articulos')->onDelete('cascade');
            $table->integer('cantidad')->default(0);
            $table->integer('cantidad_disponible')->default(0);
            $table->decimal('costo_unitario', 10, 2);
            $table->date('fecha_entrada');
            $table->foreignId('compra_id')->nullable()->constrained('compras')->onDelete('set null');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lotes');
    }
};
