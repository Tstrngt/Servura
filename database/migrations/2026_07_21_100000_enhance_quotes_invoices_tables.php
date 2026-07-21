<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add proposal, client_notes, internal_notes to quotes
        Schema::table('quotes', function (Blueprint $table) {
            $table->text('proposal')->nullable()->after('notes');
            $table->text('client_notes')->nullable()->after('proposal');
            $table->text('internal_notes')->nullable()->after('client_notes');
        });

        // Add discount to quote_lines
        Schema::table('quote_lines', function (Blueprint $table) {
            $table->decimal('discount', 10, 2)->default(0)->after('unit_price');
        });

        // Add internal_notes to invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->text('internal_notes')->nullable()->after('notes');
            $table->foreignId('quote_id')->nullable()->after('payment_url')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['proposal', 'client_notes', 'internal_notes']);
        });
        Schema::table('quote_lines', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['quote_id']);
            $table->dropColumn(['internal_notes', 'quote_id']);
        });
    }
};
