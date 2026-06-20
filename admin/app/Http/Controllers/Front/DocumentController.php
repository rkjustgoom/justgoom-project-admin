<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\DocumentRequest;
use App\Models\Document;
use App\Services\Front\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(private DocumentService $documentService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        return view('front.users.documents', [
            'documents' => $this->documentService->listForUser($user),
            'stats' => $this->documentService->statsForUser($user),
        ]);
    }

    public function create()
    {
        return view('front.users.document-add');
    }

    public function store(DocumentRequest $request)
    {
        $this->documentService->store($request->user(), $request->validated());

        return redirect()
            ->route('front.users.documents')
            ->with('success', 'Document uploaded successfully.');
    }

    public function edit(Request $request, Document $document)
    {
        abort_unless($this->documentService->belongsToUser($document, $request->user()), 404);

        return view('front.users.document-edit', compact('document'));
    }

    public function update(DocumentRequest $request, Document $document)
    {
        abort_unless($this->documentService->belongsToUser($document, $request->user()), 404);

        $this->documentService->update($request->user(), $document, $request->validated());

        return redirect()
            ->route('front.users.documents')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Request $request, Document $document)
    {
        abort_unless($this->documentService->belongsToUser($document, $request->user()), 404);

        $this->documentService->delete($request->user(), $document);

        return redirect()
            ->route('front.users.documents')
            ->with('success', 'Document removed successfully.');
    }
}
