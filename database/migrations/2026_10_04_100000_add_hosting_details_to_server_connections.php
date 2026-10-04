<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('server_connections', function (Blueprint $table) {
            $table->string('nameserver_1')->nullable()->after('shared_ip');
            $table->string('nameserver_2')->nullable()->after('nameserver_1');
            $table->string('nameserver_3')->nullable()->after('nameserver_2');
            $table->string('ftp_host')->nullable()->after('nameserver_3');
        });
    }

    public function down(): void
    {
        Schema::table('server_connections', function (Blueprint $table) {
            $table->dropColumn(['nameserver_1', 'nameserver_2', 'nameserver_3', 'ftp_host']);
        });
    }
};
