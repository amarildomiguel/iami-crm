<?php

namespace Webkul\Admin\Http\Controllers\Deadline;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Deadline\DeadlineDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Lead\Repositories\LegalDeadlineRepository;
use Webkul\User\Repositories\UserRepository;

class DeadlineController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected LegalDeadlineRepository $deadlineRepository,
        protected LeadRepository $leadRepository,
        protected UserRepository $userRepository,
    ) {}

    /**
     * Display a listing of deadlines.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(DeadlineDataGrid::class)->process();
        }

        return view('admin::deadlines.index');
    }

    /**
     * Show deadline calendar view.
     */
    public function calendar(): View
    {
        $deadlines = $this->deadlineRepository->with(['lead', 'user'])
            ->where('status', '!=', 'concluido')
            ->orderBy('due_date')
            ->get();

        return view('admin::deadlines.calendar', compact('deadlines'));
    }

    /**
     * Show the form for creating a new deadline.
     */
    public function create(): View
    {
        $leads = $this->leadRepository->all(['id', 'title']);
        $users = $this->userRepository->all(['id', 'name']);

        return view('admin::deadlines.create', compact('leads', 'users'));
    }

    /**
     * Store a newly created deadline.
     */
    public function store(): RedirectResponse|JsonResponse
    {
        $this->validate(request(), [
            'lead_id'    => 'required|integer|exists:leads,id',
            'title'      => 'required|string|max:255',
            'start_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:start_date',
            'user_id'    => 'required|integer|exists:users,id',
        ]);

        $data = request()->only([
            'lead_id', 'title', 'description', 'start_date', 'due_date',
            'business_days', 'status', 'priority', 'court_deadline', 'user_id',
        ]);

        $data['court_deadline'] = isset($data['court_deadline']) ? (bool) $data['court_deadline'] : false;

        $deadline = $this->deadlineRepository->create($data);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.deadlines.index.create-success'),
                'data'    => $deadline,
            ]);
        }

        session()->flash('success', trans('admin::app.deadlines.index.create-success'));

        return redirect()->route('admin.deadlines.index');
    }

    /**
     * Update the specified deadline.
     */
    public function update(int $id): RedirectResponse|JsonResponse
    {
        $this->validate(request(), [
            'title'      => 'required|string|max:255',
            'start_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:start_date',
            'user_id'    => 'required|integer|exists:users,id',
        ]);

        $data = request()->only([
            'title', 'description', 'start_date', 'due_date',
            'business_days', 'status', 'priority', 'court_deadline', 'user_id',
        ]);

        $data['court_deadline'] = isset($data['court_deadline']) ? (bool) $data['court_deadline'] : false;

        $deadline = $this->deadlineRepository->update($data, $id);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.deadlines.index.update-success'),
                'data'    => $deadline,
            ]);
        }

        session()->flash('success', trans('admin::app.deadlines.index.update-success'));

        return redirect()->route('admin.deadlines.index');
    }

    /**
     * Calculate due date based on business days.
     */
    public function calculateDueDate(): JsonResponse
    {
        $this->validate(request(), [
            'start_date'    => 'required|date',
            'business_days' => 'required|integer|min:1',
        ]);

        $dueDate = $this->deadlineRepository->calculateDueDate(
            \Carbon\Carbon::parse(request('start_date')),
            (int) request('business_days')
        );

        return response()->json(['due_date' => $dueDate->format('Y-m-d')]);
    }

    /**
     * Remove the specified deadline.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->deadlineRepository->delete($id);

        return response()->json([
            'message' => trans('admin::app.deadlines.index.delete-success'),
        ]);
    }
}
