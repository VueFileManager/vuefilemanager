<?php

use App\Http\Controllers\AppFunctionsController;
use App\Http\Controllers\FileManager\EditItemsController;
use App\Http\Controllers\FileManager\ShareController;
use App\Http\Controllers\Sharing\FileSharingController;

// Editor functions
Route::group(['prefix' => 'editor'], function () {
    Route::post('/create-folder/public/{token}', [EditItemsController::class, 'guest_create_folder']);
    Route::patch('/rename/{id}/public/{token}', [EditItemsController::class, 'guest_rename_item']);
    Route::post('/remove/public/{token}', [EditItemsController::class, 'guest_delete_item']);
    Route::post('/upload/public/{token}', [EditItemsController::class, 'guest_upload']);
    Route::post('/move/public/{token}', [EditItemsController::class, 'guest_move']);
});

// Editor/Visitor zip functions
Route::group(['prefix' => 'zip'], function () {
    Route::post('/files/public/{token}', [EditItemsController::class, 'guest_zip_multiple_files']);
    Route::get('/folder/{id}/public/{token}', [EditItemsController::class, 'guest_zip_folder']);
});

// Browse share content
Route::group(['prefix' => 'browse'], function () {
    Route::get('/navigation/public/{token}', [FileSharingController::class, 'get_public_navigation_tree']);
    Route::get('/folders/{id}/public/{token}', [FileSharingController::class, 'get_public_folders']);
    Route::post('/shared/authenticate/{token}', [FileSharingController::class, 'authenticate']);
    Route::get('/search/public/{token}', [FileSharingController::class, 'search_public']);
    Route::get('/files/{token}/public', [FileSharingController::class, 'file_public']);
    Route::get('/shared/{token}', [ShareController::class, 'show']);
});

Route::get('/og-site/{token}', [AppFunctionsController::class, 'og_site']);