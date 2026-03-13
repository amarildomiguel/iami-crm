<?php

use Webkul\Lead\Models\Lead;
use Webkul\User\Models\User;

uses(\Tests\TestCase::class)->in('Feature/Legal');

/**
 * Testes de Gestão de Processos Jurídicos.
 *
 * Verifica que os campos jurídicos angolanos estão integrados
 * correctamente no módulo de processos (leads).
 */
describe('Gestão de Processos Jurídicos', function () {

    beforeEach(function () {
        $this->admin = User::find(1);
    });

    it('advogado autenticado pode ver a lista de processos', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.leads.index'))
            ->assertOk();
    });

    it('o processo (lead) tem os campos jurídicos angolanos', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        $fillable = $lead->getFillable();

        expect(in_array('case_number', $fillable))->toBeTrue();
        expect(in_array('court', $fillable))->toBeTrue();
        expect(in_array('legal_area', $fillable))->toBeTrue();
        expect(in_array('province', $fillable))->toBeTrue();
        expect(in_array('urgency_level', $fillable))->toBeTrue();
    });

    it('processo tem relação com audiências (hearings)', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        expect(method_exists($lead, 'hearings'))->toBeTrue();
    });

    it('processo tem relação com documentos jurídicos', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        expect(method_exists($lead, 'legalDocuments'))->toBeTrue();
    });

    it('processo tem relação com registos de horas', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        expect(method_exists($lead, 'timeEntries'))->toBeTrue();
    });

    it('processo tem relação com prazos processuais', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        expect(method_exists($lead, 'legalDeadlines'))->toBeTrue();
    });

    it('o modelo Lead tem os casts correctos para campos jurídicos de data', function () {
        $lead  = new Lead();
        $casts = $lead->getCasts();

        expect(array_key_exists('filing_date', $casts))->toBeTrue();
        expect(array_key_exists('next_hearing_date', $casts))->toBeTrue();
    });

    it('processo pode ser criado com campos jurídicos via API', function () {
        $pipeline = \Webkul\Lead\Models\LeadPipeline::first();
        $stage    = \Webkul\Lead\Models\LeadPipelineStage::first();

        if (! $pipeline || ! $stage) {
            $this->markTestSkipped('Pipeline ou stage não disponível.');
        }

        $response = test()->actingAs($this->admin)
            ->postJson(route('admin.leads.store'), [
                'title'               => 'Processo de Teste Jurídico',
                'description'         => 'Processo criado via teste automatizado',
                'lead_pipeline_id'    => $pipeline->id,
                'lead_pipeline_stage_id' => $stage->id,
                'case_number'         => 'PROC-2026-TEST-' . rand(1000, 9999),
                'court'               => 'Tribunal Provincial de Luanda',
                'legal_area'          => 'Direito Civil',
                'province'            => 'Luanda',
                'urgency_level'       => 'normal',
                'status'              => 0,
                'lead_value'          => 500000,
            ]);

        $response->assertStatus(200);
    });

    it('utilizador não autenticado é redireccionado ao tentar aceder aos processos', function () {
        test()->get(route('admin.leads.index'))
            ->assertRedirect();
    });

});
