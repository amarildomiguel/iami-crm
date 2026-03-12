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
        Schema::create('hearings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');                     // Processo
            $table->string('hearing_type');                             // Tipo (Julgamento, Instrução, Conciliação, etc.)
            $table->dateTime('scheduled_at');                           // Data/Hora agendada
            $table->string('court');                                    // Tribunal
            $table->string('court_room')->nullable();                  // Sala
            $table->string('judge_name')->nullable();                  // Juiz
            $table->text('notes')->nullable();                         // Observações
            $table->string('status')->default('agendada');             // Estado (agendada, realizada, cancelada, adiada)
            $table->text('outcome')->nullable();                       // Resultado/Desfecho
            $table->unsignedBigInteger('user_id');                     // Advogado responsável
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hearings');
    }
};
