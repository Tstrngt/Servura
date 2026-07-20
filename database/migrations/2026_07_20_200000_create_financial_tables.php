<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Facturen
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('vat_percentage', 5, 2)->default(21.00);
            $table->string('status')->default('concept'); // concept, verzonden, betaald, vervallen, gecrediteerd
            $table->text('notes')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('invoice_date');
            $table->index('due_date');
        });

        // Factuurregels
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->foreignId('customer_service_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Transacties
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->string('type'); // inkomst, uitgave, creditering
            $table->string('payment_method')->nullable(); // bank, ideal, contant, overig
            $table->string('status')->default('voltooid'); // in_afwachting, voltooid, mislukt, terugbetaald
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->string('reference')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index('transaction_date');
        });

        // Offertes
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('quote_date');
            $table->date('valid_until');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('vat_percentage', 5, 2)->default(21.00);
            $table->string('status')->default('concept'); // concept, verzonden, geaccepteerd, afgewezen, verlopen
            $table->text('notes')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->foreignId('converted_invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        // Offerteregels
        Schema::create('quote_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Te factureren items
        Schema::create('billable_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_service_id')->nullable()->constrained()->onDelete('set null');
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('open'); // open, gefactureerd
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        // Transactielogboek
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('loggable_type'); // Invoice, Transaction, Quote, etc.
            $table->unsignedBigInteger('loggable_id');
            $table->string('action'); // aangemaakt, bijgewerkt, verzonden, betaald, etc.
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['loggable_type', 'loggable_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
        Schema::dropIfExists('billable_items');
        Schema::dropIfExists('quote_lines');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
    }
};
