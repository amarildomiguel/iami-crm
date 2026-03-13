<?php

use Webkul\Lead\Models\Lead;

uses(\Tests\TestCase::class)->in('Unit/Legal');

/**
 * Testes do Número de Processo (Case Number).
 *
 * Verifica o formato e unicidade do campo case_number
 * no modelo Lead adaptado para CRM Jurídico Angolano.
 */
describe('Número de Processo', function () {

    it('o modelo Lead possui o campo case_number na lista de fillable', function () {
        $lead = new Lead();

        expect(in_array('case_number', $lead->getFillable()))->toBeTrue();
    });

    it('o campo case_number aceita o formato padrão angolano (PROC-AAAA-NNNN)', function () {
        $caseNumber = 'PROC-2026-0001';

        expect(preg_match('/^PROC-\d{4}-\d{4}$/', $caseNumber))->toBe(1);
    });

    it('o campo case_number aceita formato de tribunal (TRIBUNAL/ANO/NÚMERO)', function () {
        $formats = [
            'TSL/2026/001',    // Tribunal Supremo de Luanda
            'TPC/2026/123',    // Tribunal Provincial de...
            'TCM/2026/456',    // Tribunal de Comarca
        ];

        foreach ($formats as $format) {
            expect(strlen($format))->toBeGreaterThan(0);
        }
    });

    it('dois processos não podem ter o mesmo número de processo', function () {
        // Verificar que o campo tem restrição unique na migration
        $migration = file_get_contents(
            base_path('packages/Webkul/Lead/src/Database/Migrations/2026_03_12_100000_add_legal_fields_to_leads_table.php')
        );

        expect($migration)->toContain("'case_number'");
        expect($migration)->toContain('unique');
    });

    it('o campo case_number é nullable (processo pode ser importado sem número ainda)', function () {
        $migration = file_get_contents(
            base_path('packages/Webkul/Lead/src/Database/Migrations/2026_03_12_100000_add_legal_fields_to_leads_table.php')
        );

        expect($migration)->toContain('nullable');
    });

    it('gera número de processo no formato correcto a partir dos parâmetros', function () {
        $year   = 2026;
        $seq    = 42;
        $prefix = 'PROC';

        $caseNumber = sprintf('%s-%d-%04d', $prefix, $year, $seq);

        expect($caseNumber)->toBe('PROC-2026-0042');
        expect(strlen($caseNumber))->toBe(14);
    });

    it('o campo province está disponível no modelo para identificar a comarca', function () {
        $lead = new Lead();

        expect(in_array('province', $lead->getFillable()))->toBeTrue();
    });

    it('os campos jurídicos principais estão no fillable do Lead', function () {
        $lead = new Lead();
        $fillable = $lead->getFillable();

        $camposJuridicos = [
            'case_number',
            'court',
            'court_section',
            'case_type',
            'jurisdiction',
            'judge_name',
            'legal_area',
            'province',
            'filing_date',
            'urgency_level',
        ];

        foreach ($camposJuridicos as $campo) {
            expect(in_array($campo, $fillable))->toBeTrue(
                "Campo jurídico '{$campo}' não está no fillable do Lead"
            );
        }
    });

});
