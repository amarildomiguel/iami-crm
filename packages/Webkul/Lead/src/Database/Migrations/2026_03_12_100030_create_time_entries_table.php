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
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');                          // Processo
            $table->unsignedBigInteger('user_id');                          // Advogado
            $table->date('entry_date');                                      // Data do registo
            $table->decimal('hours', 5, 2);                                 // Horas trabalhadas
            $table->text('description');                                     // Descrição do trabalho realizado
            $table->string('activity_type');                                 // Tipo de actividade (Audiência, Reunião, Redacção, etc.)
            $table->decimal('hourly_rate', 15, 2)->nullable();              // Taxa horária (Kz)
            $table->decimal('total_amount', 15, 2)->nullable();             // Valor total (Kz)
            $table->boolean('billable')->default(true);                     // Facturável
            $table->boolean('billed')->default(false);                      // Já facturado
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
        Schema::dropIfExists('time_entries');
    }
};
