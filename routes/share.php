<?php

use Domain\Files\Controllers\EditorUploadFilesController;
use Domain\Homepage\Controllers\AppFunctionsController;
use Domain\Folders\Controllers\EditorCreateFolderController;
use Domain\Sharing\Controllers\BrowseShareController;
use Domain\Sharing\Controllers\ManipulateShareItemsController;
use Domain\Sharing\Controllers\ShareController;
use Domain\Zipping\Controllers\EditorZipFilesController;
use Domain\Zipping\Controllers\EditorZipFolderController;

// Browse functions
Route::group(['prefix' => 'editor'], function () {
    Route::post('/create-folder/{shared}', EditorCreateFolderController::class);
    Route::post('/upload/{shared}', EditorUploadFilesController::class);

    Route::patch('/rename/{id}/{shared}', [ManipulateShareItemsController::class, 'rename_item']);
    Route::post('/remove/{shared}', [ManipulateShareItemsController::class, 'delete_item']);
    Route::post('/move/{shared}', [ManipulateShareItemsController::class, 'move']);
});

// Zip shared items
Route::group(['prefix' => 'zip'], function () {
    Route::post('/files/{shared}', EditorZipFilesController::class);
    Route::get('/folder/{id}/{shared}', EditorZipFolderController::class);
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
