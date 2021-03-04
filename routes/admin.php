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
    Route::post('/{id}/reset-password', [UserController::class, 'reset_password']);
    Route::patch('/{id}/capacity', [UserController::class, 'change_storage_capacity']);
    Route::get('/{id}/subscription', [UserController::class, 'subscription']);
    Route::delete('/{id}/delete', [UserController::class, 'delete_user']);
    Route::patch('/{id}/role', [UserController::class, 'change_role']);
    Route::get('/{id}/invoices', [UserController::class, 'invoices']);
    Route::get('/{id}/storage', [UserController::class, 'storage']);
    Route::post('/create', [UserController::class, 'create_user']);
    Route::get('/{id}/detail', [UserController::class, 'details']);
    Route::get('/', [UserController::class, 'users']);
});

// Plans
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
    Route::patch('/{slug}', [PagesController::class, 'update']);
    Route::get('/{slug}', [PagesController::class, 'show']);
    Route::get('/', [PagesController::class, 'index']);
});

// Invoices
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