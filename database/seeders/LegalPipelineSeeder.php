<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para criar o Pipeline Processual Jurídico Angolano.
 *
 * Cria o fluxo de fases processuais padrão para escritórios
 * de advocacia e departamentos jurídicos em Angola.
 */
class LegalPipelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verifica se já existe um pipeline jurídico
        $existingPipeline = DB::table('lead_pipelines')
            ->where('name', 'Fluxo Processual Jurídico')
            ->first();

        if ($existingPipeline) {
            $this->command->info('Pipeline jurídico já existe. A ignorar...');

            return;
        }

        // Cria o pipeline processual jurídico angolano
        $pipelineId = DB::table('lead_pipelines')->insertGetId([
            'name'        => 'Fluxo Processual Jurídico',
            'is_default'  => 1,
            'rotten_days' => 30,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $this->command->info("Pipeline 'Fluxo Processual Jurídico' criado com ID: {$pipelineId}");

        // Fases processuais conforme Plano de Implementação (Fase 4.1)
        $stages = [
            [
                'code'        => 'consulta_inicial',
                'name'        => 'Consulta Inicial',
                'probability' => 10,
                'sort_order'  => 1,
            ],
            [
                'code'        => 'analise_caso',
                'name'        => 'Análise do Caso',
                'probability' => 20,
                'sort_order'  => 2,
            ],
            [
                'code'        => 'proposta_honorarios',
                'name'        => 'Proposta de Honorários',
                'probability' => 30,
                'sort_order'  => 3,
            ],
            [
                'code'        => 'processo_em_curso',
                'name'        => 'Processo em Curso',
                'probability' => 50,
                'sort_order'  => 4,
            ],
            [
                'code'        => 'fase_instrucao',
                'name'        => 'Fase de Instrução',
                'probability' => 60,
                'sort_order'  => 5,
            ],
            [
                'code'        => 'audiencia_julgamento',
                'name'        => 'Audiência de Julgamento',
                'probability' => 80,
                'sort_order'  => 6,
            ],
            [
                'code'        => 'aguardar_sentenca',
                'name'        => 'Aguardar Sentença',
                'probability' => 90,
                'sort_order'  => 7,
            ],
            [
                'code'        => 'recurso',
                'name'        => 'Recurso',
                'probability' => 70,
                'sort_order'  => 8,
            ],
            [
                'code'        => 'encerrado_ganho',
                'name'        => 'Encerrado — Ganho',
                'probability' => 100,
                'sort_order'  => 9,
            ],
            [
                'code'        => 'encerrado_perdido',
                'name'        => 'Encerrado — Perdido',
                'probability' => 0,
                'sort_order'  => 10,
            ],
        ];

        foreach ($stages as $stage) {
            DB::table('lead_pipeline_stages')->insert([
                'code'               => $stage['code'],
                'name'               => $stage['name'],
                'probability'        => $stage['probability'],
                'sort_order'         => $stage['sort_order'],
                'lead_pipeline_id'   => $pipelineId,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            $this->command->info("  Fase criada: {$stage['name']} ({$stage['probability']}%)");
        }

        $this->command->info('Pipeline jurídico angolano criado com sucesso!');
    }
}
