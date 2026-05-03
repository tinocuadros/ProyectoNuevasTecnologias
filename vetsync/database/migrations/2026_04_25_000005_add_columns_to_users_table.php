<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cedula')->nullable()->unique()->after('id');
            $table->string('primer_nombre')->after('cedula');
            $table->string('segundo_nombre')->nullable()->after('primer_nombre');
            $table->string('primer_apellido')->after('segundo_nombre');
            $table->string('segundo_apellido')->nullable()->after('primer_apellido');
            $table->string('username')->nullable()->unique()->after('segundo_apellido');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cedula', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'username']);
        });
    }
};