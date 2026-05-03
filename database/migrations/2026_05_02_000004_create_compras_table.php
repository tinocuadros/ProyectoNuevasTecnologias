<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura')->unique()->nullable();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('restrict');
            $table->date('fecha');
            $table->decimal('total', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compras');
    }
};
