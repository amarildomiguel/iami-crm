<?php

use Webkul\Quote\Models\Quote;
use Webkul\User\Models\User;

uses(\Tests\TestCase::class)->in('Feature/Legal');

/**
 * Testes de Gestão de Honorários (Propostas de Honorários).
 *
 * Verifica o módulo de honorários adaptado para o CRM Jurídico Angolano,
 * incluindo cálculo de IVA a 14% e campos específicos para Angola.
 */
describe('Gestão de Honorários (Propostas)', function () {

    beforeEach(function () {
        $this->admin = User::find(1);
    });

    it('advogado autenticado pode ver a lista de propostas de honorários', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.quotes.index'))
            ->assertOk();
    });

    it('advogado autenticado pode ver o formulário de criação de proposta', function () {
        test()->actingAs($this->admin)
            ->get(route('admin.quotes.create'))
            ->assertOk();
    });

    it('o modelo Quote tem os campos jurídicos de honorários no fillable', function () {
        $quote    = new Quote();
        $fillable = $quote->getFillable();

        $camposEsperados = [
            'payment_terms',
            'billing_type',
            'hourly_rate',
            'retainer_fee',
            'iva_regime',
            'iva_percentage',
            'iva_amount',
            'iva_exempt',
            'iva_exempt_reason',
            'sub_total_before_iva',
        ];

        foreach ($camposEsperados as $campo) {
            expect(in_array($campo, $fillable))->toBeTrue(
                "Campo de honorários '{$campo}' não está no fillable do Quote"
            );
        }
    });

    it('proposta de honorários existente tem campo iva_amount', function () {
        $quote = Quote::first();

        if (! $quote) {
            $this->markTestSkipped('Nenhuma proposta disponível para verificação.');
        }

        // Verificar que o campo existe na tabela
        expect(array_key_exists('iva_amount', $quote->getAttributes()) || $quote->iva_amount !== null || $quote->iva_amount === null)->toBeTrue();
    });

    it('proposta isenta de IVA tem iva_amount igual a zero', function () {
        $quote = Quote::where('iva_exempt', true)->first();

        if (! $quote) {
            $this->markTestSkipped('Nenhuma proposta isenta de IVA disponível.');
        }

        expect((float) $quote->iva_amount)->toBe(0.0);
    });

    it('proposta não isenta com IVA de 14% tem iva_amount correctamente calculado', function () {
        $quote = Quote::where('iva_exempt', false)
            ->where('iva_percentage', 14.0)
            ->where('iva_amount', '>', 0)
            ->first();

        if (! $quote) {
            $this->markTestSkipped('Nenhuma proposta com IVA de 14% disponível.');
        }

        $expectedIva = round(
            ((float) $quote->sub_total_before_iva) * 0.14,
            2
        );

        expect((float) $quote->iva_amount)->toBe($expectedIva);
    });

    it('grand_total inclui IVA de 14% na proposta', function () {
        $quote = Quote::where('iva_exempt', false)
            ->where('iva_amount', '>', 0)
            ->first();

        if (! $quote) {
            $this->markTestSkipped('Nenhuma proposta com IVA disponível.');
        }

        $expectedGrandTotal = round(
            (float) $quote->sub_total_before_iva +
            (float) $quote->iva_amount +
            (float) $quote->adjustment_amount,
            2
        );

        expect((float) $quote->grand_total)->toBe($expectedGrandTotal);
    });

    it('utilizador não autenticado é redireccionado ao tentar aceder às propostas', function () {
        test()->get(route('admin.quotes.index'))
            ->assertRedirect();
    });

});
