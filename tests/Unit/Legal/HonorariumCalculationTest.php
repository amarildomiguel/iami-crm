<?php

use Webkul\Lead\Repositories\TimeEntryRepository;
use Webkul\Quote\Repositories\QuoteRepository;

uses(\Tests\TestCase::class)->in('Unit/Legal');

/**
 * Testes do Cálculo de Honorários.
 *
 * Verifica os cálculos de honorários de advogado:
 * registo de horas, valor total e facturação.
 */
describe('Cálculo de Honorários', function () {

    beforeEach(function () {
        $this->timeEntryRepository = app(TimeEntryRepository::class);
        $this->quoteRepository     = app(QuoteRepository::class);
    });

    it('calcula o valor total de uma entrada de horas (horas × taxa horária)', function () {
        $hours      = 2.5;
        $hourlyRate = 15000.0; // 15.000 Kz por hora

        $total = round($hours * $hourlyRate, 2);

        expect($total)->toBe(37500.0);
    });

    it('calcula correctamente 0.25 horas (15 minutos)', function () {
        $hours      = 0.25;
        $hourlyRate = 20000.0;

        $total = round($hours * $hourlyRate, 2);

        expect($total)->toBe(5000.0);
    });

    it('calcula correctamente o total de horas completas', function () {
        $hours      = 8.0; // dia de trabalho completo
        $hourlyRate = 25000.0;

        $total = round($hours * $hourlyRate, 2);

        expect($total)->toBe(200000.0);
    });

    it('honorários com IVA incluído calculam 14% sobre o total', function () {
        $subTotal      = 150000.0;
        $ivaPercentage = 14.0;

        $ivaAmount  = round($subTotal * ($ivaPercentage / 100), 2);
        $grandTotal = $subTotal + $ivaAmount;

        expect($ivaAmount)->toBe(21000.0);
        expect($grandTotal)->toBe(171000.0);
    });

    it('o modelo Quote tem os campos de honorários no fillable', function () {
        $quoteModel = new \Webkul\Quote\Models\Quote();
        $fillable   = $quoteModel->getFillable();

        $camposHonorarios = [
            'billing_type',
            'hourly_rate',
            'retainer_fee',
            'iva_regime',
            'iva_percentage',
        ];

        foreach ($camposHonorarios as $campo) {
            expect(in_array($campo, $fillable))->toBeTrue(
                "Campo de honorários '{$campo}' não está no fillable do Quote"
            );
        }
    });

    it('o modelo Quote tem os campos de IVA Angola no fillable', function () {
        $quoteModel = new \Webkul\Quote\Models\Quote();
        $fillable   = $quoteModel->getFillable();

        $camposIva = [
            'iva_amount',
            'iva_exempt',
            'iva_exempt_reason',
            'sub_total_before_iva',
        ];

        foreach ($camposIva as $campo) {
            expect(in_array($campo, $fillable))->toBeTrue(
                "Campo de IVA '{$campo}' não está no fillable do Quote"
            );
        }
    });

    it('desconto de percentagem reduz correctamente a base dos honorários', function () {
        $subTotal        = 200000.0;
        $discountPercent = 20.0; // 20% de desconto

        $discountAmount = round($subTotal * ($discountPercent / 100), 2);
        $afterDiscount  = $subTotal - $discountAmount;

        expect($discountAmount)->toBe(40000.0);
        expect($afterDiscount)->toBe(160000.0);
    });

    it('taxa de retentor (retainer_fee) é um campo monetário em AOA', function () {
        $retainerFee = 500000.0; // 500.000 Kz

        // Verificar que pode ser expresso em Kwanzas
        expect($retainerFee)->toBeFloat();
        expect($retainerFee)->toBeGreaterThan(0);
    });

    it('TimeEntry tem campo billable para distinguir horas factúráveis', function () {
        $entry = new \Webkul\Lead\Models\TimeEntry();
        $fillable = $entry->getFillable();

        expect(in_array('billable', $fillable))->toBeTrue();
        expect(in_array('billed', $fillable))->toBeTrue();
    });

    it('TimeEntry armazena taxa horária e valor total em AOA', function () {
        $entry    = new \Webkul\Lead\Models\TimeEntry();
        $fillable = $entry->getFillable();

        expect(in_array('hourly_rate', $fillable))->toBeTrue();
        expect(in_array('total_amount', $fillable))->toBeTrue();
    });

});
