<?php

use Domain\Payments\Controllers\PaymentMethodsController;
use App\Users\Controllers\Account\AccountDetailsController;
use App\Users\Controllers\Account\UpdatePasswordController;
use App\Users\Controllers\Account\StorageCapacityController;
use App\Users\Controllers\Verification\VerifyEmailController;
use Domain\Invoices\Controllers\UserProfileInvoiceController;
use Domain\Subscriptions\Controllers\GetSetupIntentController;
use App\Users\Controllers\Verification\ResendVerificationEmail;
use App\Users\Controllers\Authentication\CheckAccountController;
use App\Users\Controllers\Account\UpdateProfileSettingsController;
use Domain\Subscriptions\Controllers\SubscriptionCancelController;
use Domain\Subscriptions\Controllers\SubscriptionResumeController;
use Domain\Subscriptions\Controllers\SubscriptionDetailsController;
use Domain\Subscriptions\Controllers\SubscriptionUpgradeController;
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

    // Subscription
    Route::group(['prefix' => 'subscription'], function () {
        Route::get('/setup-intent', GetSetupIntentController::class);

        Route::apiResource('/payment-cards', PaymentMethodsController::class);
        Route::get('/invoices', UserProfileInvoiceController::class);
        Route::get('/', SubscriptionDetailsController::class);

        Route::post('/upgrade', SubscriptionUpgradeController::class);
        Route::post('/cancel', SubscriptionCancelController::class);
        Route::post('/resume', SubscriptionResumeController::class);
    });
});
