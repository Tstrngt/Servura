<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Quote;
use App\Models\BillableItem;
use App\Models\TransactionLog;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function invoices(Request $request)
    {
        $query = Invoice::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%")->orWhere('company', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest('invoice_date')->paginate(15);

        return view('admin.financial.invoices', compact('invoices'));
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with(['user', 'invoice']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Quarter filter
        if ($request->filled('quarter')) {
            $year = $request->input('year', date('Y'));
            $quarter = (int) $request->quarter;
            $startMonth = ($quarter - 1) * 3 + 1;
            $startDate = "{$year}-" . str_pad($startMonth, 2, '0', STR_PAD_LEFT) . "-01";
            $endDate = date('Y-m-t', strtotime("+2 months", strtotime($startDate)));
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        // Clone query for summary before pagination
        $summaryQuery = clone $query;
        $totalRevenue = (clone $summaryQuery)->where('type', 'inkomst')->where('status', 'voltooid')->sum('amount');
        $vatPercentage = 21;
        $netRevenue = round($totalRevenue / (1 + ($vatPercentage / 100)), 2);
        $totalVat = round($totalRevenue - $netRevenue, 2);
        $summary = [
            'total_revenue' => $totalRevenue,
            'net_revenue' => $netRevenue,
            'total_vat' => $totalVat,
        ];

        $transactions = $query->latest('transaction_date')->paginate(15);

        return view('admin.financial.transactions', compact('transactions', 'summary'));
    }

    public function billableItems(Request $request)
    {
        $query = BillableItem::with(['user', 'customerService.service']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'open');
        }

        $billableItems = $query->latest()->paginate(15);

        return view('admin.financial.billable-items', compact('billableItems'));
    }

    public function quotes(Request $request)
    {
        $query = Quote::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quote_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%")->orWhere('company', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'verlopen');
        }

        $quotes = $query->latest('updated_at')->paginate(15);

        return view('admin.financial.quotes', compact('quotes'));
    }

    public function logs(Request $request)
    {
        $query = TransactionLog::with(['user', 'performedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $logs = $query->latest()->paginate(25);

        return view('admin.financial.logs', compact('logs'));
    }
}
