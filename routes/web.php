<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AbuseReportController;
use App\Http\Controllers\Admin\BillingSettingsController as AdminBillingSettingsController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DomainRegistrationController as AdminDomainRegistrationController;
use App\Http\Controllers\Admin\DomainTldController as AdminDomainTldController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Admin\InvoiceDesignController as AdminInvoiceDesignController;
use App\Http\Controllers\Admin\QuoteController as AdminQuoteController;
use App\Http\Controllers\Admin\ServerConnectionController as AdminServerConnectionController;
use App\Http\Controllers\Admin\ServiceCancellationController;
use App\Http\Controllers\Admin\ServiceCategoryController as AdminServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\TransIpSettingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\DomainController as CustomerDomainController;
use App\Http\Controllers\Customer\InvoiceController as CustomerInvoiceController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\QuoteController as CustomerQuoteController;
use App\Http\Controllers\Customer\TicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\MollieWebhookController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuoteBuilderController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
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
Route::get('/bestellen/{service}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::get('/bestellen/{service}/inloggen', [CheckoutController::class, 'loginPrompt'])->name('checkout.login');
Route::post('/bestellen/{service}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/bestellen/cart/hosting', [CheckoutController::class, 'addHosting'])->name('checkout.cart.add-hosting');
Route::post('/bestellen/cart/domain', [CheckoutController::class, 'setDomain'])->name('checkout.cart.set-domain');
Route::post('/bestellen/cart/price', [CheckoutController::class, 'setPrice'])->name('checkout.cart.set-price');
Route::post('/bestellen/cart/remove', [CheckoutController::class, 'removeItem'])->name('checkout.cart.remove');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');

// Offerte samenstellen (niet in hoofdnavigatie)
Route::get('/offerte-samenstellen', [QuoteBuilderController::class, 'index'])->name('quote.builder');
Route::get('/offerte-samenstellen/inloggen', [QuoteBuilderController::class, 'loginPrompt'])->name('quote.builder.login');
Route::post('/offerte-samenstellen', [QuoteBuilderController::class, 'store'])->middleware('throttle:10,1')->name('quote.builder.store');

// Nieuwsbrief
Route::post('/nieuwsbrief', [NewsletterController::class, 'subscribe'])->middleware('throttle:10,1')->name('newsletter.subscribe');

// Domeinchecker
Route::get('/domeinchecker', function () {
    return view('domains.checker');
})->name('domains.checker');
Route::get('/nieuwsbrief/afmelden/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Taalwissel (voorbereiding meertaligheid)
Route::post('/taal', function (Request $request) {
    $locale = $request->input('locale', 'nl');
    if (in_array($locale, ['nl', 'en', 'de', 'fr'], true)) {
        $request->session()->put('locale', $locale);
    }

    return back();
})->name('locale.switch');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/wachtwoord-vergeten', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/wachtwoord-vergeten', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/wachtwoord-reset/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/wachtwoord-reset', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::get('/email/verificatie/{token}', [VerifyEmailController::class, 'verify'])->name('verification.verify');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/email/verificatie/opnieuw', [VerifyEmailController::class, 'resend'])->name('verification.send');

    // Customer routes
    Route::middleware('customer')->prefix('klant')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
        Route::get('/profiel', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profiel', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profiel/logo', [ProfileController::class, 'updateLogo'])->name('profile.logo');
        Route::put('/profiel/wachtwoord', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Services overview (moved from dashboard)
        Route::get('/diensten', [App\Http\Controllers\Customer\ServiceController::class, 'index'])->name('services.index');
        Route::get('/diensten/{customerService}', [App\Http\Controllers\Customer\ServiceController::class, 'show'])->name('services.show');
        Route::post('/diensten/{customerService}/opzeggen', [App\Http\Controllers\Customer\ServiceController::class, 'cancel'])->name('services.cancel');
        Route::post('/diensten/{customerService}/overdragen', [App\Http\Controllers\Customer\ServiceController::class, 'transfer'])->name('services.transfer');
        Route::post('/diensten/{customerService}/upgrade', [App\Http\Controllers\Customer\ServiceController::class, 'upgrade'])->name('services.upgrade');
        Route::post('/diensten/{customerService}/reset-wachtwoord', [App\Http\Controllers\Customer\ServiceController::class, 'resetPassword'])->name('services.reset-password');
        Route::match(['get', 'post'], '/diensten/{customerService}/directadmin-login', [App\Http\Controllers\Customer\ServiceController::class, 'directAdminLogin'])->name('services.directadmin-login');

        // Domeinen
        Route::get('/domeinen', [CustomerDomainController::class, 'index'])->name('domains.index');
        Route::get('/domeinen/{domain}', [CustomerDomainController::class, 'show'])->name('domains.show');
        Route::post('/domeinen/{domain}/sync', [CustomerDomainController::class, 'sync'])->name('domains.sync');
        Route::post('/domeinen/{domain}/nameservers', [CustomerDomainController::class, 'updateNameservers'])->name('domains.nameservers.update');
        Route::post('/domeinen/{domain}/dns', [CustomerDomainController::class, 'updateDns'])->name('domains.dns.update');
        Route::post('/domeinen/{domain}/holder', [CustomerDomainController::class, 'updateHolder'])->name('domains.holder.update');
        Route::post('/domeinen/{domain}/authcode', [CustomerDomainController::class, 'authCode'])->name('domains.authcode');
        Route::post('/domeinen/{domain}/automatisch-verlengen', [CustomerDomainController::class, 'autoRenew'])->name('domains.auto-renew');
        Route::post('/domeinen/{domain}/overdragen', [CustomerDomainController::class, 'requestInternalTransfer'])->name('domains.transfer.request');
        Route::post('/domeinen/{domain}/opzeggen', [CustomerDomainController::class, 'cancel'])->name('domains.cancel');
        Route::post('/domeinen/{domain}/hosting/koppelen', [CustomerDomainController::class, 'linkHosting'])->name('domains.hosting.link');
        Route::post('/domeinen/{domain}/hosting/ontkoppelen', [CustomerDomainController::class, 'unlinkHosting'])->name('domains.hosting.unlink');
        Route::get('/domeinen/overdracht/{token}/accepteren', [CustomerDomainController::class, 'acceptInternalTransfer'])->name('domains.transfer.accept');

        // Tickets
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/aanmaken', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reageren', [TicketController::class, 'reply'])->name('tickets.reply');
        Route::post('/tickets/{ticket}/sluiten', [TicketController::class, 'close'])->name('tickets.close');
        Route::get('/tickets/attachments/{attachment}/download', [TicketController::class, 'downloadAttachment'])->name('tickets.attachments.download');
        Route::get('/tickets/attachments/{attachment}/preview', [TicketController::class, 'previewAttachment'])->name('tickets.attachments.preview');

        Route::get('/financieel', [App\Http\Controllers\Customer\FinancialController::class, 'index'])->name('financial.index');
        Route::post('/financieel/betalen', [App\Http\Controllers\Customer\FinancialController::class, 'payBatch'])->name('financial.pay');
        Route::get('/financieel/betaling-retour/{paymentBatch}', [App\Http\Controllers\Customer\FinancialController::class, 'paymentReturn'])->name('financial.payment.return');

        // Invoices
        Route::get('/facturen', [CustomerInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/facturen/{invoice}/download', [CustomerInvoiceController::class, 'download'])->name('invoices.download');
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
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::post('/contact-messages/{contactMessage}/read', [AdminDashboard::class, 'markContactMessageAsRead'])->name('contact-messages.read');
        Route::post('/contact-messages/read-all', [AdminDashboard::class, 'markAllContactMessagesAsRead'])->name('contact-messages.read-all');
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/attachments/{attachment}/download', [AdminTicketController::class, 'downloadAttachment'])->name('tickets.attachments.download');
        Route::post('/tickets/{ticket}/offerte', [AdminTicketController::class, 'createQuote'])->name('tickets.create-quote');
        Route::get('/tickets/attachments/{attachment}/preview', [AdminTicketController::class, 'previewAttachment'])->name('tickets.attachments.preview');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
        Route::patch('/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('tickets.update');
        Route::post('/tickets/{ticket}/claim', [AdminTicketController::class, 'claim'])->name('tickets.claim');
        Route::post('/tickets/{ticket}/assign', [AdminTicketController::class, 'assign'])->name('tickets.assign');
        Route::post('/tickets/{ticket}/close', [AdminTicketController::class, 'close'])->name('tickets.close');
        Route::post('/tickets/{ticket}/reopen', [AdminTicketController::class, 'reopen'])->name('tickets.reopen');
        Route::delete('/tickets/{ticket}', [AdminTicketController::class, 'destroy'])->name('tickets.destroy');

        Route::get('/service-cancellations', [ServiceCancellationController::class, 'index'])->name('service-cancellations.index');
        Route::post('/service-cancellations/{cancellation}/approve', [ServiceCancellationController::class, 'approve'])->name('service-cancellations.approve');
        Route::post('/service-cancellations/{cancellation}/reject', [ServiceCancellationController::class, 'reject'])->name('service-cancellations.reject');

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
        Route::post('/customers/{customer}/emails', [AdminCustomerController::class, 'sendEmail'])->name('customers.emails.send');
        Route::post('/customers/{customer}/emails/standard', [AdminCustomerController::class, 'sendStandardEmail'])->name('customers.emails.standard');
        Route::post('/customers/{customer}/emails/welcome', [AdminCustomerController::class, 'resendWelcomeEmail'])->name('customers.emails.welcome');
        Route::get('/customers/{customer}/services', [AdminCustomerController::class, 'services'])->name('customers.services');
        Route::post('/customers/{customer}/services', [AdminCustomerController::class, 'storeService'])->name('customers.services.store');
        Route::post('/customers/{customer}/services/import-directadmin', [AdminCustomerController::class, 'importExistingService'])->name('customers.services.import-directadmin');
        Route::patch('/customers/{customer}/services/{service}/renewal', [AdminCustomerController::class, 'updateServiceRenewal'])->name('customers.services.renewal.update');
        Route::post('/customers/{customer}/services/{service}/renewal/process', [AdminCustomerController::class, 'processServiceRenewal'])->name('customers.services.renewal.process');
        Route::post('/customers/{customer}/services/{service}/cancel', [AdminCustomerController::class, 'cancelService'])->name('customers.services.cancel');
        Route::post('/customers/{customer}/services/{service}/domain', [AdminCustomerController::class, 'updateServiceDomain'])->name('customers.services.domain');
        Route::post('/customers/{customer}/services/{service}/provision', [AdminCustomerController::class, 'provisionService'])->name('customers.services.provision');
        Route::delete('/customers/{customer}/services/{service}', [AdminCustomerController::class, 'destroyService'])->name('customers.services.destroy');
        Route::get('/customers/{customer}/tickets', [AdminCustomerController::class, 'tickets'])->name('customers.tickets');

        // Domeinregistraties
        Route::get('/domeinen', [AdminDomainRegistrationController::class, 'index'])->name('domains.index');
        Route::get('/domeinen/{domainRegistration}', [AdminDomainRegistrationController::class, 'show'])->name('domains.show');
        Route::delete('/domeinen/{domainRegistration}', [AdminDomainRegistrationController::class, 'destroy'])->name('domains.destroy');

        Route::get('/mijn-profiel', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/mijn-profiel', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

        // Services (bind by id, not slug)
        Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
        Route::get('/services/create', [AdminServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{service:id}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{service:id}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service:id}', [AdminServiceController::class, 'destroy'])->name('services.destroy');
        Route::get('/service-categories', [AdminServiceCategoryController::class, 'index'])->name('service-categories.index');

        // Abuse reports
        Route::get('/abuse-reports', [AbuseReportController::class, 'index'])->name('abuse-reports.index');
        Route::get('/abuse-reports/{abuseReport}', [AbuseReportController::class, 'show'])->name('abuse-reports.show');
        Route::patch('/abuse-reports/{abuseReport}/status', [AbuseReportController::class, 'updateStatus'])->name('abuse-reports.update-status');
        Route::post('/service-categories', [AdminServiceCategoryController::class, 'store'])->name('service-categories.store');
        Route::put('/service-categories/{serviceCategory}', [AdminServiceCategoryController::class, 'update'])->name('service-categories.update');
        Route::delete('/service-categories/{serviceCategory}', [AdminServiceCategoryController::class, 'destroy'])->name('service-categories.destroy');
        Route::get('/server-connections', [AdminServerConnectionController::class, 'index'])->name('server-connections.index');
        Route::post('/server-connections', [AdminServerConnectionController::class, 'store'])->name('server-connections.store');
        Route::put('/server-connections/{serverConnection}', [AdminServerConnectionController::class, 'update'])->name('server-connections.update');
        Route::post('/server-connections/{serverConnection}/test', [AdminServerConnectionController::class, 'test'])->name('server-connections.test');
        Route::delete('/server-connections/{serverConnection}', [AdminServerConnectionController::class, 'destroy'])->name('server-connections.destroy');
        // Financial
        Route::prefix('financial')->name('financial.')->group(function () {
            Route::get('/invoices', [FinancialController::class, 'invoices'])->name('invoices');
            Route::get('/invoices/create', [AdminInvoiceController::class, 'create'])->name('invoices.create');
            Route::post('/invoices', [AdminInvoiceController::class, 'store'])->name('invoices.store');
            Route::get('/invoices/{invoice}/download', [AdminInvoiceController::class, 'download'])->name('invoices.download');
            Route::get('/invoices/{invoice}', [AdminInvoiceController::class, 'show'])->name('invoices.show');
            Route::post('/invoices/{invoice}/mark-sent', [AdminInvoiceController::class, 'markSent'])->name('invoices.mark-sent');
            Route::post('/invoices/{invoice}/mark-paid', [AdminInvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
            Route::post('/invoices/{invoice}/credit', [AdminInvoiceController::class, 'createCredit'])->name('invoices.credit');
            Route::get('/transactions', [FinancialController::class, 'transactions'])->name('transactions');
            Route::get('/transactions/{transaction}/edit', [AdminTransactionController::class, 'edit'])->name('transactions.edit');
            Route::put('/transactions/{transaction}', [AdminTransactionController::class, 'update'])->name('transactions.update');
            Route::delete('/transactions/{transaction}', [AdminTransactionController::class, 'destroy'])->name('transactions.destroy');
            Route::get('/billable-items', [FinancialController::class, 'billableItems'])->name('billable-items');
            Route::get('/quotes', [FinancialController::class, 'quotes'])->name('quotes');
            Route::get('/quotes/create', [AdminQuoteController::class, 'create'])->name('quotes.create');
            Route::post('/quotes', [AdminQuoteController::class, 'store'])->name('quotes.store');
            Route::get('/quotes/{quote}', [AdminQuoteController::class, 'show'])->name('quotes.show');
            Route::get('/quotes/{quote}/edit', [AdminQuoteController::class, 'edit'])->name('quotes.edit');
            Route::put('/quotes/{quote}', [AdminQuoteController::class, 'update'])->name('quotes.update');
            Route::delete('/quotes/{quote}', [AdminQuoteController::class, 'destroy'])->name('quotes.destroy');
            Route::post('/quotes/{quote}/status', [AdminQuoteController::class, 'updateStatus'])->name('quotes.status');
            Route::get('/invoices/{invoice}/edit', [AdminInvoiceController::class, 'edit'])->name('invoices.edit');
            Route::put('/invoices/{invoice}', [AdminInvoiceController::class, 'update'])->name('invoices.update');
            Route::delete('/invoices/{invoice}', [AdminInvoiceController::class, 'destroy'])->name('invoices.destroy');
            Route::post('/invoices/{invoice}/status', [AdminInvoiceController::class, 'updateStatus'])->name('invoices.status');
            Route::post('/invoices/{invoice}/notes', [AdminInvoiceController::class, 'storeNote'])->name('invoices.notes.store');
            Route::post('/invoices/{invoice}/payments', [AdminInvoiceController::class, 'storePayment'])->name('invoices.payments.store');
            Route::get('/logs', [FinancialController::class, 'logs'])->name('logs');
            Route::get('/billing-settings', [AdminBillingSettingsController::class, 'edit'])->name('billing-settings.edit');
            Route::put('/billing-settings', [AdminBillingSettingsController::class, 'update'])->name('billing-settings.update');
            Route::get('/invoice-design', [AdminInvoiceDesignController::class, 'edit'])->name('invoice-design.edit');
            Route::put('/invoice-design', [AdminInvoiceDesignController::class, 'update'])->name('invoice-design.update');
            Route::get('/invoice-design/preview', [AdminInvoiceDesignController::class, 'preview'])->name('invoice-design.preview');
            Route::post('/invoice-design/publish', [AdminInvoiceDesignController::class, 'publish'])->name('invoice-design.publish');
            Route::post('/invoice-design/{version}/restore', [AdminInvoiceDesignController::class, 'restore'])->name('invoice-design.restore');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'general'])->name('general');
            Route::put('/algemeen', [SettingController::class, 'updateGeneral'])->name('general.update');
            Route::get('/mail', [SettingController::class, 'mail'])->name('mail');
            Route::put('/mail', [SettingController::class, 'updateMail'])->name('mail.update');
            Route::post('/mail/test', [SettingController::class, 'sendTestMail'])->name('mail.test');
            Route::get('/emailtemplates', [SettingController::class, 'emailTemplates'])->name('email-templates');
            Route::put('/emailtemplates', [SettingController::class, 'updateEmailTemplates'])->name('email-templates.update');
            Route::get('/emailtemplates/preview/{key}', [SettingController::class, 'previewEmailTemplate'])->name('email-templates.preview');
            Route::get('/betalingen', [SettingController::class, 'payments'])->name('payments');
            Route::put('/betalingen', [SettingController::class, 'updatePayments'])->name('payments.update');
            Route::get('/facturatie', [AdminBillingSettingsController::class, 'edit'])->name('billing');
            Route::put('/facturatie', [AdminBillingSettingsController::class, 'update'])->name('billing.update');
            Route::get('/offerteformulier', [SettingController::class, 'formbuilder'])->name('formbuilder');
            Route::put('/offerteformulier', [SettingController::class, 'updateFormbuilder'])->name('formbuilder.update');
            Route::get('/juridisch', [SettingController::class, 'legal'])->name('legal');
            Route::put('/juridisch', [SettingController::class, 'updateLegal'])->name('legal.update');
            Route::get('/juridische-documenten', [\App\Http\Controllers\Admin\LegalDocumentController::class, 'index'])->name('legal-documents.index');
            Route::get('/juridische-documenten/{document}/bewerken', [\App\Http\Controllers\Admin\LegalDocumentController::class, 'edit'])->name('legal-documents.edit');
            Route::post('/juridische-documenten/{document}', [\App\Http\Controllers\Admin\LegalDocumentController::class, 'update'])->name('legal-documents.update');
            Route::post('/juridische-documenten/{document}/voorbeeld', [\App\Http\Controllers\Admin\LegalDocumentController::class, 'preview'])->name('legal-documents.preview');
            Route::post('/juridische-documenten/{document}/publiceren', [\App\Http\Controllers\Admin\LegalDocumentController::class, 'publish'])->name('legal-documents.publish');
            Route::get('/beveiliging', [SettingController::class, 'security'])->name('security');
            Route::put('/beveiliging', [SettingController::class, 'updateSecurity'])->name('security.update');
            Route::get('/integraties', [SettingController::class, 'integrations'])->name('integrations');
            Route::get('/integraties/transip', [TransIpSettingController::class, 'index'])->name('transip');
            Route::put('/integraties/transip', [TransIpSettingController::class, 'update'])->name('transip.update');
            Route::get('/integraties/transip/test', [TransIpSettingController::class, 'test'])->name('transip.test');
            Route::get('/domein-tlds', [AdminDomainTldController::class, 'index'])->name('domains.tlds.index');
            Route::post('/domein-tlds', [AdminDomainTldController::class, 'store'])->name('domains.tlds.store');
            Route::put('/domein-tlds/{tld}', [AdminDomainTldController::class, 'update'])->name('domains.tlds.update');
            Route::delete('/domein-tlds/{tld}', [AdminDomainTldController::class, 'destroy'])->name('domains.tlds.destroy');
            Route::get('/nieuwsbrief', [SettingController::class, 'newsletter'])->name('newsletter');
            Route::post('/klanten-resetten', [SettingController::class, 'resetCustomers'])->name('reset-customers');
            Route::get('/medewerkers', [StaffController::class, 'index'])->name('staff');
            Route::post('/medewerkers', [StaffController::class, 'store'])->name('staff.store');
            Route::post('/medewerkers/{staff}', [StaffController::class, 'update'])->name('staff.update');
            Route::delete('/medewerkers/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
        });
    });
});

// Juridische pagina's
Route::get('/algemene-voorwaarden', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/privacy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/cookies', [LegalController::class, 'cookies'])->name('legal.cookies');
Route::get('/hostingvoorwaarden', [LegalController::class, 'hosting'])->name('legal.hosting');
Route::get('/acceptable-use', [LegalController::class, 'acceptableUse'])->name('legal.acceptable-use');
Route::get('/verwerkersovereenkomst', [LegalController::class, 'dpa'])->name('legal.dpa');

// Misbruik melden
Route::get('/abuse', [LegalController::class, 'abuse'])->name('legal.abuse');
Route::post('/abuse', [LegalController::class, 'storeAbuse'])->middleware('throttle:5,1')->name('legal.abuse.store');

// Mollie webhook (no auth, POST only)
Route::post('/mollie/webhook', MollieWebhookController::class)->name('mollie.webhook');

// Inline bank transfer instructions
Route::get('/betaling/bankoverschrijving/{paymentId}', [App\Http\Controllers\PaymentController::class, 'bankTransfer'])
    ->middleware(['auth', 'customer'])
    ->name('payment.bank-transfer');
