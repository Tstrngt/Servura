<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $tickets = Ticket::query()
            ->with(['user', 'assignedTo'])
            ->when($request->input('status') === 'overdue', fn ($query) => $query->overdue())
            ->when($request->filled('status') && $request->input('status') !== 'overdue', fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->string('priority')->toString()))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('company', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load([
            'user',
            'assignedTo',
            'replies.user',
            'attachments.uploadedBy',
        ]);

        $staff = User::staff()->orderBy('name')->get();

        return view('admin.tickets.show', compact('ticket', 'staff'));
    }

    /**
     * Store a newly created reply in storage.
     */
    public function reply(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:5',
            'is_internal' => 'boolean',
            'status_after_reply' => 'nullable|in:open,in_progress,waiting_for_customer,resolved',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip',
        ], [
            'message.required' => 'Bericht is verplicht',
            'message.min' => 'Bericht moet minimaal 5 tekens bevatten',
            'status_after_reply.in' => 'Ongeldige status gekozen',
            'attachments.*.max' => 'Bijlagen mogen maximaal 10MB zijn',
            'attachments.*.mimes' => 'Alleen jpg, png, gif, pdf, doc, docx, txt en zip bestanden zijn toegestaan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $isInternal = $request->boolean('is_internal');

        // Public replies are not allowed on closed/resolved tickets
        if (!$isInternal && !$ticket->canBeReplied()) {
            return redirect()->back()
                ->with('error', 'Dit ticket kan geen publieke reactie meer ontvangen.');
        }

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_internal' => $isInternal,
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

        // Update status if requested and reply is public
        $newStatus = $request->input('status_after_reply');
        if (!$isInternal && $newStatus && in_array($newStatus, ['open', 'in_progress', 'waiting_for_customer', 'resolved'])) {
            $updateData = ['status' => $newStatus];

            if ($newStatus === 'resolved' && !$ticket->resolved_at) {
                $updateData['resolved_at'] = now();
            } elseif ($newStatus !== 'resolved') {
                $updateData['resolved_at'] = null;
            }

            $ticket->update($updateData);
        }

        if (!$isInternal) {
            $ticket->updateLastReply();

            Notification::notify(
                $ticket->user,
                'ticket_reply',
                'Nieuwe reactie op uw ticket',
                Auth::user()->name . ' heeft gereageerd op ticket ' . $ticket->ticket_number . '.',
                route('customer.tickets.show', $ticket)
            );
        }

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', $isInternal
                ? 'Interne notitie toegevoegd.'
                : 'Reactie verzonden en zichtbaar voor de klant.');
    }

    /**
     * Update ticket priority and/or category.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,billing,general,feature_request,bug_report',
        ], [
            'priority.required' => 'Prioriteit is verplicht',
            'category.required' => 'Categorie is verplicht',
        ]);

        $ticket->update($validated);

        Notification::notify(
            $ticket->user,
            'ticket_updated',
            'Ticket bijgewerkt',
            'Uw ticket ' . $ticket->ticket_number . ' is bijgewerkt.',
            route('customer.tickets.show', $ticket)
        );

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket bijgewerkt.');
    }

    /**
     * Assign ticket to a staff member.
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ], [
            'assigned_to.exists' => 'Geselecteerde medewerker bestaat niet.',
        ]);

        $previousAssignee = $ticket->assigned_to;

        $ticket->update([
            'assigned_to' => $validated['assigned_to'] ?: null,
        ]);

        if ($validated['assigned_to'] && (int) $validated['assigned_to'] !== (int) $previousAssignee) {
            Notification::notify(
                User::find($validated['assigned_to']),
                'ticket_assigned',
                'Ticket toegewezen',
                'Ticket ' . $ticket->ticket_number . ' is aan u toegewezen.',
                route('admin.tickets.show', $ticket)
            );
        }

        $message = $validated['assigned_to']
            ? 'Ticket toegewezen aan ' . User::find($validated['assigned_to'])->name . '.'
            : 'Ticket niet meer toegewezen.';

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', $message);
    }

    /**
     * Close the specified ticket.
     */
    public function close(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'resolution_notes' => 'nullable|string|max:5000',
        ]);

        if (!$ticket->canBeClosed()) {
            return redirect()->route('admin.tickets.show', $ticket)
                ->with('error', 'Dit ticket is al gesloten.');
        }

        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
            'resolution_notes' => $validated['resolution_notes'] ?? $ticket->resolution_notes,
        ]);

        Notification::notify(
            $ticket->user,
            'ticket_closed',
            'Ticket gesloten',
            'Uw ticket ' . $ticket->ticket_number . ' is gesloten.',
            route('customer.tickets.show', $ticket)
        );

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket gesloten.');
    }

    /**
     * Reopen the specified ticket.
     */
    public function reopen(Ticket $ticket)
    {
        $ticket->reopen();

        Notification::notify(
            $ticket->user,
            'ticket_reopened',
            'Ticket heropend',
            'Uw ticket ' . $ticket->ticket_number . ' is heropend.',
            route('customer.tickets.show', $ticket)
        );

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket heropend.');
    }

    /**
     * Download attachment.
     */
    public function downloadAttachment(TicketAttachment $attachment)
    {
        $filePath = storage_path('app/public/' . $attachment->file_path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $attachment->original_name);
    }

    /**
     * Delete the specified ticket.
     */
    public function destroy(Ticket $ticket)
    {
        $ticketNumber = $ticket->ticket_number;

        // Delete attachments from storage
        foreach ($ticket->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $ticket->delete();

        return redirect()->route('admin.tickets.index')
            ->with('success', "Ticket {$ticketNumber} is verwijderd.");
    }

    /**
     * Preview attachment (for images).
     */
    public function previewAttachment(TicketAttachment $attachment)
    {
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
