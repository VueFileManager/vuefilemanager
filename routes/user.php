<?php

use App\Users\Controllers\Account\AccountDetailsController;
use App\Users\Controllers\Account\UpdatePasswordController;
use App\Users\Controllers\Account\StorageCapacityController;
use App\Users\Controllers\Verification\VerifyEmailController;
use App\Users\Controllers\Verification\ResendVerificationEmail;
use App\Users\Controllers\Authentication\CheckAccountController;
use App\Users\Controllers\Account\UpdateProfileSettingsController;
use App\Users\Controllers\Authentication\AccountAccessTokenController;

Route::post('/check', CheckAccountController::class);

// Email verification
Route::get('/email/verify/{id}', VerifyEmailController::class)
    ->name('verification.verify');

Route::post('/email/verify/resend', ResendVerificationEmail::class)
    ->name('verification.send');

Route::group(['middleware' => ['auth:sanctum']], function () {
    // User Access Token
    Route::apiResource('/tokens', AccountAccessTokenController::class);
    
    // Account
    Route::patch('/settings', UpdateProfileSettingsController::class);
    Route::post('/password', UpdatePasswordController::class);
    Route::get('/storage', StorageCapacityController::class);
    Route::get('/', AccountDetailsController::class);
});
