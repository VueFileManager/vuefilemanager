<?php

use App\Http\Controllers\App\AppFunctionsController;
use App\Http\Controllers\Sharing\BrowseShareController;
use App\Http\Controllers\Sharing\EditShareItemsController;
use App\Http\Controllers\FileManager\ShareController;
use App\Http\Controllers\Sharing\ServeSharedController;

// Editor functions
Route::group(['prefix' => 'editor'], function () {
    Route::post('/create-folder/public/{shared}', [EditShareItemsController::class, 'create_folder']);
    Route::patch('/rename/{id}/public/{shared}', [EditShareItemsController::class, 'rename_item']);
    Route::post('/remove/public/{shared}', [EditShareItemsController::class, 'delete_item']);
    Route::post('/upload/public/{shared}', [EditShareItemsController::class, 'upload']);
    Route::post('/move/public/{shared}', [EditShareItemsController::class, 'move']);
});

// Editor/Visitor zip functions
Route::group(['prefix' => 'zip'], function () {
    Route::post('/files/public/{shared}', [EditShareItemsController::class, 'zip_multiple_files']);
    Route::get('/folder/{id}/public/{shared}', [EditShareItemsController::class, 'zip_folder']);
});

// Browse share content
Route::group(['prefix' => 'browse'], function () {
    Route::get('/navigation/public/{shared}', [BrowseShareController::class, 'get_public_navigation_tree']);
    Route::get('/folders/{id}/public/{shared}', [BrowseShareController::class, 'get_public_folders']);
    Route::get('/search/public/{shared}', [BrowseShareController::class, 'search_public']);

    Route::post('/shared/authenticate/{shared}', [ServeSharedController::class, 'authenticate']);
    Route::get('/files/{shared}/public', [ServeSharedController::class, 'file_public']);
    Route::get('/shared/{token}', [ShareController::class, 'show']);
});

// Private sharing secured by password
// TODO: tests
Route::group(['middleware' => ['auth:api', 'auth.shared', 'scope:visitor,editor']], function () {
    Route::get('/folders/{unique_id}/private', [ServeSharedController::class, 'get_private_folders']);
    Route::get('/navigation/private', [ServeSharedController::class, 'get_private_navigation_tree']);
    Route::get('/search/private', [ServeSharedController::class, 'search_private']);
    Route::get('/files/private', [ServeSharedController::class, 'file_private']);
});

Route::get('/og-site/{shared}', [AppFunctionsController::class, 'og_site']);