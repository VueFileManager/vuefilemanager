<?php

use Illuminate\Support\Facades\Route;
use VueFileManager\Subscription\Support\Webhooks\WebhooksController;
use VueFileManager\Subscription\Domain\Plans\Controllers\PlansController;
use VueFileManager\Subscription\Domain\Plans\Controllers\GetPlansController;
use VueFileManager\Subscription\Domain\Plans\Actions\UpdatePlanFixedItemAction;
use VueFileManager\Subscription\Domain\Plans\Controllers\SynchronizePlansController;
use VueFileManager\Subscription\Domain\Plans\Controllers\GetPlanSubscribersController;
use VueFileManager\Subscription\Domain\Credits\Controllers\CreditUserBalanceController;
use VueFileManager\Subscription\Domain\BillingAlerts\Controllers\BillingAlertController;
use VueFileManager\Subscription\Domain\Transactions\Controllers\GetTransactionsController;
use VueFileManager\Subscription\Domain\Subscriptions\Controllers\GetSubscriptionController;
use VueFileManager\Subscription\Domain\Subscriptions\Controllers\EditSubscriptionController;
use VueFileManager\Subscription\Domain\Subscriptions\Controllers\SwapSubscriptionController;
use VueFileManager\Subscription\Domain\Transactions\Controllers\GetAllTransactionsController;
use VueFileManager\Subscription\Domain\Subscriptions\Controllers\CancelSubscriptionController;
use VueFileManager\Subscription\Domain\Transactions\Controllers\GetUserTransactionsController;
use VueFileManager\Subscription\Domain\Subscriptions\Controllers\GetAllSubscriptionsController;
use VueFileManager\Subscription\Domain\Subscriptions\Controllers\GetUserSubscriptionController;
use VueFileManager\Subscription\Support\Miscellaneous\Stripe\Controllers\CreateStripeSessionController;
use VueFileManager\Subscription\Support\Miscellaneous\Stripe\Controllers\DeleteStripeCreditCardController;
use VueFileManager\Subscription\Support\Miscellaneous\Stripe\Controllers\CreateStripeSetupIntentController;
use VueFileManager\Subscription\Support\Miscellaneous\Paystack\Controllers\CreatePaystackTransactionController;

// System
Route::group(['prefix' => 'api/subscriptions', 'middleware' => ['api']], function () {
    Route::post('/{driver}/webhooks', WebhooksController::class);
    Route::get('/plans', GetPlansController::class);
});

// Stripe
Route::group(['prefix' => 'api/stripe', 'middleware' => ['api', 'auth:sanctum']], function () {
    Route::delete('/credit-cards/{creditCard}', DeleteStripeCreditCardController::class);
    Route::get('/setup-intent', CreateStripeSetupIntentController::class);
    Route::post('/checkout', CreateStripeSessionController::class);
});

// Paystack
Route::group(['prefix' => 'api/paystack', 'middleware' => ['api', 'auth:sanctum']], function () {
    Route::post('/checkout', CreatePaystackTransactionController::class);
});

// User
Route::group(['prefix' => 'api/subscriptions', 'middleware' => ['api', 'auth:sanctum']], function () {
    // Subscription
    Route::post('/edit/{subscription}', EditSubscriptionController::class);
    Route::post('/swap/{plan}', SwapSubscriptionController::class);
    Route::post('/cancel', CancelSubscriptionController::class);
    Route::get('/detail', GetSubscriptionController::class);

    // Transactions
    Route::get('/transactions', GetTransactionsController::class);

    // Alerts
    Route::post('/billing-alert', [BillingAlertController::class, 'store']);
    Route::put('/billing-alert', [BillingAlertController::class, 'update']);
    Route::delete('/billing-alert', [BillingAlertController::class, 'destroy']);
});

// Admin
Route::group(['prefix' => 'api/subscriptions/admin', 'middleware' => ['api', 'auth:sanctum', 'admin']], function () {
    // Plans
    Route::get('/plans/{plan}/subscribers', GetPlanSubscribersController::class);
    Route::patch('/plans/{plan}/features', UpdatePlanFixedItemAction::class);
    Route::get('/plans/synchronize', SynchronizePlansController::class);
    Route::apiResource('/plans', PlansController::class);

    // User data
    Route::get('/users/{id}/transactions', GetUserTransactionsController::class);
    Route::get('/users/{id}/subscription', GetUserSubscriptionController::class);
    Route::post('/users/{id}/credit', CreditUserBalanceController::class);

    // Transactions
    Route::get('/transactions', GetAllTransactionsController::class);

    // Subscriptions
    Route::get('/', GetAllSubscriptionsController::class);
});
