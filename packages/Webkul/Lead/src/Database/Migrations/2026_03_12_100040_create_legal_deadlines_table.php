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
        Schema::create('legal_deadlines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');                          // Processo
            $table->string('title');                                         // Título do prazo
            $table->text('description')->nullable();                        // Descrição
            $table->date('start_date');                                      // Data de início do prazo
            $table->date('due_date');                                        // Data limite
            $table->integer('business_days')->nullable();                   // Dias úteis
            $table->string('status')->default('pendente');                  // Estado (pendente, concluído, expirado)
            $table->string('priority')->default('normal');                  // Prioridade (baixa, normal, alta, urgente)
            $table->boolean('court_deadline')->default(false);              // Prazo judicial obrigatório
            $table->unsignedBigInteger('user_id');                          // Advogado responsável
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
        Schema::dropIfExists('legal_deadlines');
    }
};
