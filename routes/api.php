<?php

use App\Users\Actions\CreateNewUserAction;
use Domain\Homepage\Controllers\SendContactMessageController;
use Domain\Sharing\Controllers\ShareController;
use Domain\Items\Controllers\EditItemsController;
use Domain\Trash\Controllers\DumpTrashController;
use App\Users\Controllers\ResetPasswordController;
use Domain\Zipping\Controllers\ZipFilesController;
use App\Users\Controllers\ForgotPasswordController;
use Domain\Folders\Controllers\FavouriteController;
use Domain\Zipping\Controllers\ZipFolderController;
use Domain\Homepage\Controllers\AppFunctionsController;
use Domain\Sharing\Controllers\ShareViaEmailController;
use Domain\Trash\Controllers\RestoreTrashContentController;
use Domain\Browsing\Controllers\BrowseLatestFilesController;
use Domain\Browsing\Controllers\BrowseSharedItemsController;
use Domain\Browsing\Controllers\BrowseTrashContentController;
use Domain\Browsing\Controllers\BrowseFolderContentController;
use Domain\Folders\Controllers\NavigationFolderTreeController;
use Domain\Browsing\Controllers\SearchFilesAndFoldersController;
use Domain\Browsing\Controllers\BrowseParticipantsUploadsController;

// Pages
Route::get('/content', [AppFunctionsController::class, 'get_setting_columns']);
Route::post('/contact', SendContactMessageController::class);
Route::get('/page/{page}', [AppFunctionsController::class, 'get_page']);
Route::get('/pricing', [AppFunctionsController::class, 'get_storage_plans']);

// Password reset
Route::group(['prefix' => 'password'], function () {
    Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/reset', [ResetPasswordController::class, 'reset']);
});

// Register user
Route::post('/register', CreateNewUserAction::class);

// User master Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Browse
    Route::group(['prefix' => 'browse'], function () {
        Route::get('/participants', BrowseParticipantsUploadsController::class);
        Route::get('/folders/{id}', BrowseFolderContentController::class);
        Route::get('/navigation', NavigationFolderTreeController::class);
        Route::get('/search', SearchFilesAndFoldersController::class);
        Route::get('/latest', BrowseLatestFilesController::class);
        Route::get('/trash', BrowseTrashContentController::class);
        Route::get('/share', BrowseSharedItemsController::class);
    });

    // Trash
    Route::group(['prefix' => 'trash'], function () {
        Route::post('/restore', RestoreTrashContentController::class);
        Route::delete('/dump', DumpTrashController::class);
    });

    // Favourites
    Route::apiResource('/folders/favourites', FavouriteController::class);

    // Share
    Route::post('/share/{token}/email', ShareViaEmailController::class);
    Route::apiResource('/share', ShareController::class);
});

// User master,editor routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/create-folder', [EditItemsController::class, 'create_folder']);
    Route::patch('/rename/{id}', [EditItemsController::class, 'rename_item']);
    Route::post('/remove', [EditItemsController::class, 'delete_item']);
    Route::post('/upload', [EditItemsController::class, 'upload']);
    Route::post('/move', [EditItemsController::class, 'move']);

    Route::group(['prefix' => '/zip'], function () {
        Route::post('/files', ZipFilesController::class);
        Route::get('/folder/{id}', ZipFolderController::class);
    });
});
