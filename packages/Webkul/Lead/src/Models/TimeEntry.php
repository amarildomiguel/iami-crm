<?php

namespace Webkul\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Lead\Contracts\TimeEntry as TimeEntryContract;
use Webkul\User\Models\UserProxy;

class TimeEntry extends Model implements TimeEntryContract
{
    protected $table = 'time_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lead_id',
        'user_id',
        'entry_date',
        'hours',
        'description',
        'activity_type',
        'hourly_rate',
        'total_amount',
        'billable',
        'billed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'entry_date'   => 'date',
        'hours'        => 'decimal:2',
        'hourly_rate'  => 'decimal:2',
        'total_amount' => 'decimal:2',
        'billable'     => 'boolean',
        'billed'       => 'boolean',
    ];

    /**
     * Get the process (lead) this time entry belongs to.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(LeadProxy::modelClass());
    }

    /**
     * Get the lawyer who logged the time.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserProxy::modelClass());
    }
}
