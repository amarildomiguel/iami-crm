<?php

use Webkul\Quote\Repositories\QuoteRepository;

uses(\Tests\TestCase::class)->in('Unit/Legal');

/**
 * Testes do Cálculo de IVA Angolano.
 *
 * Verifica o cálculo do Imposto sobre o Valor Acrescentado (IVA)
 * à taxa de 14% conforme o Código do IVA Angolano (Lei n.º 7/19).
 */
describe('Cálculo de IVA Angola (14%)', function () {

    beforeEach(function () {
        $this->repository = app(QuoteRepository::class);
    });

    it('calcula IVA de 14% sobre valor base', function () {
        // Aceder ao método protegido via reflexão
        $method = new ReflectionMethod(QuoteRepository::class, 'calculateIva');
        $method->setAccessible(true);

        $data = [
            'sub_total'        => 100000.00,
            'discount_amount'  => 0.0,
            'adjustment_amount' => 0.0,
            'iva_percentage'   => 14.0,
            'iva_exempt'       => false,
        ];

        $result = $method->invoke($this->repository, $data);

        expect($result['iva_amount'])->toBe(14000.0);
        expect($result['grand_total'])->toBe(114000.0);
    });

    it('calcula IVA com desconto aplicado primeiro', function () {
        $method = new ReflectionMethod(QuoteRepository::class, 'calculateIva');
        $method->setAccessible(true);

        $data = [
            'sub_total'         => 100000.00,
            'discount_amount'   => 10000.00, // desconto de 10%
            'adjustment_amount' => 0.0,
            'iva_percentage'    => 14.0,
            'iva_exempt'        => false,
        ];

        $result = $method->invoke($this->repository, $data);

        // Base tributável = 100000 - 10000 = 90000
        // IVA = 90000 * 14% = 12600
        expect($result['sub_total_before_iva'])->toBe(90000.0);
        expect($result['iva_amount'])->toBe(12600.0);
        expect($result['grand_total'])->toBe(102600.0);
    });

    it('aplica isenção de IVA quando iva_exempt é verdadeiro', function () {
        $method = new ReflectionMethod(QuoteRepository::class, 'calculateIva');
        $method->setAccessible(true);

        $data = [
            'sub_total'         => 100000.00,
            'discount_amount'   => 0.0,
            'adjustment_amount' => 0.0,
            'iva_percentage'    => 14.0,
            'iva_exempt'        => true,
        ];

        $result = $method->invoke($this->repository, $data);

        expect($result['iva_amount'])->toBe(0.0);
        expect($result['grand_total'])->toBe(100000.0);
    });

    it('usa 14% como taxa padrão quando iva_percentage não é fornecido', function () {
        $method = new ReflectionMethod(QuoteRepository::class, 'calculateIva');
        $method->setAccessible(true);

        $data = [
            'sub_total'         => 50000.00,
            'discount_amount'   => 0.0,
            'adjustment_amount' => 0.0,
            'iva_exempt'        => false,
        ];

        $result = $method->invoke($this->repository, $data);

        expect($result['iva_percentage'])->toBe(14.0);
        expect($result['iva_amount'])->toBe(7000.0);
    });

    it('inclui ajuste no grand_total', function () {
        $method = new ReflectionMethod(QuoteRepository::class, 'calculateIva');
        $method->setAccessible(true);

        $data = [
            'sub_total'         => 100000.00,
            'discount_amount'   => 0.0,
            'adjustment_amount' => 5000.0,
            'iva_percentage'    => 14.0,
            'iva_exempt'        => false,
        ];

        $result = $method->invoke($this->repository, $data);

        // grand_total = 100000 + 14000 + 5000 = 119000
        expect($result['grand_total'])->toBe(119000.0);
    });

    it('calcula IVA com taxa zero como isenção', function () {
        $method = new ReflectionMethod(QuoteRepository::class, 'calculateIva');
        $method->setAccessible(true);

        $data = [
            'sub_total'         => 100000.00,
            'discount_amount'   => 0.0,
            'adjustment_amount' => 0.0,
            'iva_percentage'    => 0,
            'iva_exempt'        => false,
        ];

        $result = $method->invoke($this->repository, $data);

        expect($result['iva_amount'])->toBe(0.0);
    });

    it('arredonda IVA a 2 casas decimais', function () {
        $method = new ReflectionMethod(QuoteRepository::class, 'calculateIva');
        $method->setAccessible(true);

        $data = [
            'sub_total'         => 33333.33,
            'discount_amount'   => 0.0,
            'adjustment_amount' => 0.0,
            'iva_percentage'    => 14.0,
            'iva_exempt'        => false,
        ];

        $result = $method->invoke($this->repository, $data);

        // 33333.33 * 14% = 4666.6662 → arredondado para 4666.67
        expect($result['iva_amount'])->toBe(round(33333.33 * 0.14, 2));
    });

    it('grand_total com IVA de 14% é sempre maior que sub_total (quando não isento)', function () {
        $method = new ReflectionMethod(QuoteRepository::class, 'calculateIva');
        $method->setAccessible(true);

        $data = [
            'sub_total'         => 200000.00,
            'discount_amount'   => 0.0,
            'adjustment_amount' => 0.0,
            'iva_percentage'    => 14.0,
            'iva_exempt'        => false,
        ];

        $result = $method->invoke($this->repository, $data);

        expect($result['grand_total'])->toBeGreaterThan($data['sub_total']);
    });

});
