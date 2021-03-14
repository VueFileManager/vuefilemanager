<?php

use App\Http\Controllers\App\AppFunctionsController;
use App\Http\Controllers\Sharing\BrowseShareController;
use App\Http\Controllers\Sharing\EditShareItemsController;
use App\Http\Controllers\FileManager\ShareController;
use App\Http\Controllers\Sharing\FileSharingController;

// Editor functions
Route::group(['prefix' => 'editor'], function () {
    Route::post('/create-folder/public/{token}', [EditShareItemsController::class, 'create_folder']);
    Route::patch('/rename/{id}/public/{token}', [EditShareItemsController::class, 'rename_item']);
    Route::post('/remove/public/{token}', [EditShareItemsController::class, 'delete_item']);
    Route::post('/upload/public/{token}', [EditShareItemsController::class, 'upload']);
    Route::post('/move/public/{token}', [EditShareItemsController::class, 'move']);
});

// Editor/Visitor zip functions
Route::group(['prefix' => 'zip'], function () {
    Route::post('/files/public/{token}', [EditShareItemsController::class, 'zip_multiple_files']);
    Route::get('/folder/{id}/public/{token}', [EditShareItemsController::class, 'zip_folder']);
});

// Browse share content
Route::group(['prefix' => 'browse'], function () {
    Route::get('/navigation/public/{token}', [BrowseShareController::class, 'get_public_navigation_tree']);
    Route::get('/folders/{id}/public/{token}', [BrowseShareController::class, 'get_public_folders']);
    Route::get('/search/public/{token}', [BrowseShareController::class, 'search_public']);

    Route::post('/shared/authenticate/{token}', [FileSharingController::class, 'authenticate']);
    Route::get('/files/{token}/public', [FileSharingController::class, 'file_public']);
    Route::get('/shared/{token}', [ShareController::class, 'show']);
});

Route::get('/og-site/{token}', [AppFunctionsController::class, 'og_site']);