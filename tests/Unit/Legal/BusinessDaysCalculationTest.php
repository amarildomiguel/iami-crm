<?php

use Carbon\Carbon;
use Webkul\Lead\Repositories\LegalDeadlineRepository;

uses(\Tests\TestCase::class)->in('Unit/Legal');

/**
 * Testes do Cálculo de Dias Úteis Angolanos.
 *
 * Verifica que o cálculo de dias úteis considera correctamente
 * fins de semana e feriados nacionais de Angola.
 */
describe('Cálculo de Dias Úteis', function () {

    beforeEach(function () {
        $this->repository = app(LegalDeadlineRepository::class);
    });

    it('calcula 1 dia útil a partir de uma segunda-feira normal', function () {
        // 08/06/2026 é segunda-feira
        $start  = Carbon::create(2026, 6, 8); // segunda
        $result = $this->repository->calculateDueDate($start, 1);

        // O próximo dia útil deve ser terça-feira 09/06
        expect($result->format('Y-m-d'))->toBe('2026-06-09');
    });

    it('calcula 5 dias úteis a partir de segunda-feira', function () {
        // 08/06/2026 é segunda-feira
        $start  = Carbon::create(2026, 6, 8);
        $result = $this->repository->calculateDueDate($start, 5);

        // 5 dias úteis: ter, qua, qui, sex, segunda-feira seguinte
        expect($result->format('Y-m-d'))->toBe('2026-06-15');
    });

    it('salta o fim de semana ao calcular dias úteis', function () {
        // 12/06/2026 é sexta-feira
        $start  = Carbon::create(2026, 6, 12);
        $result = $this->repository->calculateDueDate($start, 1);

        // 13/06 sábado, 14/06 domingo — deve retornar segunda 15/06
        expect($result->format('Y-m-d'))->toBe('2026-06-15');
    });

    it('calcula correctamente 10 dias úteis', function () {
        // 01/06/2026 segunda-feira
        $start  = Carbon::create(2026, 6, 1);
        $result = $this->repository->calculateDueDate($start, 10);

        // 10 dias úteis = 2 semanas de dias úteis = 15/06 (segunda)
        expect($result->format('Y-m-d'))->toBe('2026-06-15');
    });

    it('salta feriado angolano ao calcular dias úteis', function () {
        // 03/04/2026 quinta-feira — próximo dia útil deveria ser 06/04 (pois 04/04 é feriado e 05/04 é domingo)
        $start  = Carbon::create(2026, 4, 3);
        $result = $this->repository->calculateDueDate($start, 1);

        expect($result->format('Y-m-d'))->not->toBe('2026-04-04'); // 04/04 é feriado
    });

    it('calcula prazo em anos diferentes correctamente', function () {
        $start2025 = Carbon::create(2025, 12, 31);
        $result    = $this->repository->calculateDueDate($start2025, 1);

        // 01/01 é feriado, então deve avançar para 02/01/2026
        expect($result->year)->toBe(2026);
        expect($result->format('Y-m-d'))->toBe('2026-01-02');
    });

    it('não conta o sábado como dia útil', function () {
        // 13/06/2026 é sábado
        $saturday = Carbon::create(2026, 6, 13);
        $result   = $this->repository->calculateDueDate($saturday, 1);

        // Devo avançar para a próxima segunda-feira
        expect($result->isWeekend())->toBeFalse();
    });

    it('não conta o domingo como dia útil', function () {
        // 14/06/2026 é domingo
        $sunday = Carbon::create(2026, 6, 14);
        $result = $this->repository->calculateDueDate($sunday, 1);

        expect($result->isWeekend())->toBeFalse();
    });

    it('retorna data posterior à data de início', function () {
        $start  = Carbon::create(2026, 6, 10);
        $result = $this->repository->calculateDueDate($start, 3);

        expect($result->greaterThan($start))->toBeTrue();
    });

});
