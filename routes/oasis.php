<?php

use App\Http\Controllers\Oasis\AdminController;
use App\Http\Controllers\Oasis\ClientController;
use App\Http\Controllers\Oasis\InvoiceController;
use App\Http\Controllers\Oasis\InvoiceProfileController;
use App\Http\Controllers\Oasis\SubscriptionController;
use App\Services\Oasis\OasisDevService;

Route::group(['middleware' => 'api', 'prefix' => '/api/oasis'], function () {

    // Admin
    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'admin'], function () {
        Route::get('/company-details', [AdminController::class, 'get_company_details']);
        Route::post('/users/create-order', [AdminController::class, 'create_order']);
        Route::post('/users/create-user', [AdminController::class, 'create_user']);
    });

    // Subscription
    Route::group(['prefix' => 'subscribe'], function () {
        Route::post('/{order?}', [SubscriptionController::class, 'subscribe']);
        Route::get('/{order}', [SubscriptionController::class, 'get_subscription_request']);
        Route::get('/{order}/setup-intent', [SubscriptionController::class, 'get_setup_intent']);
    });

    // Invoices
    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'invoices'], function () {
        Route::get('/regular', [InvoiceController::class, 'get_all_regular_invoices']);
        Route::get('/advance', [InvoiceController::class, 'get_all_advance_invoices']);
        Route::get('/search', [InvoiceController::class, 'search']);

        Route::post('/', [InvoiceController::class, 'store']);
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy']);

        Route::get('/profile', [InvoiceProfileController::class, 'show']);
        Route::post('/profile', [InvoiceProfileController::class, 'store']);
        Route::patch('/profile', [InvoiceProfileController::class, 'update']);
    });

    // Clients
    Route::group(['prefix' => 'clients'], function () {
        Route::get('/', [ClientController::class, 'index']);
        Route::get('/search', [ClientController::class, 'search']);

        Route::get('/{client}', [ClientController::class, 'show']);
        Route::get('/{client}/invoices', [ClientController::class, 'show_invoices']);
        Route::patch('/{client}', [ClientController::class, 'update']);
        Route::delete('/{client}', [ClientController::class, 'destroy']);

        Route::post('/', [ClientController::class, 'store']);
    });
});

// Web routes
Route::group(['middleware' => 'web', 'prefix' => 'oasis'], function () {
    Route::post('/subscribe/{order}/set-password', [SubscriptionController::class, 'set_password']);

    // Admin
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/invoice/{invoice}', [InvoiceController::class, 'get_invoice']);
    });
});

// Debug routes
Route::group(['middleware' => 'web', 'prefix' => 'oasis/debug'], function () {
    Route::get('/invoice', [OasisDevService::class, 'get_invoice_view'])->name('invoice-debug');
});
