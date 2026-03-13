<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Lead\Models\Lead;
use Webkul\Lead\Models\LegalDeadline;
use Webkul\User\Models\User;

class LegalDeadlineFactory extends Factory
{
    protected $model = LegalDeadline::class;

    public function definition(): array
    {
        $startDate    = Carbon::now()->addDays($this->faker->numberBetween(1, 30));
        $businessDays = $this->faker->numberBetween(5, 30);
        $dueDate      = $startDate->copy()->addDays((int) ceil($businessDays * 1.4)); // approximate

        return [
            'lead_id'        => Lead::factory(),
            'title'          => $this->faker->randomElement([
                'Prazo de Contestação',
                'Prazo de Recurso',
                'Prazo para Apresentação de Documentos',
                'Prazo de Réplica',
                'Prazo para Alegações',
            ]),
            'description'    => $this->faker->optional()->sentence(),
            'start_date'     => $startDate->format('Y-m-d'),
            'due_date'       => $dueDate->format('Y-m-d'),
            'business_days'  => $businessDays,
            'status'         => $this->faker->randomElement(['pendente', 'em_curso', 'concluido']),
            'priority'       => $this->faker->randomElement(['baixa', 'normal', 'alta', 'urgente']),
            'court_deadline' => $this->faker->boolean(60),
            'user_id'        => User::factory(),
        ];
    }
}
