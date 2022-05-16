<?php

use Domain\Sharing\Controllers\ShareController;
use Domain\Zip\Controllers\VisitorZipController;
use Domain\Files\Controllers\VisitorShowFileController;
use Domain\Files\Controllers\VisitorUploadFileController;
use Domain\Folders\Controllers\VisitorCreateFolderController;
use Domain\Sharing\Controllers\WebCrawlerOpenGraphController;
use Domain\Browsing\Controllers\VisitorBrowseFolderController;
use Domain\Files\Controllers\VisitorUploadFileChunksController;
use Domain\Items\Controllers\VisitorMoveFileOrFolderController;
use Domain\Items\Controllers\VisitorDeleteFileOrFolderController;
use Domain\Items\Controllers\VisitorRenameFileOrFolderController;
use Domain\Sharing\Controllers\VisitorUnlockLockedShareController;
use Domain\Folders\Controllers\VisitorNavigationFolderTreeController;
use Domain\RemoteUpload\Controllers\VisitorRemoteUploadFileController;
use Domain\Browsing\Controllers\VisitorSearchFilesAndFoldersController;

// Creating
Route::post('/upload/remote/{shared}', VisitorRemoteUploadFileController::class);
Route::post('/upload/chunks/{shared}', VisitorUploadFileChunksController::class);
Route::post('/upload/{shared}', VisitorUploadFileController::class);

Route::post('/create-folder/{shared}', VisitorCreateFolderController::class);

// Editing
Route::patch('/rename/{id}/{shared}', VisitorRenameFileOrFolderController::class);
Route::post('/remove/{shared}', VisitorDeleteFileOrFolderController::class);
Route::post('/move/{shared}', VisitorMoveFileOrFolderController::class);

// Browse share content
Route::post('/authenticate/{shared}', VisitorUnlockLockedShareController::class);
Route::get('/navigation/{shared}', VisitorNavigationFolderTreeController::class);
Route::get('/folders/{id}/{shared}', VisitorBrowseFolderController::class);
Route::get('/file/{shared}', VisitorShowFileController::class);
Route::get('/{share}', [ShareController::class, 'show']);

// Others
Route::get('/search/{shared}', VisitorSearchFilesAndFoldersController::class);
Route::get('/og-site/{share}', WebCrawlerOpenGraphController::class);
Route::get('/zip/{shared}', VisitorZipController::class);
