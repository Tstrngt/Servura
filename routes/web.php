<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Admin\QuoteController as AdminQuoteController;
use App\Http\Controllers\Customer\InvoiceController as CustomerInvoiceController;
use App\Http\Controllers\Customer\QuoteController as CustomerQuoteController;
use App\Http\Controllers\MollieWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Over ons pagina
Route::get('/over-ons', [AboutController::class, 'index'])->name('about');

// Diensten
Route::get('/diensten', [ServiceController::class, 'index'])->name('services.index');
Route::get('/diensten/{service}', [ServiceController::class, 'show'])->name('services.show');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('/wachtwoord-vergeten', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/wachtwoord-vergeten', [PasswordResetLinkController::class, 'store'])->name('password.email');
    
    Route::get('/wachtwoord-reset/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/wachtwoord-reset', [NewPasswordController::class, 'store'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Customer routes
    Route::middleware('customer')->prefix('klant')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
        
        // Tickets
        Route::get('/tickets', [App\Http\Controllers\Customer\TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/aanmaken', [App\Http\Controllers\Customer\TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [App\Http\Controllers\Customer\TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [App\Http\Controllers\Customer\TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reageren', [App\Http\Controllers\Customer\TicketController::class, 'reply'])->name('tickets.reply');
        Route::post('/tickets/{ticket}/sluiten', [App\Http\Controllers\Customer\TicketController::class, 'close'])->name('tickets.close');
        Route::get('/tickets/attachments/{attachment}/download', [App\Http\Controllers\Customer\TicketController::class, 'downloadAttachment'])->name('tickets.attachments.download');
        Route::get('/tickets/attachments/{attachment}/preview', [App\Http\Controllers\Customer\TicketController::class, 'previewAttachment'])->name('tickets.attachments.preview');

        // Invoices
        Route::get('/facturen', [CustomerInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/facturen/{invoice}', [CustomerInvoiceController::class, 'show'])->name('invoices.show');
        Route::post('/facturen/{invoice}/betalen', [CustomerInvoiceController::class, 'pay'])->name('invoices.pay');
        Route::get('/facturen/{invoice}/betaling-retour', [CustomerInvoiceController::class, 'paymentReturn'])->name('invoices.payment.return');

        // Quotes
        Route::get('/offertes', [CustomerQuoteController::class, 'index'])->name('quotes.index');
        Route::get('/offertes/{quote}', [CustomerQuoteController::class, 'show'])->name('quotes.show');
        Route::post('/offertes/{quote}/akkoord', [CustomerQuoteController::class, 'accept'])->name('quotes.accept');
        Route::post('/offertes/{quote}/afwijzen', [CustomerQuoteController::class, 'reject'])->name('quotes.reject');
    });
    
    // Notifications
    Route::get('/notifications/unread', [App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/attachments/{attachment}/download', [AdminTicketController::class, 'downloadAttachment'])->name('tickets.attachments.download');
        Route::get('/tickets/attachments/{attachment}/preview', [AdminTicketController::class, 'previewAttachment'])->name('tickets.attachments.preview');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
        Route::patch('/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('tickets.update');
        Route::post('/tickets/{ticket}/assign', [AdminTicketController::class, 'assign'])->name('tickets.assign');
        Route::post('/tickets/{ticket}/close', [AdminTicketController::class, 'close'])->name('tickets.close');
        Route::post('/tickets/{ticket}/reopen', [AdminTicketController::class, 'reopen'])->name('tickets.reopen');

        // Customers
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [AdminCustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [AdminCustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
        Route::get('/customers/{customer}/edit', [AdminCustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [AdminCustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [AdminCustomerController::class, 'destroy'])->name('customers.destroy');
        Route::post('/customers/{customer}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
        Route::post('/customers/{customer}/reset-password', [AdminCustomerController::class, 'resetPassword'])->name('customers.reset-password');
        Route::get('/customers/{customer}/services', [AdminCustomerController::class, 'services'])->name('customers.services');
        Route::post('/customers/{customer}/services', [AdminCustomerController::class, 'storeService'])->name('customers.services.store');
        Route::post('/customers/{customer}/services/{service}/cancel', [AdminCustomerController::class, 'cancelService'])->name('customers.services.cancel');
        Route::get('/customers/{customer}/tickets', [AdminCustomerController::class, 'tickets'])->name('customers.tickets');

        // Services (bind by id, not slug)
        Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
        Route::get('/services/create', [AdminServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{service:id}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{service:id}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service:id}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

        // Financial
        Route::prefix('financial')->name('financial.')->group(function () {
            Route::get('/invoices', [FinancialController::class, 'invoices'])->name('invoices');
            Route::get('/invoices/create', [AdminInvoiceController::class, 'create'])->name('invoices.create');
            Route::post('/invoices', [AdminInvoiceController::class, 'store'])->name('invoices.store');
            Route::get('/invoices/{invoice}', [AdminInvoiceController::class, 'show'])->name('invoices.show');
            Route::post('/invoices/{invoice}/mark-sent', [AdminInvoiceController::class, 'markSent'])->name('invoices.mark-sent');
            Route::post('/invoices/{invoice}/mark-paid', [AdminInvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
            Route::get('/transactions', [FinancialController::class, 'transactions'])->name('transactions');
            Route::get('/billable-items', [FinancialController::class, 'billableItems'])->name('billable-items');
            Route::get('/quotes', [FinancialController::class, 'quotes'])->name('quotes');
            Route::get('/quotes/create', [AdminQuoteController::class, 'create'])->name('quotes.create');
            Route::post('/quotes', [AdminQuoteController::class, 'store'])->name('quotes.store');
            Route::get('/quotes/{quote}', [AdminQuoteController::class, 'show'])->name('quotes.show');
            Route::post('/quotes/{quote}/mark-sent', [AdminQuoteController::class, 'markSent'])->name('quotes.mark-sent');
            Route::get('/logs', [FinancialController::class, 'logs'])->name('logs');
        });
    });
});

// Mollie webhook (no auth, POST only)
Route::post('/mollie/webhook', MollieWebhookController::class)->name('mollie.webhook');
