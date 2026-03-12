<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 6 — Conformidade Legal Angolana
 *
 * Adiciona campos de protecção de dados à tabela persons.
 * Em conformidade com a Lei n.º 22/11 de 17 de Junho (Lei da Protecção de Dados Pessoais de Angola).
 *
 * Obrigações:
 * - Registo do consentimento do titular dos dados
 * - Data e origem do consentimento
 * - Possibilidade de revogação do consentimento
 * - Finalidade do tratamento de dados
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            // Conformidade com Lei n.º 22/11 — Protecção de Dados Pessoais
            $table->boolean('data_consent')->default(false)->after('commune');              // Consentimento de tratamento de dados
            $table->timestamp('data_consent_at')->nullable()->after('data_consent');        // Data do consentimento
            $table->string('data_consent_source')->nullable()->after('data_consent_at');    // Origem do consentimento (presencial, email, web)
            $table->boolean('data_consent_revoked')->default(false)->after('data_consent_source'); // Consentimento revogado
            $table->timestamp('data_consent_revoked_at')->nullable()->after('data_consent_revoked'); // Data da revogação
            $table->boolean('iva_exempt')->default(false)->after('data_consent_revoked_at'); // Isenção de IVA do cliente
            $table->string('iva_exempt_reason')->nullable()->after('iva_exempt');            // Motivo da isenção de IVA
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn([
                'data_consent',
                'data_consent_at',
                'data_consent_source',
                'data_consent_revoked',
                'data_consent_revoked_at',
                'iva_exempt',
                'iva_exempt_reason',
            ]);
        });
    }
};
