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
        // Vérifie si admin connecté sinon redirige vers login
        if (!session()->has('admin_auth')) {
            return redirect()->route('admin.login.page');
        }
        return app(AdminController::class)->index();
    })->name('admin.index');

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

// Page login client avec slug
Route::get('/admin/{slug}/login', [CustomerController::class, 'showLogin'])
    ->name('customer.login');

// Traitement du login client
Route::post('/admin/{slug}/login', [CustomerController::class, 'login'])
    ->name('customer.login.post');

// Logout client
Route::post('/admin/{slug}/logout', [CustomerController::class, 'logout'])
    ->name('customer.logout');

// Dashboard client
Route::get('/admin/{slug}', [CustomerController::class, 'dashboard'])
    ->name('customer.dashboard');

// Route pour la page d'achat principale
Route::get('/purchase', [PurchaseController::class, 'show'])->name('purchase');

// Route pour la page de virement bancaire
Route::get('/payment/bank-transfer', [PurchaseController::class, 'showBankTransfer'])->name('payment.bank_transfer');

// Route pour la confirmation du virement (formulaire sur la page bank-transfer)
Route::post('/payment/confirm/bank-transfer', [PurchaseController::class, 'confirmBankTransfer'])->name('payment.confirm.bank_transfer');

// Carte (SumUp)
Route::get('/payment/sumup', [PurchaseController::class, 'showSumUp'])->name('payment.sumup');
// Route::post('/payment/sumup/callback', [PurchaseController::class, 'handleSumUpCallback'])->name('payment.sumup.callback');
// Carte (SumUp)
Route::get('/payment/sumup/create-checkout', [PurchaseController::class, 'createSumUpCheckout'])->name('payment.sumup.create');
Route::post('/payment/sumup/confirm', [PurchaseController::class, 'confirmSumUpPayment'])->name('payment.sumup.confirm');

// PayPal
Route::get('/payment/paypal', [PurchaseController::class, 'showPayPal'])->name('payment.paypal');
// Route::post('/payment/paypal/success', [PurchaseController::class, 'handlePayPalSuccess'])->name('payment.paypal.success');
Route::post('/paypal/create', [PurchaseController::class, 'createPayPalOrder'])->name('paypal.create');
Route::post('/paypal/capture', [PurchaseController::class, 'capturePayPalOrder'])->name('paypal.capture');


// Page de succès générique
Route::get('/purchase/success', [PurchaseController::class, 'showSuccess'])->name('purchase.success');

// -----------------
// Routes VCard Public
// -----------------
Route::get('/{slug}', [VCardController::class, 'show'])->name('vcard.show');
Route::get('/vcard/download/{slug}', [VCardController::class, 'download'])->name('vcard.download');