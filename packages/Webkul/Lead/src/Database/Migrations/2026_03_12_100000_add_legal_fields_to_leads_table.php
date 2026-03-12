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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('case_number')->nullable()->unique()->after('description'); // Número do Processo
            $table->string('court')->nullable()->after('case_number');                // Tribunal
            $table->string('court_section')->nullable()->after('court');              // Secção/Vara
            $table->string('case_type')->nullable()->after('court_section');          // Tipo (Cível, Penal, Laboral, etc.)
            $table->string('jurisdiction')->nullable()->after('case_type');           // Jurisdição/Comarca
            $table->string('judge_name')->nullable()->after('jurisdiction');          // Nome do Juiz
            $table->string('opponent_name')->nullable()->after('judge_name');         // Parte Contrária
            $table->string('opponent_lawyer')->nullable()->after('opponent_name');    // Advogado da Parte Contrária
            $table->date('filing_date')->nullable()->after('opponent_lawyer');        // Data de Entrada
            $table->date('next_hearing_date')->nullable()->after('filing_date');      // Próxima Audiência
            $table->string('urgency_level')->nullable()->after('next_hearing_date'); // Nível de Urgência
            $table->string('legal_area')->nullable()->after('urgency_level');         // Área Jurídica
            $table->text('case_summary')->nullable()->after('legal_area');            // Resumo do Caso
            $table->string('province')->nullable()->after('case_summary');            // Província
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'case_number',
                'court',
                'court_section',
                'case_type',
                'jurisdiction',
                'judge_name',
                'opponent_name',
                'opponent_lawyer',
                'filing_date',
                'next_hearing_date',
                'urgency_level',
                'legal_area',
                'case_summary',
                'province',
            ]);
        });
    }
};
