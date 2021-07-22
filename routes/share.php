<?php

use Domain\Sharing\Controllers\ShareController;
use Domain\Files\Controllers\VisitorShowFileController;
use Domain\Files\Controllers\VisitorUploadFileController;
use Domain\Zip\Controllers\VisitorZipFilesController;
use Domain\Zip\Controllers\VisitorZipFolderController;
use Domain\Folders\Controllers\VisitorCreateFolderController;
use Domain\Sharing\Controllers\WebCrawlerOpenGraphController;
use Domain\Items\Controllers\VisitorMoveFileOrFolderController;
use Domain\Items\Controllers\VisitorDeleteFileOrFolderController;
use Domain\Items\Controllers\VisitorRenameFileOrFolderController;
use Domain\Sharing\Controllers\VisitorUnlockLockedShareController;
use Domain\Browsing\Controllers\VisitorBrowseFolderContentController;
use Domain\Folders\Controllers\VisitorNavigationFolderTreeController;
use Domain\Browsing\Controllers\VisitorSearchFilesAndFoldersController;

// Browse functions
Route::group(['prefix' => 'editor'], function () {
    Route::post('/create-folder/{shared}', VisitorCreateFolderController::class);
    Route::post('/upload/{shared}', VisitorUploadFileController::class);

    Route::patch('/rename/{id}/{shared}', VisitorRenameFileOrFolderController::class);
    Route::post('/remove/{shared}', VisitorDeleteFileOrFolderController::class);
    Route::post('/move/{shared}', VisitorMoveFileOrFolderController::class);
});

// Zip shared items
Route::group(['prefix' => 'zip'], function () {
    Route::get('/folder/{id}/{shared}', VisitorZipFolderController::class);
    Route::post('/files/{shared}', VisitorZipFilesController::class);
});

// Browse share content
Route::group(['prefix' => 'browse'], function () {
    Route::post('/authenticate/{shared}', VisitorUnlockLockedShareController::class);
    Route::get('/folders/{id}/{shared}', VisitorBrowseFolderContentController::class);
    Route::get('/navigation/{shared}', VisitorNavigationFolderTreeController::class);
    Route::get('/search/{shared}', VisitorSearchFilesAndFoldersController::class);
    Route::get('/file/{shared}', VisitorShowFileController::class);
    Route::get('/share/{shared}', [ShareController::class, 'show']);
});

Route::get('/og-site/{shared}', WebCrawlerOpenGraphController::class);
