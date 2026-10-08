<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_design_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('version');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->json('settings');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique('version');
            $table->index(['status', 'published_at']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('invoice_design_version_id')->nullable()->after('customer_service_id')->constrained()->nullOnDelete();
            $table->foreignId('credited_invoice_id')->nullable()->after('invoice_design_version_id')->constrained('invoices')->nullOnDelete();
            $table->enum('document_type', ['invoice', 'credit'])->default('invoice')->after('invoice_number');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('subtotal');
            $table->json('issuer_snapshot')->nullable();
            $table->json('customer_snapshot')->nullable();
        });

        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->decimal('vat_percentage', 5, 2)->nullable()->after('total');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('vat_percentage');
            $table->date('period_start')->nullable()->after('discount_amount');
            $table->date('period_end')->nullable()->after('period_start');
            $table->string('service_reference')->nullable()->after('period_end');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->dropColumn(['vat_percentage', 'discount_amount', 'period_start', 'period_end', 'service_reference']);
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invoice_design_version_id');
            $table->dropConstrainedForeignId('credited_invoice_id');
            $table->dropColumn(['document_type', 'discount_amount', 'issuer_snapshot', 'customer_snapshot']);
        });
        Schema::dropIfExists('invoice_design_versions');
    }
};
