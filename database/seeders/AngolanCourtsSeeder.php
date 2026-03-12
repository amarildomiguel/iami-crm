<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para criar os Tribunais de Angola como Lead Sources.
 *
 * Cria os principais tribunais angolanos para serem usados
 * nos campos de tribunal dos processos jurídicos.
 */
class AngolanCourtsSeeder extends Seeder
{
    /**
     * Principais tribunais de Angola.
     */
    protected array $courts = [
        // Tribunais Superiores
        'Tribunal Supremo',
        'Tribunal Constitucional',
        'Tribunal de Contas',
        'Tribunal Supremo Militar',
        // Tribunal Administrativo
        'Tribunal Administrativo',
        // Tribunal do Trabalho (Nacional)
        'Tribunal Superior do Trabalho',
        // Tribunais Provinciais
        'Tribunal Provincial de Luanda',
        'Tribunal Provincial de Benguela',
        'Tribunal Provincial de Huambo',
        'Tribunal Provincial da Huíla',
        'Tribunal Provincial de Malanje',
        'Tribunal Provincial do Uíge',
        'Tribunal Provincial do Zaire',
        'Tribunal Provincial de Cabinda',
        'Tribunal Provincial do Bié',
        'Tribunal Provincial do Moxico',
        'Tribunal Provincial do Cuando Cubango',
        'Tribunal Provincial do Cunene',
        'Tribunal Provincial do Namibe',
        'Tribunal Provincial do Cuanza Sul',
        'Tribunal Provincial do Cuanza Norte',
        'Tribunal Provincial de Lunda Norte',
        'Tribunal Provincial de Lunda Sul',
        'Tribunal Provincial do Bengo',
        // Tribunais de Comarca de Luanda
        'Tribunal de Comarca de Belas',
        'Tribunal de Comarca do Cacuaco',
        'Tribunal de Comarca de Cazenga',
        'Tribunal de Comarca do Kilamba Kiaxi',
        'Tribunal de Comarca do Maianga',
        'Tribunal de Comarca do Município do Rangel',
        'Tribunal de Comarca de Viana',
        // Arbitragem
        'Centro de Arbitragem, Conciliação e Mediação (CACM)',
        'Câmara de Comércio e Indústria de Angola (CCIA)',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('A criar tribunais de Angola...');

        foreach ($this->courts as $sort => $court) {
            $exists = DB::table('lead_sources')->where('name', $court)->exists();

            if (! $exists) {
                DB::table('lead_sources')->insert([
                    'name'       => $court,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("  Tribunal criado: {$court}");
            }
        }

        $this->command->info('Tribunais de Angola criados com sucesso!');
    }
}
