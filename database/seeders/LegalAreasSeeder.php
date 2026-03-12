<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para criar os Tipos de Acção Jurídica (Lead Types).
 *
 * Cria as áreas jurídicas e tipos de acção angolanos
 * para categorizar os processos.
 */
class LegalAreasSeeder extends Seeder
{
    /**
     * Áreas jurídicas de Angola.
     */
    protected array $legalTypes = [
        'Direito Civil',
        'Direito Penal',
        'Direito Laboral',
        'Direito Comercial',
        'Direito da Família',
        'Direito Administrativo',
        'Direito Fiscal e Aduaneiro',
        'Direito Imobiliário',
        'Direito de Propriedade Intelectual',
        'Direito Ambiental',
        'Direito Marítimo',
        'Direito Petrolífero e Mineiro',
        'Direito Internacional',
        'Direito Constitucional',
        'Arbitragem',
        'Contencioso Administrativo',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('A criar áreas jurídicas de Angola...');

        foreach ($this->legalTypes as $type) {
            $exists = DB::table('lead_types')->where('name', $type)->exists();

            if (! $exists) {
                DB::table('lead_types')->insert([
                    'name'       => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("  Área jurídica criada: {$type}");
            }
        }

        $this->command->info('Áreas jurídicas de Angola criadas com sucesso!');
    }
}
