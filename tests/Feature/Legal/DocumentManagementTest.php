<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Lead\Models\LegalDocument;
use Webkul\Lead\Repositories\LegalDocumentRepository;
use Webkul\User\Models\User;

uses(\Tests\TestCase::class)->in('Feature/Legal');

/**
 * Testes de Gestão de Documentos Jurídicos.
 *
 * Verifica o CRUD completo do módulo de documentos jurídicos,
 * incluindo upload de ficheiros e tipos de documento angolanos.
 */
describe('Gestão de Documentos Jurídicos', function () {

    beforeEach(function () {
        $this->admin = User::find(1);
    });

    it('advogado autenticado pode ver a lista de documentos', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.documents.index'))
            ->assertOk();
    });

    it('advogado autenticado pode ver o formulário de criação de documento', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.documents.create'))
            ->assertOk();
    });

    it('valida campos obrigatórios ao criar documento', function () {
        test()->actingAs($this->admin)
            ->post(route('admin.documents.store'), [])
            ->assertSessionHasErrors(['title', 'document_type', 'user_id']);
    });

    it('os tipos de documento incluem tipos jurídicos angolanos', function () {
        $documentTypes = LegalDocumentRepository::documentTypes();

        $tiposEsperados = [
            'peticao_inicial',
            'contestacao',
            'recurso',
            'procuracao',
            'contrato',
            'parecer',
            'sentenca',
        ];

        foreach ($tiposEsperados as $tipo) {
            expect(array_key_exists($tipo, $documentTypes))->toBeTrue(
                "Tipo de documento '{$tipo}' não está na lista de tipos"
            );
        }
    });

    it('cria documento com upload de ficheiro via AJAX', function () {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('peticao_inicial.pdf', 1024, 'application/pdf');

        $response = test()->actingAs($this->admin)
            ->postJson(route('admin.documents.store'), [
                'title'         => 'Petição Inicial — Processo n.º PROC-2026-0001',
                'document_type' => 'peticao_inicial',
                'user_id'       => $this->admin->id,
                'status'        => 'rascunho',
                'file'          => $file,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data']);
    });

    it('cria documento sem ficheiro via AJAX', function () {
        $response = test()->actingAs($this->admin)
            ->postJson(route('admin.documents.store'), [
                'title'         => 'Parecer Jurídico sobre Direito Laboral',
                'document_type' => 'parecer',
                'user_id'       => $this->admin->id,
                'status'        => 'rascunho',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data']);
    });

    it('retorna 404 ao tentar fazer download de documento sem ficheiro', function () {
        $document = LegalDocument::whereNull('file_path')->orWhere('file_path', '')->first();

        if (! $document) {
            $this->markTestSkipped('Nenhum documento sem ficheiro disponível.');
        }

        test()->actingAs($this->admin)
            ->get(route('admin.documents.download', $document->id))
            ->assertNotFound();
    });

    it('retorna 404 ao tentar ver documento inexistente', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.documents.view', 99999))
            ->assertNotFound();
    });

    it('apaga documento e retorna confirmação JSON', function () {
        $document = LegalDocument::whereNull('file_path')->orWhere('file_path', '')->first();

        if (! $document) {
            $this->markTestSkipped('Nenhum documento disponível para remoção.');
        }

        test()->actingAs($this->admin)
            ->deleteJson(route('admin.documents.delete', $document->id))
            ->assertOk()
            ->assertJsonStructure(['message']);
    });

    it('utilizador não autenticado é redireccionado ao tentar aceder aos documentos', function () {
        test()->get(route('admin.documents.index'))
            ->assertRedirect();
    });

});
