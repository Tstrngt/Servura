<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('customer_services')
            ->where('provisioning_status', 'failed')
            ->where('suspension_reason', 'provisioning_failed')
            ->whereNull('external_username')
            ->whereNull('domain')
            ->update([
                'status' => 'active',
                'suspension_reason' => null,
                'suspended_at' => null,
                'provisioning_status' => 'not_required',
                'provisioning_error' => null,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
    }
};
