<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionLog;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function edit(Transaction $transaction)
    {
        $transaction->load('user', 'invoice');

        return view('admin.financial.transactions-edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:' . implode(',', array_keys(Transaction::TYPES)),
            'payment_method' => 'required|in:' . implode(',', array_keys(Transaction::PAYMENT_METHODS)),
            'status' => 'required|in:' . implode(',', array_keys(Transaction::STATUSES)),
            'description' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
        ]);

        $transaction->update($validated);

        TransactionLog::create([
            'user_id' => $transaction->user_id,
            'loggable_type' => Transaction::class,
            'loggable_id' => $transaction->id,
            'action' => 'bijgewerkt',
            'description' => "Transactie {$transaction->transaction_number} bijgewerkt",
            'performed_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.financial.transactions')
            ->with('success', "Transactie {$transaction->transaction_number} is bijgewerkt.");
    }

    public function destroy(Transaction $transaction)
    {
        $number = $transaction->transaction_number;
        $userId = $transaction->user_id;

        TransactionLog::create([
            'user_id' => $userId,
            'loggable_type' => Transaction::class,
            'loggable_id' => $transaction->id,
            'action' => 'verwijderd',
            'description' => "Transactie {$number} verwijderd",
            'performed_by' => auth()->id(),
        ]);

        $transaction->delete();

        return redirect()
            ->route('admin.financial.transactions')
            ->with('success', "Transactie {$number} is verwijderd.");
    }
}
