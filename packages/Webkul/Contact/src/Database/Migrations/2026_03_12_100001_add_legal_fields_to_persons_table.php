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
        Schema::table('persons', function (Blueprint $table) {
            $table->string('bi_number')->nullable()->after('job_title');                         // Nº do BI (Bilhete de Identidade)
            $table->string('nif')->nullable()->after('bi_number');                               // NIF (Número de Identificação Fiscal)
            $table->string('passport_number')->nullable()->after('nif');                         // Nº do Passaporte
            $table->string('nationality')->nullable()->default('Angolana')->after('passport_number'); // Nacionalidade
            $table->string('province')->nullable()->after('nationality');                         // Província
            $table->string('municipality')->nullable()->after('province');                        // Município
            $table->string('commune')->nullable()->after('municipality');                         // Comuna
            $table->string('client_type')->nullable()->after('commune');                          // Tipo (Autor, Réu, Testemunha, etc.)
            $table->date('date_of_birth')->nullable()->after('client_type');                     // Data de Nascimento
            $table->string('marital_status')->nullable()->after('date_of_birth');                // Estado Civil
            $table->string('profession')->nullable()->after('marital_status');                   // Profissão
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn([
                'bi_number',
                'nif',
                'passport_number',
                'nationality',
                'province',
                'municipality',
                'commune',
                'client_type',
                'date_of_birth',
                'marital_status',
                'profession',
            ]);
        });
    }
};
