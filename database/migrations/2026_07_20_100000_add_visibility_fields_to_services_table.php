<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('show_on_homepage')->default(false)->after('is_active');
            $table->boolean('show_on_services_page')->default(true)->after('show_on_homepage');
        });

        // Set existing popular services to show on homepage
        \DB::table('services')->where('is_popular', true)->update(['show_on_homepage' => true]);
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['show_on_homepage', 'show_on_services_page']);
        });
    }
};
