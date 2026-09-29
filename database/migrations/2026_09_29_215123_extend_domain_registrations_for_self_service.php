<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domain_registrations', function (Blueprint $table) {
            $table->foreignId('hosted_customer_service_id')->nullable()->after('customer_service_id')->constrained('customer_services')->nullOnDelete();
            $table->json('current_nameservers')->nullable()->after('error_message')->comment('Snapshot of nameservers from the provider');
            $table->boolean('is_dns_managed_by_servura')->default(false)->after('current_nameservers');
            $table->boolean('registrar_lock')->default(false)->after('is_dns_managed_by_servura');
            $table->boolean('owner_change_pending')->default(false)->after('registrar_lock');
            $table->text('owner_change_token')->nullable()->after('owner_change_pending');
            $table->timestamp('provider_info_synced_at')->nullable()->after('owner_change_token');
        });
    }

    public function down(): void
    {
        Schema::table('domain_registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hosted_customer_service_id');
            $table->dropColumn([
                'current_nameservers',
                'is_dns_managed_by_servura',
                'registrar_lock',
                'owner_change_pending',
                'owner_change_token',
                'provider_info_synced_at',
            ]);
        });
    }
};
