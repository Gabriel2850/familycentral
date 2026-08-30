<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pickup_appointments', function (Blueprint $table) {
            // Cambiar la columna a ENUM con los valores correctos o a string
            $table->enum('status', ['programado', 'recolectado', 'reprogramado', 'cancelado'])
                  ->default('programado')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('pickup_appointments', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }
};