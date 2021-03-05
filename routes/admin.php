<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AppFunctionsController;
use App\Http\Controllers\SettingController;

// Dashboard
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/newbies', [DashboardController::class, 'newbies']);
    Route::get('/', [DashboardController::class, 'index']);
});

// Users
Route::group(['prefix' => 'users'], function () {
    Route::patch('/{user}/capacity', [UserController::class, 'change_storage_capacity']);
    Route::post('/{user}/reset-password', [UserController::class, 'reset_password']);
    Route::get('/{user}/subscription', [UserController::class, 'subscription']);
    Route::delete('/{user}/delete', [UserController::class, 'delete_user']);
    Route::patch('/{user}/role', [UserController::class, 'change_role']);
    Route::get('/{user}/invoices', [UserController::class, 'invoices']);
    Route::get('/{user}/storage', [UserController::class, 'storage']);
    Route::get('/{user}/detail', [UserController::class, 'details']);
    Route::post('/create', [UserController::class, 'create_user']);
    Route::get('/', [UserController::class, 'users']);
});

// Plans
// TODO: testy
Route::group(['prefix' => 'plans'], function () {
    Route::get('/{id}/subscribers', [PlanController::class, 'subscribers']);
    Route::patch('/{id}/update', [PlanController::class, 'update']);
    Route::delete('/{id}', [PlanController::class, 'delete']);
    Route::post('/store', [PlanController::class, 'store']);
    Route::get('/{id}', [PlanController::class, 'show']);
    Route::get('/', [PlanController::class, 'index']);
});

// Pages
Route::group(['prefix' => 'pages'], function () {
    Route::patch('/{page}', [PagesController::class, 'update']);
    Route::get('/{page}', [PagesController::class, 'show']);
    Route::get('/', [PagesController::class, 'index']);
});

// Invoices
// TODO: testy
Route::group(['prefix' => 'invoices'], function () {
    Route::get('/{token}', [InvoiceController::class, 'show']);
    Route::get('/', [InvoiceController::class, 'index']);
});

// Settings
Route::group(['prefix' => 'settings'], function () {
    Route::get('/flush-cache', [AppFunctionsController::class, 'flush_cache']);
    Route::post('/stripe', [SettingController::class, 'set_stripe']);
    Route::post('/email', [SettingController::class, 'set_email']);
    Route::patch('/', [SettingController::class, 'update']);
    Route::get('/', [SettingController::class, 'show']);
});