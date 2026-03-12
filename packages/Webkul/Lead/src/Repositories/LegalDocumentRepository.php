<?php

namespace Webkul\Lead\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Lead\Contracts\LegalDocument;

class LegalDocumentRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'title',
        'document_type',
        'lead_id',
        'person_id',
        'user_id',
        'status',
    ];

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return LegalDocument::class;
    }

    /**
     * Get documents for a specific lead.
     */
    public function getByLead(int $leadId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model()::with(['user', 'person'])
            ->where('lead_id', $leadId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Available document types.
     */
    public static function documentTypes(): array
    {
        return [
            'peticao_inicial'   => 'Petição Inicial',
            'contestacao'       => 'Contestação',
            'replica'           => 'Réplica / Tréplica',
            'recurso'           => 'Recurso',
            'procuracao'        => 'Procuração',
            'contrato'          => 'Contrato',
            'parecer'           => 'Parecer Jurídico',
            'requerimento'      => 'Requerimento',
            'notificacao'       => 'Notificação',
            'sentenca'          => 'Sentença / Acórdão',
        ];
    }
}
