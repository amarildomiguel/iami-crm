<?php

namespace Webkul\Admin\Http\Controllers\TimeEntry;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\TimeEntry\TimeEntryDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Lead\Repositories\TimeEntryRepository;
use Webkul\User\Repositories\UserRepository;

class TimeEntryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected TimeEntryRepository $timeEntryRepository,
        protected LeadRepository $leadRepository,
        protected UserRepository $userRepository,
    ) {}

    /**
     * Display a listing of time entries.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(TimeEntryDataGrid::class)->process();
        }

        return view('admin::time-entries.index');
    }

    /**
     * Show the form for creating a new time entry.
     */
    public function create(): View
    {
        $leads = $this->leadRepository->all(['id', 'title']);
        $users = $this->userRepository->all(['id', 'name']);

        $activityTypes = [
            'reuniao'       => 'Reunião / Consulta',
            'audiencia'     => 'Audiência',
            'redacao'       => 'Redacção de Peça Processual',
            'pesquisa'      => 'Pesquisa Jurídica',
            'negociacao'    => 'Negociação',
            'deslocacao'    => 'Deslocação ao Tribunal',
            'correspondencia' => 'Correspondência',
            'outro'         => 'Outro',
        ];

        return view('admin::time-entries.create', compact('leads', 'users', 'activityTypes'));
    }

    /**
     * Store a newly created time entry.
     */
    public function store(): RedirectResponse|JsonResponse
    {
        $this->validate(request(), [
            'lead_id'       => 'required|integer|exists:leads,id',
            'user_id'       => 'required|integer|exists:users,id',
            'entry_date'    => 'required|date',
            'hours'         => 'required|numeric|min:0.25|max:24',
            'description'   => 'required|string',
            'activity_type' => 'required|string',
        ]);

        $data = request()->only([
            'lead_id', 'user_id', 'entry_date', 'hours',
            'description', 'activity_type', 'hourly_rate', 'billable',
        ]);

        $data['billable'] = isset($data['billable']) ? (bool) $data['billable'] : true;
        $data['billed']   = false;

        if (! empty($data['hourly_rate']) && ! empty($data['hours'])) {
            $data['total_amount'] = round((float) $data['hours'] * (float) $data['hourly_rate'], 2);
        }

        $entry = $this->timeEntryRepository->create($data);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.time-entries.index.create-success'),
                'data'    => $entry,
            ]);
        }

        session()->flash('success', trans('admin::app.time-entries.index.create-success'));

        return redirect()->route('admin.time-entries.index');
    }

    /**
     * Update the specified time entry.
     */
    public function update(int $id): RedirectResponse|JsonResponse
    {
        $this->validate(request(), [
            'entry_date'    => 'required|date',
            'hours'         => 'required|numeric|min:0.25|max:24',
            'description'   => 'required|string',
            'activity_type' => 'required|string',
        ]);

        $data = request()->only([
            'entry_date', 'hours', 'description', 'activity_type',
            'hourly_rate', 'billable', 'billed',
        ]);

        if (! empty($data['hourly_rate']) && ! empty($data['hours'])) {
            $data['total_amount'] = round((float) $data['hours'] * (float) $data['hourly_rate'], 2);
        }

        $entry = $this->timeEntryRepository->update($data, $id);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.time-entries.index.update-success'),
                'data'    => $entry,
            ]);
        }

        session()->flash('success', trans('admin::app.time-entries.index.update-success'));

        return redirect()->route('admin.time-entries.index');
    }

    /**
     * Mark time entries as billed.
     */
    public function markBilled(): JsonResponse
    {
        $ids = request('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'Nenhuma entrada seleccionada.'], 422);
        }

        $count = $this->timeEntryRepository->markAsBilled($ids);

        return response()->json([
            'message' => trans('admin::app.time-entries.index.billed-success', ['count' => $count]),
        ]);
    }

    /**
     * Remove the specified time entry.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->timeEntryRepository->delete($id);

        return response()->json([
            'message' => trans('admin::app.time-entries.index.delete-success'),
        ]);
    }
}
