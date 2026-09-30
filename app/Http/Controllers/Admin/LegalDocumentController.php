<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\LegalDocument;
use App\Models\LegalDocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LegalDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $this->authorize('legal.view');

        $documents = LegalDocument::orderBy('title')->get();

        return view('admin.settings.legal-documents.index', compact('documents'));
    }

    public function edit(LegalDocument $document)
    {
        $this->authorize('legal.edit');

        $document->load(['versions' => fn ($q) => $q->latest()->limit(10), 'publishedBy']);

        return view('admin.settings.legal-documents.edit', compact('document'));
    }

    public function update(Request $request, LegalDocument $document)
    {
        $this->authorize('legal.edit');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string|max:500000',
            'effective_date' => 'nullable|date',
        ]);

        $before = $document->only(['title', 'content', 'effective_date']);

        $document->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'effective_date' => $validated['effective_date'] ?? null,
        ]);

        AuditLog::record(
            'legal_document.updated',
            $document,
            $before,
            $document->only(['title', 'content', 'effective_date']),
            'Concept bijgewerkt'
        );

        return back()->with('success', 'Document is opgeslagen als concept.');
    }

    public function preview(Request $request, LegalDocument $document)
    {
        $this->authorize('legal.edit');

        $preview = clone $document;
        $preview->title = $request->input('title', $document->title);
        $preview->content = $request->input('content', $document->content);
        $preview->version = $request->input('version', $document->version);

        return view('legal.show', ['document' => $preview]);
    }

    public function publish(Request $request, LegalDocument $document)
    {
        $this->authorize('legal.publish');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string|max:500000',
            'version' => 'required|string|max:20',
            'effective_date' => 'nullable|date',
        ]);

        $before = $document->only(['title', 'content', 'version', 'effective_date', 'status']);

        DB::transaction(function () use ($document, $validated) {
            // Archive current published state before overwriting.
            if ($document->isPublished()) {
                $document->versions()->create([
                    'version' => $document->version,
                    'content' => $document->content,
                    'effective_date' => $document->effective_date,
                    'published_by' => $document->published_by,
                    'published_at' => $document->published_at,
                ]);
            }

            $document->update([
                'title' => $validated['title'],
                'content' => $validated['content'] ?? null,
                'version' => $validated['version'],
                'effective_date' => $validated['effective_date'] ?? null,
                'status' => LegalDocument::STATUS_PUBLISHED,
                'published_at' => now(),
                'published_by' => auth()->id(),
            ]);
        });

        AuditLog::record(
            'legal_document.published',
            $document,
            $before,
            $document->only(['title', 'content', 'version', 'effective_date', 'status']),
            "Versie {$validated['version']} gepubliceerd"
        );

        return redirect()->route('admin.settings.legal-documents.index')
            ->with('success', "Versie {$validated['version']} van {$document->title} is gepubliceerd.");
    }
}
