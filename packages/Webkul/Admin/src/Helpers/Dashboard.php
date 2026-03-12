<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Helpers\Reporting\Activity;
use Webkul\Admin\Helpers\Reporting\Lead;
use Webkul\Admin\Helpers\Reporting\Organization;
use Webkul\Admin\Helpers\Reporting\Person;
use Webkul\Admin\Helpers\Reporting\Product;
use Webkul\Admin\Helpers\Reporting\Quote;

class Dashboard
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Lead $leadReporting,
        protected Activity $activityReporting,
        protected Product $productReporting,
        protected Person $personReporting,
        protected Organization $organizationReporting,
        protected Quote $quoteReporting,
    ) {}

    /**
     * Returns the overall revenue statistics.
     */
    public function getRevenueStats(): array
    {
        return [
            'total_won_revenue'  => $this->leadReporting->getTotalWonLeadValueProgress(),
            'total_lost_revenue' => $this->leadReporting->getTotalLostLeadValueProgress(),
        ];
    }

    /**
     * Returns the overall statistics.
     */
    public function getOverAllStats(): array
    {
        return [
            'total_leads'           => $this->leadReporting->getTotalLeadsProgress(),
            'average_lead_value'    => $this->leadReporting->getAverageLeadValueProgress(),
            'average_leads_per_day' => $this->leadReporting->getAverageLeadsPerDayProgress(),
            'total_quotations'      => $this->quoteReporting->getTotalQuotesProgress(),
            'total_persons'         => $this->personReporting->getTotalPersonsProgress(),
            'total_organizations'   => $this->organizationReporting->getTotalOrganizationsProgress(),
        ];
    }

    /**
     * Returns leads statistics.
     */
    public function getTotalLeadsStats(): array
    {
        return [
            'all'  => [
                'over_time' => $this->leadReporting->getTotalLeadsOverTime(),
            ],

            'won'  => [
                'over_time' => $this->leadReporting->getTotalWonLeadsOverTime(),
            ],
            'lost' => [
                'over_time' => $this->leadReporting->getTotalLostLeadsOverTime(),
            ],
        ];
    }

    /**
     * Returns leads revenue statistics by sources.
     */
    public function getLeadsStatsBySources(): mixed
    {
        return $this->leadReporting->getTotalWonLeadValueBySources();
    }

    /**
     * Returns leads revenue statistics by types.
     */
    public function getLeadsStatsByTypes(): mixed
    {
        return $this->leadReporting->getTotalWonLeadValueByTypes();
    }

    /**
     * Returns open leads statistics by states.
     */
    public function getOpenLeadsByStates(): mixed
    {
        return $this->leadReporting->getOpenLeadsByStates();
    }

    /**
     * Returns top selling products statistics.
     */
    public function getTopSellingProducts(): Collection
    {
        return $this->productReporting->getTopSellingProductsByRevenue(5);
    }

    /**
     * Returns top selling products statistics.
     */
    public function getTopPersons(): Collection
    {
        return $this->personReporting->getTopCustomersByRevenue(5);
    }

    /**
     * Get the start date.
     *
     * @return \Carbon\Carbon
     */
    public function getStartDate(): Carbon
    {
        return $this->leadReporting->getStartDate();
    }

    /**
     * Get the end date.
     *
     * @return \Carbon\Carbon
     */
    public function getEndDate(): Carbon
    {
        return $this->leadReporting->getEndDate();
    }

    /**
     * Returns date range
     */
    public function getDateRange(): string
    {
        return $this->getStartDate()->format('d M').' - '.$this->getEndDate()->format('d M');
    }

    /**
     * Returns active processes stats (leads not won/lost).
     */
    public function getActiveProcesses(): array
    {
        $activeCount = DB::table('leads')
            ->whereNull('deleted_at')
            ->whereNotIn('lead_pipeline_stage_id', function ($q) {
                $q->select('id')->from('lead_pipeline_stages')
                  ->whereIn('code', ['won', 'lost']);
            })
            ->count();

        $urgentCount = DB::table('leads')
            ->whereNull('deleted_at')
            ->where('urgency_level', 'urgente')
            ->whereNotIn('lead_pipeline_stage_id', function ($q) {
                $q->select('id')->from('lead_pipeline_stages')
                  ->whereIn('code', ['won', 'lost']);
            })
            ->count();

        return [
            'active_count' => $activeCount,
            'urgent_count' => $urgentCount,
        ];
    }

    /**
     * Returns upcoming hearings for this week.
     */
    public function getUpcomingHearings(): array
    {
        $now  = Carbon::now();
        $week = Carbon::now()->endOfWeek();

        $hearings = DB::table('hearings')
            ->select('hearings.*', 'leads.title as process_title')
            ->leftJoin('leads', 'hearings.lead_id', '=', 'leads.id')
            ->where('hearings.scheduled_at', '>=', $now)
            ->where('hearings.scheduled_at', '<=', $week)
            ->where('hearings.status', '!=', 'cancelada')
            ->orderBy('hearings.scheduled_at')
            ->limit(10)
            ->get()
            ->map(function ($h) {
                return [
                    'id'             => $h->id,
                    'type'           => $h->hearing_type,
                    'court'          => $h->court,
                    'scheduled_at'   => Carbon::parse($h->scheduled_at)->format('d/m/Y H:i'),
                    'process_title'  => $h->process_title,
                    'status'         => $h->status,
                ];
            })
            ->toArray();

        return [
            'total'    => count($hearings),
            'hearings' => $hearings,
        ];
    }

    /**
     * Returns deadlines due in the next 5 business days.
     */
    public function getUpcomingDeadlines(): array
    {
        $now      = Carbon::now();
        $deadline = Carbon::now()->addDays(7);

        $deadlines = DB::table('legal_deadlines')
            ->select('legal_deadlines.*', 'leads.title as process_title')
            ->leftJoin('leads', 'legal_deadlines.lead_id', '=', 'leads.id')
            ->where('legal_deadlines.due_date', '>=', $now->toDateString())
            ->where('legal_deadlines.due_date', '<=', $deadline->toDateString())
            ->whereIn('legal_deadlines.status', ['pendente', 'em_curso'])
            ->orderBy('legal_deadlines.due_date')
            ->limit(10)
            ->get()
            ->map(function ($d) {
                $daysLeft = Carbon::now()->diffInDays(Carbon::parse($d->due_date), false);

                return [
                    'id'            => $d->id,
                    'title'         => $d->title,
                    'due_date'      => Carbon::parse($d->due_date)->format('d/m/Y'),
                    'process_title' => $d->process_title,
                    'priority'      => $d->priority,
                    'days_left'     => (int) $daysLeft,
                    'is_overdue'    => $daysLeft < 0,
                ];
            })
            ->toArray();

        $overdue = DB::table('legal_deadlines')
            ->whereIn('status', ['pendente', 'em_curso'])
            ->where('due_date', '<', $now->toDateString())
            ->count();

        return [
            'total'    => count($deadlines),
            'overdue'  => $overdue,
            'deadlines' => $deadlines,
        ];
    }

    /**
     * Returns billable hours not yet invoiced.
     */
    public function getBillableHours(): array
    {
        $totalBillable = DB::table('time_entries')
            ->where('billable', true)
            ->where('billed', false)
            ->sum('hours') ?? 0;

        $totalAmount = DB::table('time_entries')
            ->where('billable', true)
            ->where('billed', false)
            ->sum('total_amount') ?? 0;

        $byLawyer = DB::table('time_entries')
            ->select('users.name', DB::raw('SUM(time_entries.hours) as total_hours'), DB::raw('SUM(time_entries.total_amount) as total_amount'))
            ->leftJoin('users', 'time_entries.user_id', '=', 'users.id')
            ->where('time_entries.billable', true)
            ->where('time_entries.billed', false)
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_hours')
            ->limit(5)
            ->get()
            ->map(function ($r) {
                return [
                    'name'         => $r->name,
                    'total_hours'  => round((float) $r->total_hours, 2),
                    'total_amount' => number_format((float) $r->total_amount, 2, ',', '.'),
                ];
            })
            ->toArray();

        return [
            'total_hours'  => round((float) $totalBillable, 2),
            'total_amount' => number_format((float) $totalAmount, 2, ',', '.'),
            'by_lawyer'    => $byLawyer,
        ];
    }

    /**
     * Returns processes grouped by legal area.
     */
    public function getProcessesByLegalArea(): array
    {
        $data = DB::table('leads')
            ->select(DB::raw('COALESCE(legal_area, "Não definida") as legal_area'), DB::raw('COUNT(*) as total'))
            ->whereNull('deleted_at')
            ->groupBy('legal_area')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($r) => ['label' => $r->legal_area, 'total' => (int) $r->total])
            ->toArray();

        return ['areas' => $data];
    }

    /**
     * Returns processes grouped by province.
     */
    public function getProcessesByProvince(): array
    {
        $data = DB::table('leads')
            ->select(DB::raw('COALESCE(province, "Não definida") as province'), DB::raw('COUNT(*) as total'))
            ->whereNull('deleted_at')
            ->groupBy('province')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($r) => ['label' => $r->province, 'total' => (int) $r->total])
            ->toArray();

        return ['provinces' => $data];
    }

    /**
     * Returns performance per lawyer (won vs lost).
     */
    public function getLawyerPerformance(): array
    {
        $wonStageIds = DB::table('lead_pipeline_stages')->where('code', 'won')->pluck('id')->toArray();
        $lostStageIds = DB::table('lead_pipeline_stages')->where('code', 'lost')->pluck('id')->toArray();

        $lawyers = DB::table('leads')
            ->select(
                'users.name',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN lead_pipeline_stage_id IN ('.implode(',', count($wonStageIds) ? $wonStageIds : [0]).') THEN 1 ELSE 0 END) as won'),
                DB::raw('SUM(CASE WHEN lead_pipeline_stage_id IN ('.implode(',', count($lostStageIds) ? $lostStageIds : [0]).') THEN 1 ELSE 0 END) as lost'),
            )
            ->leftJoin('users', 'leads.user_id', '=', 'users.id')
            ->whereNull('leads.deleted_at')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($r) => [
                'name'  => $r->name ?? 'N/D',
                'total' => (int) $r->total,
                'won'   => (int) $r->won,
                'lost'  => (int) $r->lost,
            ])
            ->toArray();

        return ['lawyers' => $lawyers];
    }
}
