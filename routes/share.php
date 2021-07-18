<?php

use Domain\Sharing\Controllers\ShareController;
use Domain\Sharing\Controllers\BrowseShareController;
use Domain\Homepage\Controllers\AppFunctionsController;
use Domain\Sharing\Controllers\ManipulateShareItemsController;

// Browse functions
Route::group(['prefix' => 'editor'], function () {
    Route::post('/create-folder/{shared}', [ManipulateShareItemsController::class, 'create_folder']);
    Route::patch('/rename/{id}/{shared}', [ManipulateShareItemsController::class, 'rename_item']);
    Route::post('/remove/{shared}', [ManipulateShareItemsController::class, 'delete_item']);
    Route::post('/upload/{shared}', [ManipulateShareItemsController::class, 'upload']);
    Route::post('/move/{shared}', [ManipulateShareItemsController::class, 'move']);
});

// Zip shared items
Route::group(['prefix' => 'zip'], function () {
    Route::post('/files/{shared}', [ManipulateShareItemsController::class, 'zip_multiple_files']);
    Route::get('/folder/{id}/{shared}', [ManipulateShareItemsController::class, 'zip_folder']);
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
