<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCancellationRequest;
use App\Services\CancellationService;
use Illuminate\Http\Request;

class ServiceCancellationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $cancellations = ServiceCancellationRequest::with(['user', 'customerService.service', 'ticket'])
            ->latest()
            ->paginate(20);

        return view('admin.service-cancellations.index', compact('cancellations'));
    }

    public function approve(Request $request, ServiceCancellationRequest $cancellation, CancellationService $service)
    {
        abort_unless($cancellation->status === 'pending', 422);
        $validated = $request->validate([
            'effective_at' => ['required', 'date'],
            'estimated_usage_cost' => ['required', 'numeric', 'min:0'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $service->approve($cancellation, $validated);

        return back()->with('success', 'Het opzegverzoek is goedgekeurd.');
    }

    public function reject(Request $request, ServiceCancellationRequest $cancellation, CancellationService $service)
    {
        abort_unless($cancellation->status === 'pending', 422);
        $validated = $request->validate(['admin_notes' => ['required', 'string', 'max:2000']]);
        $service->reject($cancellation, $validated['admin_notes']);

        return back()->with('success', 'Het opzegverzoek is afgewezen.');
    }
}
