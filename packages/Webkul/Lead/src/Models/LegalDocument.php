<?php

namespace Webkul\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Contact\Models\PersonProxy;
use Webkul\Lead\Contracts\LegalDocument as LegalDocumentContract;
use Webkul\User\Models\UserProxy;

class LegalDocument extends Model implements LegalDocumentContract
{
    protected $table = 'legal_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'document_type',
        'description',
        'file_path',
        'file_type',
        'lead_id',
        'person_id',
        'user_id',
        'status',
        'due_date',
        'filing_date',
        'court_reference',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'due_date'    => 'date',
        'filing_date' => 'date',
    ];

    /**
     * Get the process (lead) this document belongs to.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(LeadProxy::modelClass());
    }

    /**
     * Get the client (person) this document belongs to.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(PersonProxy::modelClass());
    }

    /**
     * Get the responsible lawyer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserProxy::modelClass());
    }
}
