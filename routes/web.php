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
    });
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        
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
        Route::get('/customers/{customer}/tickets', [AdminCustomerController::class, 'tickets'])->name('customers.tickets');
    });
});
