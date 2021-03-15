<?php

use App\Http\Controllers\App\AppFunctionsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FileManager\BrowseController;
use App\Http\Controllers\FileManager\EditItemsController;
use App\Http\Controllers\FileManager\FavouriteController;
use App\Http\Controllers\FileManager\ShareController;
use App\Http\Controllers\FileManager\TrashController;

// Pages
Route::get('/content', [AppFunctionsController::class, 'get_setting_columns']);
Route::post('/contact', [AppFunctionsController::class, 'contact_form']);
Route::get('/page/{page}', [AppFunctionsController::class, 'get_page']);
Route::get('/pricing', [AppFunctionsController::class, 'get_storage_plans']);

// Password reset
Route::group(['prefix' => 'password'], function () {
    Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/reset', [ResetPasswordController::class, 'reset']);
});

// User master Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // Browse
    Route::group(['prefix' => 'browse'], function () {
        Route::get('/participants', [BrowseController::class, 'participant_uploads']);
        Route::get('/navigation', [BrowseController::class, 'navigation_tree']);
        Route::get('/folders/{id}', [BrowseController::class, 'folder']);
        Route::get('/shared', [BrowseController::class, 'shared']);
        Route::get('/latest', [BrowseController::class, 'latest']);
        Route::get('/search', [BrowseController::class, 'search']);
        Route::get('/trash', [BrowseController::class, 'trash']);
    });

    // Trash
    Route::group(['prefix' => 'trash'], function () {
        Route::post('/restore', [TrashController::class, 'restore']);
        Route::delete('/dump', [TrashController::class, 'dump']);
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

// User master,editor routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/create-folder', [EditItemsController::class, 'create_folder']);
    Route::patch('/rename/{id}', [EditItemsController::class, 'rename_item']);
    Route::post('/remove', [EditItemsController::class, 'delete_item']);
    Route::post('/upload', [EditItemsController::class, 'upload']);
    Route::post('/move', [EditItemsController::class, 'move']);

    Route::group(['prefix' => 'zip'], function () {
        Route::post('/files', [EditItemsController::class, 'zip_multiple_files']);
        Route::get('/folder/{unique_id}', [EditItemsController::class, 'zip_folder']);
    });
});
