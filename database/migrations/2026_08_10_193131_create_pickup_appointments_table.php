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
    Schema::create('pickup_appointments', function (Blueprint $table) {
        $table->id();

        // 🔗 Relaciones con Clientes, Zonas y el Usuario que agendó
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->foreignId('zone_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('user_id')->comment('Empleado o Admin que agendó')->constrained();

        // 📅 Información de la Agenda (Día específico de Lunes a Sábado)
        $table->date('scheduled_date'); 

        // 📦 Datos de la Carga (Campos específicos, sin mezclar texto)
        $table->integer('box_quantity')->default(1);
        $table->string('box_dimensions')->comment('Ejemplo: 18x18x24 pulgadas');
        $table->string('tracking_number')->nullable()->comment('Se asigna al momento de recoger la caja');

        // 📋 Estado de la recolección
        $table->enum('status', ['programado', 'recolectado', 'cancelado'])->default('programado');
        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_appointments');
    }
};
