<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Lead\Models\Hearing;
use Webkul\Lead\Models\Lead;
use Webkul\User\Models\User;

class HearingFactory extends Factory
{
    protected $model = Hearing::class;

    public function definition(): array
    {
        return [
            'lead_id'      => Lead::factory(),
            'hearing_type' => $this->faker->randomElement(['Julgamento', 'Instrução', 'Conciliação', 'Audiência Prévia']),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+3 months'),
            'court'        => $this->faker->randomElement([
                'Tribunal Provincial de Luanda',
                'Tribunal Supremo',
                'Tribunal de Comarca de Luanda',
                'Tribunal do Trabalho de Luanda',
            ]),
            'court_room'   => $this->faker->optional()->randomElement(['Sala 1', 'Sala 2', 'Sala 3']),
            'judge_name'   => $this->faker->optional()->name(),
            'notes'        => $this->faker->optional()->sentence(),
            'status'       => $this->faker->randomElement(['agendada', 'realizada', 'cancelada', 'adiada']),
            'outcome'      => null,
            'user_id'      => User::factory(),
        ];
    }
}
