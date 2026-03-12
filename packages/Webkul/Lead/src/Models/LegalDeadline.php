<?php

namespace Webkul\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Lead\Contracts\LegalDeadline as LegalDeadlineContract;
use Webkul\User\Models\UserProxy;

class LegalDeadline extends Model implements LegalDeadlineContract
{
    protected $table = 'legal_deadlines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lead_id',
        'title',
        'description',
        'start_date',
        'due_date',
        'business_days',
        'status',
        'priority',
        'court_deadline',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date'     => 'date',
        'due_date'       => 'date',
        'court_deadline' => 'boolean',
    ];

    /**
     * Get the process (lead) this deadline belongs to.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(LeadProxy::modelClass());
    }

    /**
     * Get the responsible lawyer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserProxy::modelClass());
    }

    /**
     * Scope: deadlines expiring within the next N business days.
     */
    public function scopeExpiringSoon($query, int $days = 5)
    {
        return $query->where('status', 'pendente')
            ->whereDate('due_date', '>=', now())
            ->whereDate('due_date', '<=', now()->addDays($days));
    }
}
