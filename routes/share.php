<?php

use App\Http\Controllers\App\AppFunctionsController;
use App\Http\Controllers\Sharing\BrowseShareController;
use App\Http\Controllers\Sharing\EditShareItemsController;
use App\Http\Controllers\FileManager\ShareController;

// Browse functions
Route::group(['prefix' => 'editor'], function () {
    Route::post('/create-folder/{shared}', [EditShareItemsController::class, 'create_folder']);
    Route::patch('/rename/{id}/{shared}', [EditShareItemsController::class, 'rename_item']);
    Route::post('/remove/{shared}', [EditShareItemsController::class, 'delete_item']);
    Route::post('/upload/{shared}', [EditShareItemsController::class, 'upload']);
    Route::post('/move/{shared}', [EditShareItemsController::class, 'move']);
});

// Zip shared items
Route::group(['prefix' => 'zip'], function () {
    Route::post('/files/{shared}', [EditShareItemsController::class, 'zip_multiple_files']);
    Route::get('/folder/{id}/{shared}', [EditShareItemsController::class, 'zip_folder']);
});

// Browse share content
Route::group(['prefix' => 'browse'], function () {
    Route::post('/authenticate/{shared}', [BrowseShareController::class, 'authenticate']);
    Route::get('/navigation/{shared}', [BrowseShareController::class, 'navigation_tree']);
    Route::get('/folders/{id}/{shared}', [BrowseShareController::class, 'browse_folder']);
    Route::get('/file/{shared}', [BrowseShareController::class, 'get_single_file']);
    Route::get('/search/{shared}', [BrowseShareController::class, 'search']);
    Route::get('/share/{shared}', [ShareController::class, 'show']);
});

Route::get('/og-site/{shared}', [AppFunctionsController::class, 'og_site']);