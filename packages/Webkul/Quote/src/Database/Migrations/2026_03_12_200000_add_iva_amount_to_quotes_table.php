<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 6 — Conformidade Legal Angolana
 *
 * Adiciona campo iva_amount à tabela quotes para registo do valor calculado de IVA.
 * O IVA em Angola tem taxa padrão de 14% (Código do IVA, Lei n.º 7/19).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->decimal('iva_amount', 15, 2)->default(0)->after('iva_percentage');   // Valor calculado de IVA (Kz)
            $table->boolean('iva_exempt')->default(false)->after('iva_amount');           // Isenção de IVA
            $table->string('iva_exempt_reason')->nullable()->after('iva_exempt');         // Motivo da isenção
            $table->decimal('sub_total_before_iva', 15, 2)->default(0)->after('iva_exempt_reason'); // Subtotal sem IVA
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn([
                'iva_amount',
                'iva_exempt',
                'iva_exempt_reason',
                'sub_total_before_iva',
            ]);
        });
    }
};
