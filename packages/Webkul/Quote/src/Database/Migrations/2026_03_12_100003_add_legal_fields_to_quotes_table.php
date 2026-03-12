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
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('payment_terms')->nullable()->after('grand_total');             // Condições de Pagamento
            $table->string('billing_type')->nullable()->after('payment_terms');            // Tipo (Avença, Por Hora, Por Processo)
            $table->decimal('hourly_rate', 15, 2)->nullable()->after('billing_type');     // Taxa Horária (Kz)
            $table->decimal('retainer_fee', 15, 2)->nullable()->after('hourly_rate');     // Valor da Avença (Kz)
            $table->string('iva_regime')->nullable()->after('retainer_fee');              // Regime de IVA
            $table->decimal('iva_percentage', 5, 2)->default(14.00)->after('iva_regime'); // % IVA (14% em Angola)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn([
                'payment_terms',
                'billing_type',
                'hourly_rate',
                'retainer_fee',
                'iva_regime',
                'iva_percentage',
            ]);
        });
    }
};
