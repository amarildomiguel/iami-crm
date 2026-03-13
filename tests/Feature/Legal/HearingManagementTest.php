<?php

use Webkul\Lead\Models\Hearing;
use Webkul\Lead\Models\Lead;
use Webkul\User\Models\User;

uses(\Tests\TestCase::class)->in('Feature/Legal');

/**
 * Testes de Gestão de Audiências.
 *
 * Verifica o CRUD completo do módulo de audiências judiciais.
 */
describe('Gestão de Audiências', function () {

    beforeEach(function () {
        $this->admin = User::find(1);
    });

    it('advogado autenticado pode ver a lista de audiências', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.hearings.index'))
            ->assertOk();
    });

    it('advogado autenticado pode ver o formulário de criação de audiência', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.hearings.create'))
            ->assertOk();
    });

    it('valida campos obrigatórios ao criar audiência', function () {
        test()->actingAs($this->admin)
            ->post(route('admin.hearings.store'), [])
            ->assertSessionHasErrors(['lead_id', 'hearing_type', 'scheduled_at', 'court', 'user_id']);
    });

    it('valida que lead_id deve existir na base de dados', function () {
        test()->actingAs($this->admin)
            ->post(route('admin.hearings.store'), [
                'lead_id'      => 99999,
                'hearing_type' => 'Julgamento',
                'scheduled_at' => now()->addDays(7)->format('Y-m-d H:i:s'),
                'court'        => 'Tribunal Provincial de Luanda',
                'user_id'      => $this->admin->id,
            ])
            ->assertSessionHasErrors(['lead_id']);
    });

    it('retorna JSON ao criar audiência via AJAX', function () {
        $lead = Lead::first();

        if (! $lead) {
            $this->markTestSkipped('Nenhum processo disponível para o teste.');
        }

        $response = test()->actingAs($this->admin)
            ->postJson(route('admin.hearings.store'), [
                'lead_id'      => $lead->id,
                'hearing_type' => 'Julgamento',
                'scheduled_at' => now()->addDays(7)->format('Y-m-d H:i:s'),
                'court'        => 'Tribunal Provincial de Luanda',
                'user_id'      => $this->admin->id,
                'status'       => 'agendada',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data']);
    });

    it('retorna JSON ao actualizar audiência via AJAX', function () {
        $hearing = Hearing::first();

        if (! $hearing) {
            $this->markTestSkipped('Nenhuma audiência disponível para actualização.');
        }

        $response = test()->actingAs($this->admin)
            ->putJson(route('admin.hearings.update', $hearing->id), [
                'hearing_type' => 'Instrução',
                'scheduled_at' => now()->addDays(14)->format('Y-m-d H:i:s'),
                'court'        => 'Tribunal Supremo',
                'user_id'      => $this->admin->id,
                'status'       => 'agendada',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data']);
    });

    it('retorna 404 ao tentar ver audiência inexistente', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.hearings.view', 99999))
            ->assertNotFound();
    });

    it('retorna JSON ao apagar audiência via AJAX', function () {
        $hearing = Hearing::first();

        if (! $hearing) {
            $this->markTestSkipped('Nenhuma audiência disponível para remoção.');
        }

        test()->actingAs($this->admin)
            ->deleteJson(route('admin.hearings.delete', $hearing->id))
            ->assertOk()
            ->assertJsonStructure(['message']);
    });

    it('utilizador não autenticado é redireccionado ao tentar aceder às audiências', function () {
        test()->get(route('admin.hearings.index'))
            ->assertRedirect();
    });

});
