<?php

namespace Webkul\Admin\DataGrids\TimeEntry;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class TimeEntryDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('time_entries')
            ->select(
                'time_entries.id',
                'time_entries.entry_date',
                'time_entries.hours',
                'time_entries.description',
                'time_entries.activity_type',
                'time_entries.hourly_rate',
                'time_entries.total_amount',
                'time_entries.billable',
                'time_entries.billed',
                'time_entries.created_at',
                'leads.id as lead_id',
                'leads.title as lead_title',
                'users.id as user_id',
                'users.name as lawyer_name',
            )
            ->leftJoin('leads', 'time_entries.lead_id', '=', 'leads.id')
            ->leftJoin('users', 'time_entries.user_id', '=', 'users.id');

        $this->addFilter('id', 'time_entries.id');
        $this->addFilter('entry_date', 'time_entries.entry_date');
        $this->addFilter('activity_type', 'time_entries.activity_type');
        $this->addFilter('billable', 'time_entries.billable');
        $this->addFilter('billed', 'time_entries.billed');
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
            'label'      => trans('admin::app.time-entries.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'entry_date',
            'label'      => trans('admin::app.time-entries.index.datagrid.date'),
            'type'       => 'date',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'lead_title',
            'label'      => trans('admin::app.time-entries.index.datagrid.process'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'activity_type',
            'label'      => trans('admin::app.time-entries.index.datagrid.activity-type'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'hours',
            'label'      => trans('admin::app.time-entries.index.datagrid.hours'),
            'type'       => 'decimal',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'hourly_rate',
            'label'      => trans('admin::app.time-entries.index.datagrid.hourly-rate'),
            'type'       => 'decimal',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
            'closure'    => fn ($row) => $row->hourly_rate ? number_format((float) $row->hourly_rate, 2, ',', '.') . ' Kz' : '—',
        ]);

        $this->addColumn([
            'index'      => 'total_amount',
            'label'      => trans('admin::app.time-entries.index.datagrid.total'),
            'type'       => 'decimal',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
            'closure'    => fn ($row) => $row->total_amount ? number_format((float) $row->total_amount, 2, ',', '.') . ' Kz' : '—',
        ]);

        $this->addColumn([
            'index'            => 'billable',
            'label'            => trans('admin::app.time-entries.index.datagrid.billable'),
            'type'             => 'string',
            'searchable'       => false,
            'filterable'       => true,
            'sortable'         => false,
            'dropdown_options' => [
                ['label' => 'Sim', 'value' => '1'],
                ['label' => 'Não', 'value' => '0'],
            ],
            'closure'          => fn ($row) => $row->billable ? 'Sim' : 'Não',
        ]);

        $this->addColumn([
            'index'            => 'billed',
            'label'            => trans('admin::app.time-entries.index.datagrid.billed'),
            'type'             => 'string',
            'searchable'       => false,
            'filterable'       => true,
            'sortable'         => false,
            'dropdown_options' => [
                ['label' => 'Facturado',     'value' => '1'],
                ['label' => 'Não Facturado', 'value' => '0'],
            ],
            'closure'          => fn ($row) => $row->billed ? 'Facturado' : 'Não Facturado',
        ]);

        $this->addColumn([
            'index'      => 'lawyer_name',
            'label'      => trans('admin::app.time-entries.index.datagrid.lawyer'),
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
        if (bouncer()->hasPermission('time-entries.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.time-entries.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => fn ($row) => route('admin.time-entries.delete', $row->id),
            ]);
        }
    }
}
