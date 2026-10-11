<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('openprovider_sandbox_handle')->nullable()->after('openprovider_handle')->index();
            $table->string('openprovider_production_handle')->nullable()->after('openprovider_sandbox_handle')->index();
        });

        DB::table('users')->whereNotNull('openprovider_handle')->update([
            'openprovider_sandbox_handle' => DB::raw('openprovider_handle'),
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['openprovider_sandbox_handle', 'openprovider_production_handle']);
        });
    }
};
