<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Lead\Models\Lead;
use Webkul\Lead\Models\LegalDocument;
use Webkul\User\Models\User;

class LegalDocumentFactory extends Factory
{
    protected $model = LegalDocument::class;

    public function definition(): array
    {
        return [
            'title'           => $this->faker->sentence(4),
            'document_type'   => $this->faker->randomElement([
                'peticao_inicial',
                'contestacao',
                'recurso',
                'procuracao',
                'contrato',
                'parecer',
                'requerimento',
                'sentenca',
            ]),
            'description'     => $this->faker->optional()->paragraph(),
            'file_path'       => null,
            'file_type'       => null,
            'lead_id'         => Lead::factory(),
            'person_id'       => null,
            'user_id'         => User::factory(),
            'status'          => $this->faker->randomElement(['rascunho', 'finalizado', 'arquivado']),
            'due_date'        => $this->faker->optional()->dateTimeBetween('now', '+6 months'),
            'filing_date'     => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'court_reference' => $this->faker->optional()->numerify('REF-####-####'),
        ];
    }
}
