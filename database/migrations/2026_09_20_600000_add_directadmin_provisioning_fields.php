<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('directadmin_package')->nullable()->after('fulfillment_type');
        });

        Schema::table('customer_services', function (Blueprint $table) {
            $table->string('domain')->nullable()->after('service_price_id');
            $table->string('external_username')->nullable()->unique()->after('domain');
            $table->text('external_password')->nullable()->after('external_username');
            $table->string('provisioning_status')->default('not_required')->after('external_password');
            $table->text('provisioning_error')->nullable()->after('provisioning_status');
            $table->timestamp('provisioned_at')->nullable()->after('provisioning_error');
            $table->timestamp('external_suspended_at')->nullable()->after('provisioned_at');
            $table->index('provisioning_status');
        });
    }

    public function down(): void
    {
        Schema::table('customer_services', function (Blueprint $table) {
            $table->dropIndex(['provisioning_status']);
            $table->dropColumn([
                'domain', 'external_username', 'external_password', 'provisioning_status',
                'provisioning_error', 'provisioned_at', 'external_suspended_at',
            ]);
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('directadmin_package');
        });
    }
};
