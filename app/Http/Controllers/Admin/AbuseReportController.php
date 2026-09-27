<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbuseReport;
use Illuminate\Http\Request;

class AbuseReportController extends Controller
{
    public function index()
    {
        $reports = AbuseReport::latest()->paginate(25);

        return view('admin.abuse-reports.index', compact('reports'));
    }

    public function show(AbuseReport $abuseReport)
    {
        return view('admin.abuse-reports.show', compact('abuseReport'));
    }

    public function updateStatus(Request $request, AbuseReport $abuseReport)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,reviewing,action_taken,rejected,closed',
        ]);

        $abuseReport->update(['status' => $validated['status']]);

        return back()->with('success', 'Status bijgewerkt.');
    }
}
