<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Lead\Models\Lead;
use Webkul\Lead\Models\TimeEntry;
use Webkul\User\Models\User;

class TimeEntryFactory extends Factory
{
    protected $model = TimeEntry::class;

    public function definition(): array
    {
        $hours      = $this->faker->randomElement([0.5, 1.0, 1.5, 2.0, 2.5, 3.0, 4.0, 8.0]);
        $hourlyRate = $this->faker->randomElement([15000.0, 20000.0, 25000.0, 30000.0]);

        return [
            'lead_id'       => Lead::factory(),
            'user_id'       => User::factory(),
            'entry_date'    => $this->faker->dateTimeBetween('-3 months', 'now'),
            'hours'         => $hours,
            'description'   => $this->faker->sentence(),
            'activity_type' => $this->faker->randomElement([
                'reuniao',
                'audiencia',
                'redacao',
                'pesquisa',
                'negociacao',
                'deslocacao',
                'correspondencia',
                'outro',
            ]),
            'hourly_rate'   => $hourlyRate,
            'total_amount'  => round($hours * $hourlyRate, 2),
            'billable'      => $this->faker->boolean(80),
            'billed'        => false,
        ];
    }
}
