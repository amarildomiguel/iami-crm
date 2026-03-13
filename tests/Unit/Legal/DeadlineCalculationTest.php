<?php

use Carbon\Carbon;
use Webkul\Lead\Repositories\LegalDeadlineRepository;

uses(\Tests\TestCase::class)->in('Unit/Legal');

/**
 * Testes do Cálculo de Prazos Processuais.
 *
 * Verifica o comportamento do cálculo de prazos judiciais
 * considerando dias úteis e feriados angolanos.
 */
describe('Cálculo de Prazos Processuais', function () {

    beforeEach(function () {
        $this->repository = app(LegalDeadlineRepository::class);
    });

    it('calcula prazo de 1 dia útil a partir de uma data normal', function () {
        $start  = Carbon::create(2026, 6, 8); // segunda-feira
        $result = $this->repository->calculateDueDate($start, 1);

        expect($result)->toBeInstanceOf(Carbon::class);
        expect($result->greaterThan($start))->toBeTrue();
    });

    it('calcula prazo de 15 dias úteis (prazo comum para contestação)', function () {
        $start  = Carbon::create(2026, 6, 1);
        $result = $this->repository->calculateDueDate($start, 15);

        // 15 dias úteis = 3 semanas = 22/06/2026
        expect($result->format('Y-m-d'))->toBe('2026-06-22');
    });

    it('calcula prazo de 30 dias úteis', function () {
        $start  = Carbon::create(2026, 6, 1);
        $result = $this->repository->calculateDueDate($start, 30);

        expect($result)->toBeInstanceOf(Carbon::class);
        expect($result->greaterThan($start))->toBeTrue();

        // 30 dias úteis a partir de 01/06 não pode ser antes de 30/06
        expect($result->greaterThanOrEqualTo(Carbon::create(2026, 7, 1)))->toBeTrue();
    });

    it('prazo que atravessa o Dia da Independência (11/11) é calculado correctamente', function () {
        // Começa 2 dias úteis antes do 11/11 (em semana de trabalho)
        $start  = Carbon::create(2026, 11, 9); // segunda-feira
        $result = $this->repository->calculateDueDate($start, 2);

        // 10/11 terça (1 dia útil), 11/11 feriado (saltado), 12/11 quarta (2 dias úteis)
        expect($result->format('Y-m-d'))->toBe('2026-11-12');
    });

    it('prazo que atravessa o Dia do Trabalhador (01/05) é calculado correctamente', function () {
        $start  = Carbon::create(2026, 4, 30); // quinta-feira (30/04)
        $result = $this->repository->calculateDueDate($start, 1);

        // 01/05 é feriado — deve avançar para 04/05 (segunda)
        // 02/05 sábado, 03/05 domingo, 04/05 segunda
        expect($result->format('Y-m-d'))->toBe('2026-05-04');
    });

    it('o resultado de calculateDueDate nunca cai em fim de semana', function () {
        for ($days = 1; $days <= 10; $days++) {
            $start  = Carbon::create(2026, 6, 1);
            $result = $this->repository->calculateDueDate($start, $days);

            expect($result->isWeekend())->toBeFalse(
                "O prazo de {$days} dias úteis caiu num fim de semana: " . $result->format('Y-m-d (l)')
            );
        }
    });

    it('o resultado nunca cai num feriado angolano', function () {
        $feriados = [
            '2026-01-01',
            '2026-01-04',
            '2026-02-04',
            '2026-04-04',
            '2026-05-01',
            '2026-09-17',
            '2026-11-02',
            '2026-11-11',
            '2026-12-25',
        ];

        foreach ($feriados as $feriado) {
            $feriadoDate = Carbon::parse($feriado)->subDay();
            $result      = $this->repository->calculateDueDate($feriadoDate, 1);

            expect($result->format('Y-m-d'))->not->toBe(
                $feriado,
                "O prazo caiu no feriado {$feriado}"
            );
        }
    });

});
