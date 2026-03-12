<?php

namespace Webkul\Lead\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Lead\Contracts\TimeEntry;

class TimeEntryRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'lead_id',
        'user_id',
        'entry_date',
        'activity_type',
        'billable',
        'billed',
    ];

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return TimeEntry::class;
    }

    /**
     * Get unbilled billable hours total for a lead.
     */
    public function getUnbilledTotal(int $leadId): float
    {
        return (float) $this->model()::where('lead_id', $leadId)
            ->where('billable', true)
            ->where('billed', false)
            ->sum('total_amount');
    }

    /**
     * Get total hours logged for a lead.
     */
    public function getTotalHours(int $leadId): float
    {
        return (float) $this->model()::where('lead_id', $leadId)->sum('hours');
    }

    /**
     * Get time entries for a specific lead.
     */
    public function getByLead(int $leadId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model()::with(['user'])
            ->where('lead_id', $leadId)
            ->orderBy('entry_date', 'desc')
            ->get();
    }

    /**
     * Mark entries as billed.
     */
    public function markAsBilled(array $ids): int
    {
        return $this->model()::whereIn('id', $ids)->update(['billed' => true]);
    }
}
