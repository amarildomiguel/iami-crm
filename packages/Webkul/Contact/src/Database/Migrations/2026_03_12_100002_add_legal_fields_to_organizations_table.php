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
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('nif')->nullable()->after('name');                              // NIF da empresa
            $table->string('commercial_registry')->nullable()->after('nif');              // Registo Comercial
            $table->string('legal_form')->nullable()->after('commercial_registry');        // Forma Jurídica (SU, Lda, SA, etc.)
            $table->string('province')->nullable()->after('legal_form');                   // Província
            $table->string('municipality')->nullable()->after('province');                 // Município
            $table->string('sector')->nullable()->after('municipality');                   // Sector de Actividade
            $table->string('representative_name')->nullable()->after('sector');            // Representante Legal
            $table->string('representative_role')->nullable()->after('representative_name'); // Cargo do Representante
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'nif',
                'commercial_registry',
                'legal_form',
                'province',
                'municipality',
                'sector',
                'representative_name',
                'representative_role',
            ]);
        });
    }
};
