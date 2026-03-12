<?php

namespace Webkul\Admin\Http\Controllers\Hearing;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Hearing\HearingDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Lead\Repositories\HearingRepository;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\User\Repositories\UserRepository;

class HearingController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected HearingRepository $hearingRepository,
        protected LeadRepository $leadRepository,
        protected UserRepository $userRepository,
    ) {}

    /**
     * Display a listing of hearings.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(HearingDataGrid::class)->process();
        }

        return view('admin::hearings.index');
    }

    /**
     * Show the form for creating a new hearing.
     */
    public function create(): View
    {
        $leads = $this->leadRepository->all(['id', 'title']);
        $users = $this->userRepository->all(['id', 'name']);

        return view('admin::hearings.create', compact('leads', 'users'));
    }

    /**
     * Store a newly created hearing.
     */
    public function store(): RedirectResponse|JsonResponse
    {
        $this->validate(request(), [
            'lead_id'      => 'required|integer|exists:leads,id',
            'hearing_type' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'court'        => 'required|string|max:255',
            'user_id'      => 'required|integer|exists:users,id',
        ]);

        $hearing = $this->hearingRepository->create(request()->only([
            'lead_id', 'hearing_type', 'scheduled_at', 'court',
            'court_room', 'judge_name', 'notes', 'status', 'user_id',
        ]));

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.hearings.index.create-success'),
                'data'    => $hearing,
            ]);
        }

        session()->flash('success', trans('admin::app.hearings.index.create-success'));

        return redirect()->route('admin.hearings.index');
    }

    /**
     * Show the specified hearing.
     */
    public function view(int $id): View
    {
        $hearing = $this->hearingRepository->findOrFail($id);

        return view('admin::hearings.view', compact('hearing'));
    }

    /**
     * Update the specified hearing.
     */
    public function update(int $id): RedirectResponse|JsonResponse
    {
        $this->validate(request(), [
            'hearing_type' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'court'        => 'required|string|max:255',
            'user_id'      => 'required|integer|exists:users,id',
        ]);

        $hearing = $this->hearingRepository->update(request()->only([
            'hearing_type', 'scheduled_at', 'court', 'court_room',
            'judge_name', 'notes', 'status', 'outcome', 'user_id',
        ]), $id);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.hearings.index.update-success'),
                'data'    => $hearing,
            ]);
        }

        session()->flash('success', trans('admin::app.hearings.index.update-success'));

        return redirect()->route('admin.hearings.index');
    }

    /**
     * Remove the specified hearing.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->hearingRepository->delete($id);

        return response()->json([
            'message' => trans('admin::app.hearings.index.delete-success'),
        ]);
    }
}
