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

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

// Setup Wizard
Route::group(['middleware' => ['api'], 'prefix' => 'setup'], function () {
    Route::post('/purchase-code', 'General\SetupWizardController@verify_purchase_code');
    Route::post('/database', 'General\SetupWizardController@setup_database');

    Route::post('/stripe-credentials', 'General\SetupWizardController@store_stripe_credentials');
    Route::post('/stripe-billings', 'General\SetupWizardController@store_stripe_billings');
    Route::post('/stripe-plans', 'General\SetupWizardController@store_stripe_plans');

    Route::post('/environment-setup', 'General\SetupWizardController@store_environment_setup');
    Route::post('/app-setup', 'General\SetupWizardController@store_app_settings');
    Route::post('/admin-setup', 'General\SetupWizardController@create_admin_account');
});

// Upgrade App
Route::group(['middleware' => ['api'], 'prefix' => 'upgrade'], function () {
    Route::post('/app', 'General\UpgradeAppController@upgrade');
});

// Plans
Route::group(['middleware' => ['api'], 'prefix' => 'public'], function () {
    Route::get('/pricing', 'General\PricingController@index');
});

// Public routes
Route::group(['middleware' => ['api']], function () {

    // Edit Functions
    Route::delete('/remove-item/{unique_id}/public/{token}', 'FileFunctions\EditItemsController@guest_delete_item');
    Route::patch('/rename-item/{unique_id}/public/{token}', 'FileFunctions\EditItemsController@guest_rename_item');
    Route::post('/create-folder/public/{token}', 'FileFunctions\EditItemsController@guest_create_folder');
    Route::patch('/move/{unique_id}/public/{token}', 'FileFunctions\EditItemsController@guest_move');
    Route::post('/upload/public/{token}', 'FileFunctions\EditItemsController@guest_upload');

    // Sharing page browsing
    Route::get('/folders/{unique_id}/public/{token}', 'Sharing\FileSharingController@get_public_folders');
    Route::get('/navigation/public/{token}', 'Sharing\FileSharingController@get_public_navigation_tree');
    Route::post('/shared/authenticate/{token}', 'Sharing\FileSharingController@authenticate');
    Route::get('/search/public/{token}', 'Sharing\FileSharingController@search_public');
    Route::get('/files/{token}/public', 'Sharing\FileSharingController@file_public');
    Route::get('/shared/{token}', 'FileFunctions\ShareController@show');

    // User reset password
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

    // User authentication
    Route::post('/user/check', 'Auth\AuthController@check_account');
    Route::post('/user/register', 'Auth\AuthController@register');
    Route::post('/user/login', 'Auth\AuthController@login');

    // Pages
    Route::post('/contact', 'AppFunctionsController@contact_form');
    Route::get('/page/{slug}', 'AppFunctionsController@get_page');
    Route::get('/content', 'AppFunctionsController@get_settings');
});

// User master Routes
Route::group(['middleware' => ['auth:api', 'auth.master', 'scope:master']], function () {

    // User
    Route::patch('/user/relationships/settings', 'User\AccountController@update_user_settings');
    Route::post('/user/password', 'User\AccountController@change_password');
    Route::patch('/user/profile', 'User\AccountController@update_profile');
    Route::get('/user/subscription', 'User\SubscriptionController@show');
    Route::get('/user/invoices', 'User\AccountController@invoices');
    Route::get('/user/storage', 'User\AccountController@storage');
    Route::get('/user', 'User\AccountController@user');

    // Payment cards
    Route::delete('/user/payment-cards/{id}', 'User\PaymentMethodsController@delete');
    Route::patch('/user/payment-cards/{id}', 'User\PaymentMethodsController@update');
    Route::post('/user/payment-cards', 'User\PaymentMethodsController@store');
    Route::get('/user/payments', 'User\PaymentMethodsController@index');

    // Subscription
    Route::get('/stripe/setup-intent', 'User\SubscriptionController@stripe_setup_intent');
    Route::post('/subscription/upgrade', 'User\SubscriptionController@upgrade');
    Route::post('/subscription/cancel', 'User\SubscriptionController@cancel');
    Route::post('/subscription/resume', 'User\SubscriptionController@resume');

    // Browse
    Route::get('/participant-uploads', 'FileBrowser\BrowseController@participant_uploads');
    Route::get('/file-detail/{unique_id}', 'FileBrowser\BrowseController@file_detail');
    Route::get('/navigation', 'FileBrowser\BrowseController@navigation_tree');
    Route::get('/folders/{unique_id}', 'FileBrowser\BrowseController@folder');
    Route::get('/shared-all', 'FileBrowser\BrowseController@shared');
    Route::get('/latest', 'FileBrowser\BrowseController@latest');
    Route::get('/search', 'FileBrowser\BrowseController@search');
    Route::get('/trash', 'FileBrowser\BrowseController@trash');

    // Trash
    Route::patch('/restore-item/{unique_id}', 'FileFunctions\TrashController@restore');
    Route::delete('/empty-trash', 'FileFunctions\TrashController@clear');

    // Favourites
    Route::delete('/folders/favourites/{unique_id}', 'FileFunctions\FavouriteController@destroy');
    Route::post('/folders/favourites', 'FileFunctions\FavouriteController@store');

    // Share
    Route::delete('/share/{token}', 'FileFunctions\ShareController@destroy');
    Route::patch('/share/{token}', 'FileFunctions\ShareController@update');
    Route::post('/share', 'FileFunctions\ShareController@store');

    // Auth
    Route::get('/logout', 'Auth\AuthController@logout');
});

// Admin
Route::group(['middleware' => ['auth:api', 'auth.master', 'auth.admin', 'scope:master']], function () {

    // Admin
    Route::get('/dashboard', 'Admin\DashboardController@index');
    Route::get('/dashboard/new-users', 'Admin\DashboardController@new_registrations');

    // Get users info
    Route::get('/users/{id}/subscription', 'Admin\UserController@subscription');
    Route::get('/users/{id}/storage', 'Admin\UserController@storage');
    Route::get('/users/{id}/detail', 'Admin\UserController@details');
    Route::get('/users', 'Admin\UserController@users');

    // Edit users
    Route::post('/users/{id}/send-password-email', 'Admin\UserController@send_password_reset_email');
    Route::patch('/users/{id}/capacity', 'Admin\UserController@change_storage_capacity');
    Route::delete('/users/{id}/delete', 'Admin\UserController@delete_user');
    Route::patch('/users/{id}/role', 'Admin\UserController@change_role');
    Route::get('/users/{id}/invoices', 'Admin\UserController@invoices');
    Route::post('/users/create', 'Admin\UserController@create_user');

    // Plans
    Route::get('/plans/{id}/subscribers', 'Admin\PlanController@subscribers');
    Route::patch('/plans/{id}/update', 'Admin\PlanController@update');
    Route::delete('/plans/{id}', 'Admin\PlanController@delete');
    Route::post('/plans/store', 'Admin\PlanController@store');
    Route::get('/plans/{id}', 'Admin\PlanController@show');
    Route::get('/plans', 'Admin\PlanController@index');

    // Pages
    Route::get('/pages', 'Admin\PagesController@index');
    Route::get('/pages/{slug}', 'Admin\PagesController@show');
    Route::patch('/pages/{slug}', 'Admin\PagesController@update');

    // Invoices
    Route::get('/invoices/{token}', 'Admin\InvoiceController@show');
    Route::get('/invoices', 'Admin\InvoiceController@index');

    // Settings
    Route::post('/settings/email', 'SettingController@set_email');
    Route::post('/settings/stripe', 'SettingController@set_stripe');
    Route::patch('/settings', 'SettingController@update');
    Route::get('/settings', 'SettingController@show');
    Route::get('/flush-cache', 'AppFunctionsController@flush_cache');
});

// Protected sharing routes for authenticated user
Route::group(['middleware' => ['auth:api', 'auth.shared', 'scope:visitor,editor']], function () {

    // Browse folders & files
    Route::get('/folders/{unique_id}/private', 'Sharing\FileSharingController@get_private_folders');
    Route::get('/navigation/private', 'Sharing\FileSharingController@get_private_navigation_tree');
    Route::get('/search/private', 'Sharing\FileSharingController@search_private');
    Route::get('/files/private', 'Sharing\FileSharingController@file_private');
});

// User master,editor routes
Route::group(['middleware' => ['auth:api', 'auth.shared', 'auth.master', 'scope:master,editor']], function () {

    // Edit items
    Route::delete('/remove-item/{unique_id}', 'FileFunctions\EditItemsController@user_delete_item');
    Route::patch('/rename-item/{unique_id}', 'FileFunctions\EditItemsController@user_rename_item');
    Route::post('/create-folder', 'FileFunctions\EditItemsController@user_create_folder');
    Route::patch('/move/{unique_id}', 'FileFunctions\EditItemsController@user_move');
    Route::post('/upload', 'FileFunctions\EditItemsController@user_upload');
});
