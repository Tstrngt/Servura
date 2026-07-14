<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('customer');
    }

    /**
     * Display a listing of the customer's tickets.
     */
    public function index()
    {
        $tickets = Auth::user()->tickets()
            ->with(['assignedTo'])
            ->latest()
            ->paginate(10);

        return view('customer.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        return view('customer.tickets.create');
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,billing,general,feature_request,bug_report',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip',
        ], [
            'title.required' => 'Titel is verplicht',
            'title.max' => 'Titel mag maximaal 255 tekens bevatten',
            'description.required' => 'Beschrijving is verplicht',
            'description.min' => 'Beschrijving moet minimaal 10 tekens bevatten',
            'priority.required' => 'Prioriteit is verplicht',
            'category.required' => 'Categorie is verplicht',
            'attachments.*.max' => 'Bijlagen mogen maximaal 10MB zijn',
            'attachments.*.mimes' => 'Alleen jpg, png, gif, pdf, doc, docx, txt en zip bestanden zijn toegestaan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');
                
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'uploaded_by' => Auth::id(),
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('customer.tickets.show', $ticket)
            ->with('success', 'Uw ticket is succesvol aangemaakt. Wij nemen zo snel mogelijk contact met u op.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        // Check if user owns this ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load([
            'replies' => fn ($query) => $query->public()->with('user'),
            'attachments.uploadedBy',
        ]);

        return view('customer.tickets.show', compact('ticket'));
    }

    /**
     * Store a newly created reply in storage.
     */
    public function reply(Request $request, Ticket $ticket)
    {
        // Check if user owns this ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if ticket can be replied to
        if (!$ticket->canBeReplied()) {
            return redirect()->back()
                ->with('error', 'Dit ticket kan niet meer beantwoord worden.');
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:5',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip',
        ], [
            'message.required' => 'Bericht is verplicht',
            'message.min' => 'Bericht moet minimaal 5 tekens bevatten',
            'attachments.*.max' => 'Bijlagen mogen maximaal 10MB zijn',
            'attachments.*.mimes' => 'Alleen jpg, png, gif, pdf, doc, docx, txt en zip bestanden zijn toegestaan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create reply
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_internal' => false,
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');
                
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'uploaded_by' => Auth::id(),
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Update ticket status and last reply
        if ($ticket->isWaitingForCustomer()) {
            $ticket->update(['status' => 'open']);
        }
        
        $ticket->updateLastReply();

        return redirect()->back()
            ->with('success', 'Uw reactie is succesvol verzonden.');
    }

    /**
     * Close the specified ticket.
     */
    public function close(Ticket $ticket)
    {
        // Check if user owns this ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$ticket->canBeClosed()) {
            return redirect()->back()
                ->with('error', 'Dit ticket kan niet worden gesloten.');
        }

        $ticket->markAsClosed();

        return redirect()->route('customer.tickets.index')
            ->with('success', 'Ticket is succesvol gesloten.');
    }

    /**
     * Download attachment.
     */
    public function downloadAttachment(TicketAttachment $attachment)
    {
        // Check if user has access to this ticket
        if ($attachment->ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $filePath = storage_path('app/public/' . $attachment->file_path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $attachment->original_name);
    }

    /**
     * Preview attachment (for images).
     */
    public function previewAttachment(TicketAttachment $attachment)
    {
        // Check if user has access to this ticket
        if ($attachment->ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$attachment->isImage()) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $attachment->file_path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    }
}
