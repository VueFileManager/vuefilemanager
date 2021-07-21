<?php

use Domain\Sharing\Controllers\ShareController;
use Domain\Sharing\Controllers\OGSiteController;
use Domain\Zipping\Controllers\EditorZipFilesController;
use Domain\Files\Controllers\EditorUploadFilesController;
use Domain\Zipping\Controllers\EditorZipFolderController;
use Domain\Folders\Controllers\EditorCreateFolderController;
use Domain\Sharing\Controllers\ManipulateShareItemsController;
use Domain\Files\Controllers\VisitorGetSingleFileResourceController;
use Domain\Browsing\Controllers\VisitorBrowseFolderContentController;
use Domain\Folders\Controllers\VisitorNavigationFolderTreeController;
use Domain\Browsing\Controllers\VisitorSearchFilesAndFoldersController;
use Domain\Sharing\Controllers\AuthenticateProtectedSharedItemController;

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
    Route::post('/authenticate/{shared}', AuthenticateProtectedSharedItemController::class);
    Route::get('/folders/{id}/{shared}', VisitorBrowseFolderContentController::class);
    Route::get('/navigation/{shared}', VisitorNavigationFolderTreeController::class);
    Route::get('/search/{shared}', VisitorSearchFilesAndFoldersController::class);
    Route::get('/file/{shared}', VisitorGetSingleFileResourceController::class);
    Route::get('/share/{shared}', [ShareController::class, 'show']);
});

Route::get('/og-site/{shared}', OGSiteController::class);
