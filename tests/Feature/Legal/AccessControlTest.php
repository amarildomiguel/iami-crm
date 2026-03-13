<?php

use Webkul\User\Models\User;

uses(\Tests\TestCase::class)->in('Feature/Legal');

/**
 * Testes de Controlo de Acesso.
 *
 * Verifica que utilizadores não autenticados não podem aceder
 * aos módulos jurídicos e que utilizadores autenticados podem.
 */
describe('Controlo de Acesso aos Módulos Jurídicos', function () {

    it('utilizador não autenticado é redireccionado do painel de administração', function () {
        test()->get(route('admin.dashboard.index'))
            ->assertRedirect();
    });

    it('utilizador não autenticado é redireccionado da lista de audiências', function () {
        test()->get(route('admin.hearings.index'))
            ->assertRedirect();
    });

    it('utilizador não autenticado é redireccionado da criação de audiências', function () {
        test()->get(route('admin.hearings.create'))
            ->assertRedirect();
    });

    it('utilizador não autenticado é redireccionado da lista de documentos', function () {
        test()->get(route('admin.documents.index'))
            ->assertRedirect();
    });

    it('utilizador não autenticado é redireccionado da criação de documentos', function () {
        test()->get(route('admin.documents.create'))
            ->assertRedirect();
    });

    it('utilizador não autenticado é redireccionado da lista de registos de horas', function () {
        test()->get(route('admin.time-entries.index'))
            ->assertRedirect();
    });

    it('utilizador não autenticado é redireccionado da lista de prazos', function () {
        test()->get(route('admin.deadlines.index'))
            ->assertRedirect();
    });

    it('utilizador não autenticado é redireccionado do calendário de prazos', function () {
        test()->get(route('admin.deadlines.calendar'))
            ->assertRedirect();
    });

    it('utilizador não autenticado é redireccionado da lista de propostas', function () {
        test()->get(route('admin.quotes.index'))
            ->assertRedirect();
    });

    it('utilizador não autenticado é redireccionado da lista de processos', function () {
        test()->get(route('admin.leads.index'))
            ->assertRedirect();
    });

    it('administrador autenticado pode aceder ao painel', function () {
        $admin = User::find(1);

        test()->actingAs($admin)
            ->get(route('admin.dashboard.index'))
            ->assertOk();
    });

    it('administrador autenticado pode aceder a todas as rotas de audiências', function () {
        $admin = User::find(1);

        test()->actingAs($admin)
            ->get(route('admin.hearings.index'))
            ->assertOk();

        test()->actingAs($admin)
            ->get(route('admin.hearings.create'))
            ->assertOk();
    });

    it('administrador autenticado pode aceder a todas as rotas de documentos', function () {
        $admin = User::find(1);

        test()->actingAs($admin)
            ->get(route('admin.documents.index'))
            ->assertOk();

        test()->actingAs($admin)
            ->get(route('admin.documents.create'))
            ->assertOk();
    });

    it('administrador autenticado pode aceder a todas as rotas de prazos', function () {
        $admin = User::find(1);

        test()->actingAs($admin)
            ->get(route('admin.deadlines.index'))
            ->assertOk();

        test()->actingAs($admin)
            ->get(route('admin.deadlines.calendar'))
            ->assertOk();

        test()->actingAs($admin)
            ->get(route('admin.deadlines.create'))
            ->assertOk();
    });

    it('administrador autenticado pode aceder a todas as rotas de registos de horas', function () {
        $admin = User::find(1);

        test()->actingAs($admin)
            ->get(route('admin.time-entries.index'))
            ->assertOk();

        test()->actingAs($admin)
            ->get(route('admin.time-entries.create'))
            ->assertOk();
    });

    it('administrador autenticado pode aceder à lista de propostas de honorários', function () {
        $admin = User::find(1);

        test()->actingAs($admin)
            ->get(route('admin.quotes.index'))
            ->assertOk();
    });

    it('as rotas de módulos jurídicos usam o guard correcto (user)', function () {
        // Verificar que o redirect é para a página de login do admin, não para outra guard
        $response = test()->get(route('admin.hearings.index'));

        $response->assertRedirect();
        expect($response->headers->get('location'))->toContain('admin');
    });

});
