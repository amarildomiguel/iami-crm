<?php

namespace Webkul\Admin\DataGrids\Deadline;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class DeadlineDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('legal_deadlines')
            ->select(
                'legal_deadlines.id',
                'legal_deadlines.title',
                'legal_deadlines.start_date',
                'legal_deadlines.due_date',
                'legal_deadlines.business_days',
                'legal_deadlines.status',
                'legal_deadlines.priority',
                'legal_deadlines.court_deadline',
                'legal_deadlines.created_at',
                'leads.id as lead_id',
                'leads.title as lead_title',
                'users.id as user_id',
                'users.name as lawyer_name',
            )
            ->leftJoin('leads', 'legal_deadlines.lead_id', '=', 'leads.id')
            ->leftJoin('users', 'legal_deadlines.user_id', '=', 'users.id');

        $this->addFilter('id', 'legal_deadlines.id');
        $this->addFilter('title', 'legal_deadlines.title');
        $this->addFilter('due_date', 'legal_deadlines.due_date');
        $this->addFilter('status', 'legal_deadlines.status');
        $this->addFilter('priority', 'legal_deadlines.priority');
        $this->addFilter('court_deadline', 'legal_deadlines.court_deadline');
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
            'label'      => trans('admin::app.deadlines.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.deadlines.index.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'lead_title',
            'label'      => trans('admin::app.deadlines.index.datagrid.process'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'due_date',
            'label'      => trans('admin::app.deadlines.index.datagrid.due-date'),
            'type'       => 'date',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'business_days',
            'label'      => trans('admin::app.deadlines.index.datagrid.business-days'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'            => 'priority',
            'label'            => trans('admin::app.deadlines.index.datagrid.priority'),
            'type'             => 'string',
            'searchable'       => false,
            'filterable'       => true,
            'sortable'         => true,
            'dropdown_options' => [
                ['label' => 'Baixa',   'value' => 'baixa'],
                ['label' => 'Normal',  'value' => 'normal'],
                ['label' => 'Alta',    'value' => 'alta'],
                ['label' => 'Urgente', 'value' => 'urgente'],
            ],
        ]);

        $this->addColumn([
            'index'            => 'status',
            'label'            => trans('admin::app.deadlines.index.datagrid.status'),
            'type'             => 'string',
            'searchable'       => false,
            'filterable'       => true,
            'sortable'         => true,
            'dropdown_options' => [
                ['label' => 'Pendente',   'value' => 'pendente'],
                ['label' => 'Em Curso',   'value' => 'em_curso'],
                ['label' => 'Concluído',  'value' => 'concluido'],
                ['label' => 'Expirado',   'value' => 'expirado'],
            ],
        ]);

        $this->addColumn([
            'index'            => 'court_deadline',
            'label'            => trans('admin::app.deadlines.index.datagrid.court-deadline'),
            'type'             => 'string',
            'searchable'       => false,
            'filterable'       => true,
            'sortable'         => false,
            'dropdown_options' => [
                ['label' => 'Sim', 'value' => '1'],
                ['label' => 'Não', 'value' => '0'],
            ],
            'closure'          => fn ($row) => $row->court_deadline ? 'Sim' : 'Não',
        ]);

        $this->addColumn([
            'index'      => 'lawyer_name',
            'label'      => trans('admin::app.deadlines.index.datagrid.lawyer'),
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
        if (bouncer()->hasPermission('deadlines.view')) {
            $this->addAction([
                'index'  => 'calendar',
                'icon'   => 'icon-calendar',
                'title'  => trans('admin::app.deadlines.index.datagrid.calendar'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.deadlines.calendar'),
            ]);
        }

        if (bouncer()->hasPermission('deadlines.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.deadlines.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => fn ($row) => route('admin.deadlines.delete', $row->id),
            ]);
        }
    }
}
