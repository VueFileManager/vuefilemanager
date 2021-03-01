<?php

use App\Http\Controllers\AppFunctionsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FileBrowser\BrowseController;
use App\Http\Controllers\FileFunctions\EditItemsController;
use App\Http\Controllers\FileFunctions\FavouriteController;
use App\Http\Controllers\FileFunctions\ShareController;
use App\Http\Controllers\FileFunctions\TrashController;
use App\Http\Controllers\General\PricingController;
use App\Http\Controllers\Sharing\FileSharingController;

// Edit Functions
Route::patch('/rename-item/{unique_id}/public/{token}', [EditItemsController::class, 'guest_rename_item']);
Route::get('/zip-folder/{unique_id}/public/{token}', [EditItemsController::class, 'guest_zip_folder']);
Route::post('/create-folder/public/{token}', [EditItemsController::class, 'guest_create_folder']);
Route::post('/remove-item/public/{token}', [EditItemsController::class, 'guest_delete_item']);
Route::post('/zip/public/{token}', [EditItemsController::class, 'guest_zip_multiple_files']);
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

// User master Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // Browse
    Route::group(['prefix' => 'browse'], function () {
        Route::get('/participant-uploads', [BrowseController::class, 'participant_uploads']);
        Route::get('/navigation', [BrowseController::class, 'navigation_tree']);
        Route::get('/folders/{id}', [BrowseController::class, 'folder']);
        Route::get('/sharing', [BrowseController::class, 'shared']);
        Route::get('/latest', [BrowseController::class, 'latest']);
        Route::get('/search', [BrowseController::class, 'search']);
        Route::get('/trash', [BrowseController::class, 'trash']);
    });

    // Trash
    Route::group(['prefix' => 'trash'], function () {
        Route::post('/restore-items', [TrashController::class, 'restore']);
        Route::delete('/empty-trash', [TrashController::class, 'clear']);
    });

    // Favourites
    Route::group(['prefix' => 'folders'], function () {
        Route::delete('/favourites/{id}', [FavouriteController::class, 'destroy']);
        Route::post('/favourites', [FavouriteController::class, 'store']);
    });

    // Share
    Route::group(['prefix' => 'share'], function () {
        Route::post('/{token}/email', [ShareController::class, 'send_to_emails_recipients']);
        Route::delete('/revoke', [ShareController::class, 'destroy']);
        Route::patch('/{token}', [ShareController::class, 'update']);
        Route::post('/{id}', [ShareController::class, 'store']);
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
Route::group(['middleware' => ['auth:sanctum']], function () {

    // Edit items
    Route::patch('/rename/{id}', [EditItemsController::class, 'user_rename_item']);
    Route::post('/create-folder', [EditItemsController::class, 'user_create_folder']);
    Route::post('/remove', [EditItemsController::class, 'user_delete_item']);
    Route::post('/zip', [EditItemsController::class, 'user_zip_multiple_files']);
    Route::get('/zip-folder/{unique_id}', [EditItemsController::class, 'user_zip_folder']);
    Route::post('/upload', [EditItemsController::class, 'user_upload']);
    Route::post('/move', [EditItemsController::class, 'user_move']);

    //Get Emojis List
    Route::get('/emojis-list', [AppFunctionsController::class, 'get_emojis_list']);
});
