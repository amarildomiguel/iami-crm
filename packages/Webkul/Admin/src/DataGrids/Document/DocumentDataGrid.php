<?php

namespace Webkul\Admin\DataGrids\Document;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class DocumentDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('legal_documents')
            ->select(
                'legal_documents.id',
                'legal_documents.title',
                'legal_documents.document_type',
                'legal_documents.status',
                'legal_documents.due_date',
                'legal_documents.filing_date',
                'legal_documents.court_reference',
                'legal_documents.created_at',
                'leads.id as lead_id',
                'leads.title as lead_title',
                'persons.id as person_id',
                'persons.name as person_name',
                'users.id as user_id',
                'users.name as lawyer_name',
            )
            ->leftJoin('leads', 'legal_documents.lead_id', '=', 'leads.id')
            ->leftJoin('persons', 'legal_documents.person_id', '=', 'persons.id')
            ->leftJoin('users', 'legal_documents.user_id', '=', 'users.id');

        $this->addFilter('id', 'legal_documents.id');
        $this->addFilter('title', 'legal_documents.title');
        $this->addFilter('document_type', 'legal_documents.document_type');
        $this->addFilter('status', 'legal_documents.status');
        $this->addFilter('lead_title', 'leads.title');
        $this->addFilter('lawyer_name', 'users.name');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.documents.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.documents.index.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'document_type',
            'label'      => trans('admin::app.documents.index.datagrid.type'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'lead_title',
            'label'      => trans('admin::app.documents.index.datagrid.process'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'person_name',
            'label'      => trans('admin::app.documents.index.datagrid.client'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'due_date',
            'label'      => trans('admin::app.documents.index.datagrid.due-date'),
            'type'       => 'date',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'            => 'status',
            'label'            => trans('admin::app.documents.index.datagrid.status'),
            'type'             => 'string',
            'searchable'       => false,
            'filterable'       => true,
            'sortable'         => true,
            'dropdown_options' => [
                ['label' => 'Rascunho',    'value' => 'rascunho'],
                ['label' => 'Em Revisão',  'value' => 'revisao'],
                ['label' => 'Finalizado',  'value' => 'finalizado'],
                ['label' => 'Protocolado', 'value' => 'protocolado'],
            ],
        ]);

        $this->addColumn([
            'index'      => 'lawyer_name',
            'label'      => trans('admin::app.documents.index.datagrid.lawyer'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('documents.view')) {
            $this->addAction([
                'index'  => 'view',
                'icon'   => 'icon-eye',
                'title'  => trans('admin::app.documents.index.datagrid.view'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.documents.view', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('documents.view')) {
            $this->addAction([
                'index'  => 'download',
                'icon'   => 'icon-download',
                'title'  => trans('admin::app.documents.index.datagrid.download'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.documents.download', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('documents.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.documents.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => fn ($row) => route('admin.documents.delete', $row->id),
            ]);
        }
    }
}
