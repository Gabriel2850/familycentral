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
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('name');                         // Nombre del cliente
        $table->string('phone')->unique();              // Teléfono principal (Identificador clave para recurrencia)
        $table->string('email')->nullable();            // Correo opcional
        $table->string('address');                      // Dirección por defecto
        
        // 🔄 Estado de Recurrencia
        $table->boolean('is_recurrent')->default(false); // false = Nuevo, true = Recurrente
        
        // 🗓️ Fecha del primer envío registrado
        $table->timestamp('first_shipped_at')->nullable();
        
        $table->timestamps();
        $table->softDeletes(); // 🗑️ Borrado suave (mantiene integridad en historiales)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
