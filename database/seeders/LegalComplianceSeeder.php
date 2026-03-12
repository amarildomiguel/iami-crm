<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * LegalComplianceSeeder — Fase 6: Conformidade Legal Angolana
 *
 * Configura as definições de conformidade legal para o CRM Jurídico Angola.
 *
 * Legislação aplicável:
 * - Lei n.º 22/11, de 17 de Junho — Lei da Protecção de Dados Pessoais de Angola
 * - Código do IVA (Lei n.º 7/19) — taxa de 14%
 * - Código do Imposto Industrial — obrigações fiscais
 * - Estatuto da Ordem dos Advogados de Angola (OAA)
 * - Código de Processo Civil Angolano
 */
class LegalComplianceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedCoreConfig();
        $this->command->info('Conformidade legal angolana configurada.');
    }

    /**
     * Configura os parâmetros de conformidade legal nas definições do sistema.
     *
     * Os parâmetros são inseridos na tabela core_config para persistência
     * e disponibilização através do helper core()->getConfigData().
     */
    protected function seedCoreConfig(): void
    {
        $configs = [
            // --- IVA Angola (Lei n.º 7/19 — Código do IVA) ---
            [
                'code'  => 'legal.iva.rate',
                'value' => '14',
            ],
            [
                'code'  => 'legal.iva.enabled',
                'value' => '1',
            ],
            [
                'code'  => 'legal.iva.exemption_allowed',
                'value' => '1',
            ],

            // --- Protecção de Dados Pessoais (Lei n.º 22/11) ---
            [
                'code'  => 'legal.data_protection.enabled',
                'value' => '1',
            ],
            [
                'code'  => 'legal.data_protection.require_consent',
                'value' => '1',
            ],
            [
                'code'  => 'legal.data_protection.retention_years',
                'value' => '10',    // Período de conservação de documentos jurídicos
            ],
            [
                'code'  => 'legal.data_protection.controller_name',
                'value' => 'Escritório de Advocacia',
            ],
            [
                'code'  => 'legal.data_protection.controller_nif',
                'value' => '',
            ],
            [
                'code'  => 'legal.data_protection.dpo_email',
                'value' => '',      // Responsável pela Protecção de Dados
            ],

            // --- Regras OAA (Estatuto da Ordem dos Advogados de Angola) ---
            [
                'code'  => 'legal.oaa.sigilo_profissional',
                'value' => '1',     // Sigilo profissional obrigatório
            ],
            [
                'code'  => 'legal.oaa.intern_supervision_required',
                'value' => '1',     // Supervisão de estagiários obrigatória
            ],
            [
                'code'  => 'legal.oaa.time_tracking_required',
                'value' => '1',     // Registo de horas para justificação de honorários
            ],

            // --- Campos obrigatórios por lei ---
            [
                'code'  => 'legal.required_fields.nif_for_billing',
                'value' => '1',     // NIF obrigatório para emissão de facturas
            ],
            [
                'code'  => 'legal.required_fields.nif_threshold_aoa',
                'value' => '0',     // Valor mínimo (Kz) para exigir NIF (0 = sempre)
            ],

            // --- Configurações de auditoria ---
            [
                'code'  => 'legal.audit.log_data_access',
                'value' => '1',     // Registo de acessos a dados pessoais
            ],
            [
                'code'  => 'legal.audit.log_retention_days',
                'value' => '365',   // Retenção de logs de auditoria (1 ano)
            ],
        ];

        foreach ($configs as $config) {
            $existing = DB::table('core_config')->where('code', $config['code'])->first();

            if (! $existing) {
                DB::table('core_config')->insert(array_merge($config, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        $this->command->info('Parâmetros de conformidade legal inseridos:');
        $this->command->info('  IVA: 14% (Lei n.º 7/19)');
        $this->command->info('  Protecção de Dados: Activa (Lei n.º 22/11)');
        $this->command->info('  Sigilo Profissional OAA: Activo');
        $this->command->info('  NIF obrigatório para facturação: Sim');
    }
}
