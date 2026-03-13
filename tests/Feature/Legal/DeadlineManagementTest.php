<?php

use Carbon\Carbon;
use Webkul\Lead\Models\Lead;
use Webkul\Lead\Models\LegalDeadline;
use Webkul\Lead\Repositories\LegalDeadlineRepository;
use Webkul\User\Models\User;

uses(\Tests\TestCase::class)->in('Feature/Legal');

/**
 * Testes de Gestão de Prazos Processuais.
 *
 * Verifica o módulo de prazos judiciais com cálculo em dias úteis angolanos.
 */
describe('Gestão de Prazos Processuais', function () {

    beforeEach(function () {
        $this->admin = User::find(1);
    });

    it('advogado autenticado pode ver a lista de prazos', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.deadlines.index'))
            ->assertOk();
    });

    it('advogado autenticado pode ver o formulário de criação de prazo', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.deadlines.create'))
            ->assertOk();
    });

    it('advogado autenticado pode ver o calendário de prazos', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.deadlines.calendar'))
            ->assertOk();
    });

    it('valida campos obrigatórios ao criar prazo', function () {
        test()->actingAs($this->admin)
            ->post(route('admin.deadlines.store'), [])
            ->assertSessionHasErrors(['lead_id', 'title', 'start_date', 'due_date', 'user_id']);
    });

    it('valida que due_date é posterior a start_date', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        test()->actingAs($this->admin)
            ->post(route('admin.deadlines.store'), [
                'lead_id'    => $lead->id,
                'title'      => 'Prazo de Contestação',
                'start_date' => '2026-06-15',
                'due_date'   => '2026-06-10', // data anterior à de início
                'user_id'    => $this->admin->id,
            ])
            ->assertSessionHasErrors(['due_date']);
    });

    it('cria prazo processual válido via AJAX', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível.');
        }

        $response = test()->actingAs($this->admin)
            ->postJson(route('admin.deadlines.store'), [
                'lead_id'       => $lead->id,
                'title'         => 'Prazo de Contestação',
                'description'   => 'Prazo legal de 20 dias úteis para contestar',
                'start_date'    => '2026-06-01',
                'due_date'      => '2026-06-29',
                'business_days' => 20,
                'status'        => 'pendente',
                'priority'      => 'alta',
                'court_deadline' => true,
                'user_id'       => $this->admin->id,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data']);
    });

    it('calcula data de vencimento a partir de dias úteis via AJAX', function () {
        $response = test()->actingAs($this->admin)
            ->postJson(route('admin.deadlines.calculate_due_date'), [
                'start_date'    => '2026-06-01',
                'business_days' => 15,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['due_date']);

        $dueDate = $response->json('due_date');

        expect($dueDate)->not->toBeNull();
        expect(Carbon::parse($dueDate)->greaterThan(Carbon::parse('2026-06-01')))->toBeTrue();
    });

    it('valida que business_days deve ser pelo menos 1 no cálculo', function () {
        test()->actingAs($this->admin)
            ->postJson(route('admin.deadlines.calculate_due_date'), [
                'start_date'    => '2026-06-01',
                'business_days' => 0,
            ])
            ->assertStatus(422);
    });

    it('o cálculo de dias úteis não retorna feriado angolano como data de vencimento', function () {
        $response = test()->actingAs($this->admin)
            ->postJson(route('admin.deadlines.calculate_due_date'), [
                'start_date'    => '2026-11-09', // segunda-feira antes do 11/11
                'business_days' => 2,
            ]);

        $response->assertStatus(200);
        $dueDate = $response->json('due_date');

        // O resultado não deve ser 11/11 (Dia da Independência)
        expect($dueDate)->not->toBe('2026-11-11');
    });

    it('LegalDeadlineRepository retorna prazos a vencer em breve', function () {
        $repository = app(LegalDeadlineRepository::class);
        $result     = $repository->getExpiringSoon(30);

        expect($result)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    });

    it('LegalDeadlineRepository retorna prazos vencidos', function () {
        $repository = app(LegalDeadlineRepository::class);
        $result     = $repository->getOverdue();

        expect($result)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    });

    it('apaga prazo e retorna confirmação JSON', function () {
        $deadline = LegalDeadline::first();

        if (! $deadline) {
            $this->markTestSkipped('Nenhum prazo disponível para remoção.');
        }

        test()->actingAs($this->admin)
            ->deleteJson(route('admin.deadlines.delete', $deadline->id))
            ->assertOk()
            ->assertJsonStructure(['message']);
    });

    it('utilizador não autenticado é redireccionado ao tentar aceder aos prazos', function () {
        test()->get(route('admin.deadlines.index'))
            ->assertRedirect();
    });

});
