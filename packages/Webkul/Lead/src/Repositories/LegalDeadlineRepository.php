<?php

namespace Webkul\Lead\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Lead\Contracts\LegalDeadline;

class LegalDeadlineRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'lead_id',
        'title',
        'due_date',
        'status',
        'priority',
        'court_deadline',
        'user_id',
    ];

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return LegalDeadline::class;
    }

    /**
     * Get deadlines expiring in the next N days.
     */
    public function getExpiringSoon(int $days = 5): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model()::with(['lead', 'user'])
            ->where('status', 'pendente')
            ->whereDate('due_date', '>=', now())
            ->whereDate('due_date', '<=', now()->addDays($days))
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get overdue deadlines.
     */
    public function getOverdue(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model()::with(['lead', 'user'])
            ->where('status', 'pendente')
            ->whereDate('due_date', '<', now())
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get deadlines for a specific lead.
     */
    public function getByLead(int $leadId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model()::with(['user'])
            ->where('lead_id', $leadId)
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Calculate due date from start date considering Angolan business days.
     *
     * Recalculates holiday lists whenever the year changes during iteration,
     * so that year-crossing deadlines (e.g. starting 31/12) are handled correctly.
     */
    public function calculateDueDate(\Carbon\Carbon $startDate, int $businessDays): \Carbon\Carbon
    {
        $date            = $startDate->copy();
        $currentYear     = $date->year;
        $angolanHolidays = $this->getAngolanHolidays($currentYear);
        $daysAdded       = 0;

        while ($daysAdded < $businessDays) {
            $date->addDay();

            // Refresh holiday list when the year changes
            if ($date->year !== $currentYear) {
                $currentYear     = $date->year;
                $angolanHolidays = $this->getAngolanHolidays($currentYear);
            }

            if ($date->isWeekday() && ! in_array($date->format('Y-m-d'), $angolanHolidays)) {
                $daysAdded++;
            }
        }

        return $date;
    }

    /**
     * Get Angolan public holidays for a given year.
     */
    private function getAngolanHolidays(int $year): array
    {
        return [
            "$year-01-01", // Ano Novo
            "$year-01-04", // Dia dos Mártires da Repressão Colonial
            "$year-02-04", // Início da Luta Armada
            "$year-03-08", // Dia Internacional da Mulher
            "$year-04-04", // Dia da Paz e Reconciliação Nacional
            "$year-05-01", // Dia do Trabalhador
            "$year-09-17", // Dia do Herói Nacional
            "$year-11-02", // Dia dos Finados
            "$year-11-11", // Dia da Independência Nacional
            "$year-12-25", // Natal
        ];
    }
}
