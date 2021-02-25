<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AppFunctionsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FileBrowser\BrowseController;
use App\Http\Controllers\FileFunctions\EditItemsController;
use App\Http\Controllers\FileFunctions\FavouriteController;
use App\Http\Controllers\FileFunctions\ShareController;
use App\Http\Controllers\FileFunctions\TrashController;
use App\Http\Controllers\General\PricingController;
use App\Http\Controllers\General\SetupWizardController;
use App\Http\Controllers\Sharing\FileSharingController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\PaymentMethodsController;
use App\Http\Controllers\User\SubscriptionController;

// Public routes
Route::group(['middleware' => ['api']], function () {

    // Edit Functions
    Route::patch('/rename-item/{unique_id}/public/{token}', [EditItemsController::class, 'guest_rename_item']);
    Route::post('/create-folder/public/{token}', [EditItemsController::class, 'guest_create_folder']);
    Route::post('/remove-item/public/{token}', [EditItemsController::class, 'guest_delete_item']);
    Route::post('/zip/public/{token}', [EditItemsController::class, 'guest_zip_multiple_files']);
    Route::get('/zip-folder/{unique_id}/public/{token}', [EditItemsController::class, 'guest_zip_folder']);
    Route::post('/upload/public/{token}', [EditItemsController::class, 'guest_upload']);
    Route::post('/move/public/{token}', [EditItemsController::class, 'guest_move']);

    // Sharing page browsing
    Route::get('/folders/{unique_id}/public/{token}', [FileSharingController::class, 'get_public_folders']);
    Route::get('/navigation/public/{token}', [FileSharingController::class, 'get_public_navigation_tree']);
    Route::post('/shared/authenticate/{token}', [FileSharingController::class, 'authenticate']);
    Route::get('/search/public/{token}', [FileSharingController::class, 'search_public']);
    Route::get('/files/{token}/public', [FileSharingController::class, 'file_public']);
    Route::get('/shared/{token}', [ShareController::class, 'show']);

    // Pages
    Route::post('/contact', [AppFunctionsController::class, 'contact_form']);
    Route::get('/page/{slug}', [AppFunctionsController::class, 'get_page']);
    Route::get('/content', [AppFunctionsController::class, 'get_settings']);

    // Stripe
    Route::get('/pricing', [PricingController::class, 'index']);

    // Password
    Route::group(['prefix' => 'password'], function () {
        Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('/reset', [ResetPasswordController::class, 'reset']);
    });

    // User
    Route::group(['prefix' => '/user'], function () {
        Route::post('/check', [AuthController::class, 'check_account']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Setup Wizard
    Route::group(['prefix' => 'setup'], function () {
        Route::post('/purchase-code', [SetupWizardController::class, 'verify_purchase_code']);
        Route::post('/database', [SetupWizardController::class, 'setup_database']);
        Route::post('/stripe-credentials', [SetupWizardController::class, 'store_stripe_credentials']);
        Route::post('/stripe-billings', [SetupWizardController::class, 'store_stripe_billings']);
        Route::post('/stripe-plans', [SetupWizardController::class, 'store_stripe_plans']);
        Route::post('/environment-setup', [SetupWizardController::class, 'store_environment_setup']);
        Route::post('/app-setup', [SetupWizardController::class, 'store_app_settings']);
        Route::post('/admin-setup', [SetupWizardController::class, 'create_admin_account']);
    });
});

// User master Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // Browse
    Route::group(['prefix' => 'browse'], function () {
        Route::get('/participant-uploads', [BrowseController::class, 'participant_uploads']);
        Route::get('/navigation', [BrowseController::class, 'navigation_tree']);
        Route::get('/folders/{unique_id}', [BrowseController::class, 'folder']);
        Route::get('/shared-all', [BrowseController::class, 'shared']);
        Route::get('/latest', [BrowseController::class, 'latest']);
        Route::get('/search', [BrowseController::class, 'search']);
    });

    // Trash
    Route::group(['prefix' => 'trash'], function () {
        Route::post('/restore-items', [TrashController::class, 'restore']);
        Route::delete('/empty-trash', [TrashController::class, 'clear']);
        Route::get('/', [BrowseController::class, 'trash']);
    });

    // Subscription
    Route::group(['prefix' => 'subscription'], function () {
        Route::get('/setup-intent', [SubscriptionController::class, 'stripe_setup_intent']);
        Route::post('/upgrade', [SubscriptionController::class, 'upgrade']);
        Route::post('/cancel', [SubscriptionController::class, 'cancel']);
        Route::post('/resume', [SubscriptionController::class, 'resume']);
    });

    // Favourites
    Route::group(['prefix' => 'folders'], function () {
        Route::delete('/favourites/{unique_id}', [FavouriteController::class, 'destroy']);
        Route::post('/favourites', [FavouriteController::class, 'store']);
    });

    // User
    Route::group(['prefix' => 'user'], function () {
        Route::patch('/relationships/settings', [AccountController::class, 'update_user_settings']);
        Route::post('/password', [AccountController::class, 'change_password']);
        Route::patch('/profile', [AccountController::class, 'update_profile']);
        Route::get('/subscription', [SubscriptionController::class, 'show']);
        Route::get('/invoices', [AccountController::class, 'invoices']);
        Route::get('/storage', [AccountController::class, 'storage']);
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/', [AccountController::class, 'user']);

        // Payment cards
        Route::delete('/payment-cards/{id}', [PaymentMethodsController::class, 'delete']);
        Route::patch('/payment-cards/{id}', [PaymentMethodsController::class, 'update']);
        Route::post('/payment-cards', [PaymentMethodsController::class, 'store']);
        Route::get('/payments', [PaymentMethodsController::class, 'index']);
    });

    // Share
    Route::group(['prefix' => 'share'], function () {
        Route::post('/{token}/send-email', [ShareController::class, 'shared_send_via_email']);
        Route::post('/cancel', [ShareController::class, 'destroy']);
        Route::patch('/{token}', [ShareController::class, 'update']);
        Route::post('/', [ShareController::class, 'store']);
    });
});

// Admin
Route::group(['middleware' => ['auth:api', 'auth.master', 'auth.admin', 'scope:master']], function () {

    // Admin
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/new-users', [DashboardController::class, 'new_registrations']);
    });

    // Users
    Route::group(['prefix' => 'users'], function () {
        Route::post('/{id}/send-password-email', [UserController::class, 'send_password_reset_email']);
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
        Route::post('/email', [InvoiceController::class, 'set_email']);
        Route::post('/stripe', [InvoiceController::class, 'set_stripe']);
        Route::patch('/', [InvoiceController::class, 'update']);
        Route::get('/', [InvoiceController::class, 'show']);
        Route::get('/flush-cache', [AppFunctionsController::class, 'flush_cache']);
    });
});

// Protected sharing routes for authenticated user
Route::group(['middleware' => ['auth:api', 'auth.shared', 'scope:visitor,editor']], function () {

    // Browse folders & files
    Route::get('/folders/{unique_id}/private', [FileSharingController::class, 'get_private_folders']);
    Route::get('/navigation/private', [FileSharingController::class, 'get_private_navigation_tree']);
    Route::get('/search/private', [FileSharingController::class, 'search_private']);
    Route::get('/files/private', [FileSharingController::class, 'file_private']);
});

// User master,editor routes
Route::group(['middleware' => ['auth:api', 'auth.shared', 'auth.master', 'scope:master,editor']], function () {

    // Edit items
    Route::patch('/rename-item/{unique_id}', [EditItemsController::class, 'user_rename_item']);
    Route::post('/create-folder', [EditItemsController::class, 'user_create_folder']);
    Route::post('/remove-item', [EditItemsController::class, 'user_delete_item']);
    Route::post('/zip', [EditItemsController::class, 'user_zip_multiple_files']);
    Route::get('/zip-folder/{unique_id}', [EditItemsController::class, 'user_zip_folder']);
    Route::post('/upload', [EditItemsController::class, 'user_upload']);
    Route::post('/move', [EditItemsController::class, 'user_move']);

    //Get Emojis List
    Route::get('/emojis-list', [AppFunctionsController::class, 'get_emojis_list']);
});
