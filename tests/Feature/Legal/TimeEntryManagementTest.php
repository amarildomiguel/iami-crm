<?php

use Webkul\Lead\Models\Lead;
use Webkul\Lead\Models\TimeEntry;
use Webkul\Lead\Repositories\TimeEntryRepository;
use Webkul\User\Models\User;

uses(\Tests\TestCase::class)->in('Feature/Legal');

/**
 * Testes de Gestão de Registo de Horas.
 *
 * Verifica o módulo de registo de tempo para cálculo de honorários.
 */
describe('Registo de Horas (Time Entries)', function () {

    beforeEach(function () {
        $this->admin = User::find(1);
    });

    it('advogado autenticado pode ver a lista de registos de horas', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.time-entries.index'))
            ->assertOk();
    });

    it('advogado autenticado pode ver o formulário de criação de registo de horas', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.time-entries.create'))
            ->assertOk();
    });

    it('valida campos obrigatórios ao criar registo de horas', function () {
        test()->actingAs($this->admin)
            ->post(route('admin.time-entries.store'), [])
            ->assertSessionHasErrors(['lead_id', 'user_id', 'entry_date', 'hours', 'description', 'activity_type']);
    });

    it('valida que horas mínimas são 0.25 (15 minutos)', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        test()->actingAs($this->admin)
            ->post(route('admin.time-entries.store'), [
                'lead_id'       => $lead->id,
                'user_id'       => $this->admin->id,
                'entry_date'    => now()->format('Y-m-d'),
                'hours'         => 0.1, // menos que o mínimo
                'description'   => 'Consulta breve',
                'activity_type' => 'reuniao',
            ])
            ->assertSessionHasErrors(['hours']);
    });

    it('valida que horas máximas são 24 por entrada', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        test()->actingAs($this->admin)
            ->post(route('admin.time-entries.store'), [
                'lead_id'       => $lead->id,
                'user_id'       => $this->admin->id,
                'entry_date'    => now()->format('Y-m-d'),
                'hours'         => 25, // mais que o máximo
                'description'   => 'Trabalho excessivo',
                'activity_type' => 'outro',
            ])
            ->assertSessionHasErrors(['hours']);
    });

    it('cria registo de horas válido via AJAX', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        $response = test()->actingAs($this->admin)
            ->postJson(route('admin.time-entries.store'), [
                'lead_id'       => $lead->id,
                'user_id'       => $this->admin->id,
                'entry_date'    => now()->format('Y-m-d'),
                'hours'         => 2.5,
                'description'   => 'Reunião com cliente sobre o processo',
                'activity_type' => 'reuniao',
                'hourly_rate'   => 15000.0,
                'billable'      => true,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data']);
    });

    it('calcula total_amount automaticamente ao criar entrada com taxa horária', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        $response = test()->actingAs($this->admin)
            ->postJson(route('admin.time-entries.store'), [
                'lead_id'       => $lead->id,
                'user_id'       => $this->admin->id,
                'entry_date'    => now()->format('Y-m-d'),
                'hours'         => 3.0,
                'description'   => 'Audiência no tribunal',
                'activity_type' => 'audiencia',
                'hourly_rate'   => 20000.0,
                'billable'      => true,
            ]);

        $response->assertStatus(200);
        $data = $response->json('data');

        // 3h × 20.000 Kz = 60.000 Kz
        expect((float) $data['total_amount'])->toBe(60000.0);
    });

    it('marca entradas de horas como facturadas', function () {
        $entry = TimeEntry::where('billable', true)->where('billed', false)->first();

        if (! $entry) {
            $this->markTestSkipped('Nenhuma entrada factúrável disponível.');
        }

        test()->actingAs($this->admin)
            ->postJson(route('admin.time-entries.mark_billed'), ['ids' => [$entry->id]])
            ->assertOk()
            ->assertJsonStructure(['message']);
    });

    it('retorna erro ao tentar marcar como facturado sem IDs', function () {
        test()->actingAs($this->admin)
            ->postJson(route('admin.time-entries.mark_billed'), ['ids' => []])
            ->assertStatus(422);
    });

    it('apaga registo de horas e retorna confirmação JSON', function () {
        $entry = TimeEntry::first();

        if (! $entry) {
            $this->markTestSkipped('Nenhum registo de horas disponível.');
        }

        test()->actingAs($this->admin)
            ->deleteJson(route('admin.time-entries.delete', $entry->id))
            ->assertOk()
            ->assertJsonStructure(['message']);
    });

    it('TimeEntryRepository calcula total de horas de um processo', function () {
        $repository = app(TimeEntryRepository::class);
        $lead       = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        $total = $repository->getTotalHours($lead->id);

        expect($total)->toBeFloat();
        expect($total)->toBeGreaterThanOrEqual(0);
    });

    it('utilizador não autenticado é redireccionado ao tentar aceder aos registos', function () {
        test()->get(route('admin.time-entries.index'))
            ->assertRedirect();
    });

});
