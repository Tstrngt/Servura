<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_connections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider');
            $table->string('url');
            $table->string('username');
            $table->text('password');
            $table->string('shared_ip')->nullable();
            $table->boolean('verify_ssl')->default(true);
            $table->unsignedInteger('timeout')->default(20);
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_tested_at')->nullable();
            $table->string('last_test_status')->nullable();
            $table->text('last_test_message')->nullable();
            $table->timestamps();
            $table->index(['provider', 'is_active']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('server_connection_id')->nullable()->after('fulfillment_type')->constrained()->nullOnDelete();
            $table->string('provider_package')->nullable()->after('server_connection_id');
        });

        DB::table('services')->whereNotNull('directadmin_package')->update([
            'provider_package' => DB::raw('directadmin_package'),
        ]);
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropConstrainedForeignId('server_connection_id');
            $table->dropColumn('provider_package');
        });
        Schema::dropIfExists('server_connections');
    }
};
