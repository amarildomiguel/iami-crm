<?php

namespace Webkul\Admin\Http\Controllers\Document;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Document\DocumentDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Lead\Repositories\LegalDocumentRepository;
use Webkul\User\Repositories\UserRepository;

class DocumentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected LegalDocumentRepository $documentRepository,
        protected LeadRepository $leadRepository,
        protected PersonRepository $personRepository,
        protected UserRepository $userRepository,
    ) {}

    /**
     * Display a listing of documents.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(DocumentDataGrid::class)->process();
        }

        return view('admin::documents.index');
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(): View
    {
        $leads         = $this->leadRepository->all(['id', 'title']);
        $persons       = $this->personRepository->all(['id', 'name']);
        $users         = $this->userRepository->all(['id', 'name']);
        $documentTypes = LegalDocumentRepository::documentTypes();

        return view('admin::documents.create', compact('leads', 'persons', 'users', 'documentTypes'));
    }

    /**
     * Store a newly created document.
     */
    public function store(): RedirectResponse|JsonResponse
    {
        $this->validate(request(), [
            'title'         => 'required|string|max:255',
            'document_type' => 'required|string',
            'user_id'       => 'required|integer|exists:users,id',
            'file'          => 'nullable|file|max:20480',
        ]);

        $data = request()->only([
            'title', 'document_type', 'description', 'file_type',
            'lead_id', 'person_id', 'user_id', 'status',
            'due_date', 'filing_date', 'court_reference',
        ]);

        if (request()->hasFile('file')) {
            $data['file_path'] = request()->file('file')->store('legal-documents');
            $data['file_type'] = request()->file('file')->getClientOriginalExtension();
        } else {
            $data['file_path'] = '';
        }

        $document = $this->documentRepository->create($data);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.documents.index.create-success'),
                'data'    => $document,
            ]);
        }

        session()->flash('success', trans('admin::app.documents.index.create-success'));

        return redirect()->route('admin.documents.index');
    }

    /**
     * Show the specified document.
     */
    public function view(int $id): View
    {
        $document = $this->documentRepository->findOrFail($id);

        return view('admin::documents.view', compact('document'));
    }

    /**
     * Update the specified document.
     */
    public function update(int $id): RedirectResponse|JsonResponse
    {
        $this->validate(request(), [
            'title'         => 'required|string|max:255',
            'document_type' => 'required|string',
            'user_id'       => 'required|integer|exists:users,id',
        ]);

        $data = request()->only([
            'title', 'document_type', 'description', 'file_type',
            'lead_id', 'person_id', 'user_id', 'status',
            'due_date', 'filing_date', 'court_reference',
        ]);

        if (request()->hasFile('file')) {
            $data['file_path'] = request()->file('file')->store('legal-documents');
            $data['file_type'] = request()->file('file')->getClientOriginalExtension();
        }

        $document = $this->documentRepository->update($data, $id);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.documents.index.update-success'),
                'data'    => $document,
            ]);
        }

        session()->flash('success', trans('admin::app.documents.index.update-success'));

        return redirect()->route('admin.documents.index');
    }

    /**
     * Download the document file.
     */
    public function download(int $id)
    {
        $document = $this->documentRepository->findOrFail($id);

        if (! $document->file_path || ! Storage::exists($document->file_path)) {
            abort(404);
        }

        return Storage::download($document->file_path, $document->title);
    }

    /**
     * Remove the specified document.
     */
    public function destroy(int $id): JsonResponse
    {
        $document = $this->documentRepository->findOrFail($id);

        if ($document->file_path) {
            Storage::delete($document->file_path);
        }

        $this->documentRepository->delete($id);

        return response()->json([
            'message' => trans('admin::app.documents.index.delete-success'),
        ]);
    }
}
