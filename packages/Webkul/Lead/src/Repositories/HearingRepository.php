<?php

namespace Webkul\Lead\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Lead\Contracts\Hearing;

class HearingRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'lead_id',
        'hearing_type',
        'scheduled_at',
        'court',
        'status',
        'user_id',
    ];

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Hearing::class;
    }

    /**
     * Get upcoming hearings within the next N days.
     */
    public function getUpcoming(int $days = 7): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model()::with(['lead', 'user'])
            ->where('status', 'agendada')
            ->whereBetween('scheduled_at', [now(), now()->addDays($days)])
            ->orderBy('scheduled_at')
            ->get();
    }

    /**
     * Get hearings for a specific lead.
     */
    public function getByLead(int $leadId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model()::with(['user'])
            ->where('lead_id', $leadId)
            ->orderBy('scheduled_at', 'desc')
            ->get();
    }
}
