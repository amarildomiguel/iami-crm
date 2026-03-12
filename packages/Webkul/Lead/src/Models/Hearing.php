<?php

namespace Webkul\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Lead\Contracts\Hearing as HearingContract;
use Webkul\User\Models\UserProxy;

class Hearing extends Model implements HearingContract
{
    protected $table = 'hearings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lead_id',
        'hearing_type',
        'scheduled_at',
        'court',
        'court_room',
        'judge_name',
        'notes',
        'status',
        'outcome',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /**
     * Get the process (lead) this hearing belongs to.
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
}
