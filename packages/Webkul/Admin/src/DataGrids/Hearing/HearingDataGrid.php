<?php

namespace Webkul\Admin\DataGrids\Hearing;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class HearingDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('hearings')
            ->select(
                'hearings.id',
                'hearings.hearing_type',
                'hearings.scheduled_at',
                'hearings.court',
                'hearings.court_room',
                'hearings.judge_name',
                'hearings.status',
                'hearings.outcome',
                'hearings.created_at',
                'leads.id as lead_id',
                'leads.title as lead_title',
                'users.id as user_id',
                'users.name as lawyer_name',
            )
            ->leftJoin('leads', 'hearings.lead_id', '=', 'leads.id')
            ->leftJoin('users', 'hearings.user_id', '=', 'users.id');

        $this->addFilter('id', 'hearings.id');
        $this->addFilter('hearing_type', 'hearings.hearing_type');
        $this->addFilter('scheduled_at', 'hearings.scheduled_at');
        $this->addFilter('court', 'hearings.court');
        $this->addFilter('status', 'hearings.status');
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
            'label'      => trans('admin::app.hearings.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'lead_title',
            'label'      => trans('admin::app.hearings.index.datagrid.process'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'hearing_type',
            'label'      => trans('admin::app.hearings.index.datagrid.type'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'scheduled_at',
            'label'      => trans('admin::app.hearings.index.datagrid.scheduled-at'),
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'court',
            'label'      => trans('admin::app.hearings.index.datagrid.court'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'judge_name',
            'label'      => trans('admin::app.hearings.index.datagrid.judge'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'            => 'status',
            'label'            => trans('admin::app.hearings.index.datagrid.status'),
            'type'             => 'string',
            'searchable'       => false,
            'filterable'       => true,
            'sortable'         => true,
            'dropdown_options' => [
                ['label' => 'Agendada',   'value' => 'agendada'],
                ['label' => 'Realizada',  'value' => 'realizada'],
                ['label' => 'Adiada',     'value' => 'adiada'],
                ['label' => 'Cancelada',  'value' => 'cancelada'],
            ],
        ]);

        $this->addColumn([
            'index'      => 'lawyer_name',
            'label'      => trans('admin::app.hearings.index.datagrid.lawyer'),
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
        if (bouncer()->hasPermission('hearings.view')) {
            $this->addAction([
                'index'  => 'view',
                'icon'   => 'icon-eye',
                'title'  => trans('admin::app.hearings.index.datagrid.view'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.hearings.view', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('hearings.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.hearings.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => fn ($row) => route('admin.hearings.delete', $row->id),
            ]);
        }
    }
}
