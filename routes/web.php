<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\VCardController;
use Illuminate\Support\Facades\Route;

// -----------------
// Page Home
// -----------------
Route::get('/', [HomeController::class, 'index'])->name('home');

// -----------------
// Login Admin (public)
// -----------------
Route::get('/admin190919642025/login', [AdminLoginController::class, 'showLogin'])->name('admin.login.page');
Route::post('/admin190919642025/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin190919642025/logout', [AdminController::class, 'logout'])->name('admin.logout');

// -----------------
// Dashboard Admin
// -----------------
Route::prefix('admin190919642025')->group(function () {

    Route::get('/', function () {
        if (!session()->has('admin_auth')) {
            return redirect()->route('admin.login.page');
        }
        return app(AdminController::class)->index();
    })->name('admin.index');

    // ===== ROUTES COMMANDES =====
    Route::get('/orders', function () {
        if (!session()->has('admin_auth')) {
            return redirect()->route('admin.login.page');
        }
        return app(AdminController::class)->orders(request());
    })->name('admin.orders');

    Route::post('/orders/{order}/create-customer', [AdminController::class, 'createCustomerFromOrder'])
        ->name('admin.orders.create-customer');

    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])
        ->name('admin.orders.update-status');

    Route::delete('/orders/{order}', [AdminController::class, 'deleteOrder'])
        ->name('admin.orders.delete');
        
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])
        ->name('admin.orders.show');

    Route::post('/orders/{order}/send-invoice', [AdminController::class, 'sendInvoice'])
        ->name('admin.orders.send-invoice');
        
    Route::get('/orders/{order}/download-invoice', [AdminController::class, 'downloadInvoice'])
        ->name('admin.orders.download-invoice');
        
    Route::post('/orders/bulk-update-status', [AdminController::class, 'bulkUpdateStatus'])
        ->name('admin.orders.bulk-update-status');
        
    Route::post('/orders/bulk-create-customers', [AdminController::class, 'bulkCreateCustomers'])
        ->name('admin.orders.bulk-create-customers');

    Route::delete('/orders/bulk-delete', [AdminController::class, 'bulkDelete'])
        ->name('admin.orders.bulk-delete');

    Route::get('/orders/{order}/edit', [AdminController::class, 'editO'])
        ->name('admin.orders.edit');
        
    Route::get('/orders/export', [AdminController::class, 'exportOrders'])->name('admin.orders.export');

    // ===== ROUTES CLIENTS =====
    Route::get('/create', function () {
        if (!session()->has('admin_auth')) {
            return redirect()->route('admin.login.page');
        }
        return app(AdminController::class)->create();
    })->name('admin.create');

    Route::post('/', function () {
        if (!session()->has('admin_auth')) {
            return redirect()->route('admin.login.page');
        }
        return app(AdminController::class)->store(request());
    })->name('admin.store');

    Route::get('/edit/{customer}', [AdminController::class, 'edit'])
        ->name('admin.edit');

    Route::put('/{customer}', [AdminController::class, 'update'])->name('admin.update');
    
    Route::post('/send-email/{customer}', [AdminController::class, 'sendWelcomeEmail'])
        ->name('admin.send.email');

    Route::post('/validate-send', function () {
        if (!session()->has('admin_auth')) {
            return redirect()->route('admin.login.page');
        }
        return app(AdminController::class)->validateAndSend(request());
    })->name('admin.validate.send');

    Route::delete('/customer/{customer}', [AdminController::class, 'destroy'])
        ->name('admin.customer.destroy');

    Route::post('/customer/{customer}/toggle-active', [AdminController::class, 'toggleActive'])
        ->name('admin.customer.toggleActive');
});

// -----------------
// Routes Customer Dashboard
// -----------------
Route::put('/customer/update/{customer}', [CustomerController::class, 'update'])->name('customer.update');

Route::get('/admin/{slug}/login', [CustomerController::class, 'showLogin'])
    ->name('customer.login');

Route::post('/admin/{slug}/login', [CustomerController::class, 'login'])
    ->name('customer.login.post');

Route::post('/admin/{slug}/logout', [CustomerController::class, 'logout'])
    ->name('customer.logout');

Route::get('/admin/{slug}', [CustomerController::class, 'dashboard'])
    ->name('customer.dashboard');

// -----------------
// Routes Paiement
// -----------------
Route::get('/purchase', [PurchaseController::class, 'show'])->name('purchase');
Route::get('/payment/bank-transfer', [PurchaseController::class, 'showBankTransfer'])->name('payment.bank_transfer');
Route::post('/payment/confirm/bank-transfer', [PurchaseController::class, 'confirmBankTransfer'])->name('payment.confirm.bank_transfer');
Route::get('/payment/sumup', [PurchaseController::class, 'showSumUp'])->name('payment.sumup');
Route::get('/payment/sumup/create-checkout', [PurchaseController::class, 'createSumUpCheckout'])->name('payment.sumup.create');
Route::post('/payment/sumup/confirm', [PurchaseController::class, 'confirmSumUpPayment'])->name('payment.sumup.confirm');
Route::get('/payment/paypal', [PurchaseController::class, 'showPayPal'])->name('payment.paypal');
Route::post('/paypal/create', [PurchaseController::class, 'createPayPalOrder'])->name('paypal.create');
Route::post('/paypal/capture', [PurchaseController::class, 'capturePayPalOrder'])->name('paypal.capture');
Route::get('/purchase/success', [PurchaseController::class, 'showSuccess'])->name('purchase.success');
Route::post('/purchase/process', [PurchaseController::class, 'processPurchase'])->name('purchase.process');

// -----------------
// Routes VCard Public
// -----------------
Route::get('/{slug}', [VCardController::class, 'show'])->name('vcard.show');
Route::get('/vcard/download/{slug}', [VCardController::class, 'download'])->name('vcard.download');