<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerDataResetService
{
    /**
     * Hard-delete a customer account and all related data
     * (services, tickets, invoices, quotes, orders, transactions,
     * payment batches, notifications, ...).
     */
    public function purge(User $customer): void
    {
        DB::transaction(function () use ($customer) {
            $invoiceIds = $customer->invoices()->pluck('id');
            $batchIds = $customer->paymentBatches()->pluck('id');

            // payment_batch_items restrict invoice deletion, so remove them first
            DB::table('payment_batch_items')
                ->whereIn('invoice_id', $invoiceIds)
                ->orWhereIn('payment_batch_id', $batchIds)
                ->delete();

            $customer->paymentBatches()->delete();
            $customer->invoices()->delete();

            // Remaining relations cascade via foreign keys on the user
            $customer->delete();
        });
    }

    /**
     * Delete every customer account including all related data.
     *
     * @return int number of deleted accounts
     */
    public function purgeAllCustomers(): int
    {
        $count = 0;

        User::where('role', 'customer')->chunkById(50, function ($customers) use (&$count) {
            foreach ($customers as $customer) {
                $this->purge($customer);
                $count++;
            }
        });

        return $count;
    }
}
