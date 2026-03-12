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
        Schema::create('legal_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');                                               // Título do Documento
            $table->string('document_type');                                       // Tipo (Petição, Contestação, Recurso, etc.)
            $table->text('description')->nullable();                              // Descrição
            $table->string('file_path');                                          // Caminho do Ficheiro
            $table->string('file_type')->nullable();                              // Tipo de Ficheiro (pdf, docx, etc.)
            $table->unsignedBigInteger('lead_id')->nullable();                    // Processo associado
            $table->unsignedBigInteger('person_id')->nullable();                  // Cliente associado
            $table->unsignedBigInteger('user_id');                                // Advogado responsável
            $table->string('status')->default('rascunho');                        // Estado (rascunho, enviado, arquivado)
            $table->date('due_date')->nullable();                                 // Data limite
            $table->date('filing_date')->nullable();                              // Data de protocolo no tribunal
            $table->string('court_reference')->nullable();                        // Referência no tribunal
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_documents');
    }
};
