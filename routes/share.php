<?php

use App\Http\Controllers\FileFunctions\EditItemsController;
use App\Http\Controllers\Sharing\FileSharingController;

// Editor functions
Route::group(['prefix' => 'editor'], function () {
    Route::patch('/rename/{id}/public/{token}', [EditItemsController::class, 'guest_rename_item']);
    Route::post('/create-folder/public/{token}', [EditItemsController::class, 'guest_create_folder']);
    Route::post('/remove/public/{token}', [EditItemsController::class, 'guest_delete_item']);
    Route::post('/upload/public/{token}', [EditItemsController::class, 'guest_upload']);
    Route::post('/move/public/{token}', [EditItemsController::class, 'guest_move']);
});

// Editor/Visitor zip functions
Route::group(['prefix' => 'zip'], function () {
    Route::get('/folder/{id}/public/{token}', [EditItemsController::class, 'guest_zip_folder']);
    Route::post('/files/public/{token}', [EditItemsController::class, 'guest_zip_multiple_files']);
});

// Sharing page browsing
Route::get('/folders/{id}/public/{token}', [FileSharingController::class, 'get_public_folders']);
Route::get('/navigation/public/{token}', [FileSharingController::class, 'get_public_navigation_tree']);
Route::post('/shared/authenticate/{token}', [FileSharingController::class, 'authenticate']);
Route::get('/search/public/{token}', [FileSharingController::class, 'search_public']);
Route::get('/files/{token}/public', [FileSharingController::class, 'file_public']);
Route::get('/shared/{token}', [ShareController::class, 'show']);