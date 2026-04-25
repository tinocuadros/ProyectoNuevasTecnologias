<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            if (!Schema::hasColumn('productos', 'cantidad')) {
                $table->integer('cantidad')->default(0);
            }
            if (!Schema::hasColumn('productos', 'stock_minimo')) {
                $table->integer('stock_minimo')->default(5);
            }
            if (!Schema::hasColumn('productos', 'categoria')) {
                $table->string('categoria', 50)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            if (Schema::hasColumn('productos', 'cantidad')) {
                $table->dropColumn('cantidad');
            }
            if (Schema::hasColumn('productos', 'stock_minimo')) {
                $table->dropColumn('stock_minimo');
            }
            if (Schema::hasColumn('productos', 'categoria')) {
                $table->dropColumn('categoria');
            }
        });
    }
};