<?php

use App\Users\Controllers\Account\AccountDetailsController;
use App\Users\Controllers\Account\UpdatePasswordController;
use App\Users\Controllers\Account\StorageCapacityController;
use App\Users\Controllers\Verification\VerifyEmailController;
use Domain\Transactions\Controllers\GetTransactionsController;
use App\Users\Controllers\Authentication\CheckAccountController;
use App\Users\Controllers\Account\UpdateProfileSettingsController;
use Domain\Notifications\Controllers\GetUserNotificationsController;
use App\Users\Controllers\Authentication\AccountAccessTokenController;
use Domain\Notifications\Controllers\FlushUserNotificationsController;
use App\Users\Controllers\Verification\ResendVerificationEmailController;
use Domain\Notifications\Controllers\MarkUserNotificationsAsReadController;

Route::post('/check', CheckAccountController::class);

// Email verification
Route::get('/email/verify/{id}', VerifyEmailController::class)
    ->name('verification.verify');

Route::post('/email/verify/resend', ResendVerificationEmailController::class)
    ->name('verification.send');

Route::group(['middleware' => ['auth:sanctum']], function () {
    // User Access Token
    Route::apiResource('/tokens', AccountAccessTokenController::class);

    // Notifications
    Route::post('/notifications/read', MarkUserNotificationsAsReadController::class);
    Route::delete('/notifications', FlushUserNotificationsController::class);
    Route::get('/notifications', GetUserNotificationsController::class);

    // Account
    Route::patch('/settings', UpdateProfileSettingsController::class);
    Route::get('/transactions', GetTransactionsController::class);
    Route::post('/password', UpdatePasswordController::class);
    Route::get('/storage', StorageCapacityController::class);
    Route::get('/', AccountDetailsController::class);
});
