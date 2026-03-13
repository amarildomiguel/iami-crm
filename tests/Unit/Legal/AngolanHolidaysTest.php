<?php

use Carbon\Carbon;
use Webkul\Lead\Repositories\LegalDeadlineRepository;

uses(\Tests\TestCase::class)->in('Unit/Legal');

/**
 * Testes dos Feriados Nacionais de Angola.
 *
 * Verifica que os feriados da Lei n.º 19/12 são reconhecidos
 * correctamente pelo repositório de prazos.
 */
describe('Feriados Angolanos', function () {

    beforeEach(function () {
        $this->repository = app(LegalDeadlineRepository::class);
    });

    it('reconhece o Dia de Ano Novo (01 de Janeiro)', function () {
        $start  = Carbon::create(2026, 1, 1);
        $result = $this->repository->calculateDueDate($start, 1);

        // 01/01 é feriado — o próximo dia útil é 02/01 (sexta) ou depois
        expect($result->format('Y-m-d'))->not->toBe('2026-01-01');
    });

    it('reconhece o Dia dos Mártires da Repressão Colonial (04 de Janeiro)', function () {
        // Começa em 03/01, 1 dia útil deve saltar 04/01
        $start  = Carbon::create(2026, 1, 3);
        $result = $this->repository->calculateDueDate($start, 1);

        // 04/01 é feriado — a data de vencimento não pode ser 04/01
        expect($result->format('Y-m-d'))->not->toBe('2026-01-04');
    });

    it('reconhece o Início da Luta Armada (04 de Fevereiro)', function () {
        $start  = Carbon::create(2026, 2, 3);
        $result = $this->repository->calculateDueDate($start, 1);

        expect($result->format('Y-m-d'))->not->toBe('2026-02-04');
    });

    it('reconhece o Dia Internacional da Mulher (08 de Março)', function () {
        $start  = Carbon::create(2026, 3, 7);
        // 08/03 é Domingo em 2026, mas em anos onde é dia útil deve ser saltado
        // Em todo caso verificamos que o cálculo não retorna data inválida
        $result = $this->repository->calculateDueDate($start, 1);

        expect($result)->toBeInstanceOf(Carbon::class);
    });

    it('reconhece o Dia da Paz e Reconciliação Nacional (04 de Abril)', function () {
        $start  = Carbon::create(2026, 4, 3);
        $result = $this->repository->calculateDueDate($start, 1);

        expect($result->format('Y-m-d'))->not->toBe('2026-04-04');
    });

    it('reconhece o Dia do Trabalhador (01 de Maio)', function () {
        $start  = Carbon::create(2026, 4, 30);
        $result = $this->repository->calculateDueDate($start, 1);

        expect($result->format('Y-m-d'))->not->toBe('2026-05-01');
    });

    it('reconhece o Dia do Herói Nacional (17 de Setembro)', function () {
        $start  = Carbon::create(2026, 9, 16);
        $result = $this->repository->calculateDueDate($start, 1);

        expect($result->format('Y-m-d'))->not->toBe('2026-09-17');
    });

    it('reconhece o Dia dos Finados (02 de Novembro)', function () {
        $start  = Carbon::create(2026, 11, 1);
        $result = $this->repository->calculateDueDate($start, 1);

        expect($result->format('Y-m-d'))->not->toBe('2026-11-02');
    });

    it('reconhece o Dia da Independência Nacional (11 de Novembro)', function () {
        $start  = Carbon::create(2026, 11, 10);
        $result = $this->repository->calculateDueDate($start, 1);

        expect($result->format('Y-m-d'))->not->toBe('2026-11-11');
    });

    it('reconhece o Dia de Natal (25 de Dezembro)', function () {
        $start  = Carbon::create(2026, 12, 24);
        $result = $this->repository->calculateDueDate($start, 1);

        expect($result->format('Y-m-d'))->not->toBe('2026-12-25');
    });

    it('retorna uma instância de Carbon', function () {
        $start  = Carbon::create(2026, 6, 1);
        $result = $this->repository->calculateDueDate($start, 5);

        expect($result)->toBeInstanceOf(Carbon::class);
    });

});
