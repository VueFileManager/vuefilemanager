<?php

use App\Users\Actions\CreateNewUserAction;
use Domain\Browsing\Controllers\BrowseFolderContentController;
use Domain\Browsing\Controllers\BrowseLatestFilesController;
use Domain\Browsing\Controllers\BrowseParticipantsUploadsController;
use Domain\Browsing\Controllers\BrowseSharedItemsController;
use Domain\Browsing\Controllers\BrowseTrashContentController;
use Domain\Browsing\Controllers\SearchFilesAndFoldersController;
use Domain\Folders\Controllers\NavigationFolderTreeController;
use Domain\Trash\Controllers\TrashController;
use Domain\Sharing\Controllers\ShareController;
use Domain\Browsing\Controllers\BrowseController;
use Domain\Items\Controllers\EditItemsController;
use App\Users\Controllers\ResetPasswordController;
use App\Users\Controllers\ForgotPasswordController;
use Domain\Folders\Controllers\FavouriteController;
use Domain\Homepage\Controllers\AppFunctionsController;

// Pages
Route::get('/content', [AppFunctionsController::class, 'get_setting_columns']);
Route::post('/contact', [AppFunctionsController::class, 'contact_form']);
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
        Route::get('/navigation', NavigationFolderTreeController::class);
        Route::get('/folders/{id}', BrowseFolderContentController::class);
        Route::get('/share', BrowseSharedItemsController::class);
        Route::get('/latest', BrowseLatestFilesController::class);
        Route::get('/search', SearchFilesAndFoldersController::class);
        Route::get('/trash', BrowseTrashContentController::class);
    });

    // Trash
    Route::group(['prefix' => 'trash'], function () {
        Route::post('/restore', [TrashController::class, 'restore']);
        Route::delete('/dump', [TrashController::class, 'dump']);
    });

    // Favourites
    Route::group(['prefix' => 'folders'], function () {
        Route::delete('/favourites/{id}', [FavouriteController::class, 'destroy']);
        Route::post('/favourites', [FavouriteController::class, 'store']);
    });

    // Share
    Route::group(['prefix' => 'share'], function () {
        Route::post('/{token}/email', [ShareController::class, 'send_to_emails_recipients']);
        Route::delete('/revoke', [ShareController::class, 'destroy']);
        Route::patch('/{token}', [ShareController::class, 'update']);
        Route::post('/{id}', [ShareController::class, 'store']);
    });
});

// User master,editor routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/create-folder', [EditItemsController::class, 'create_folder']);
    Route::patch('/rename/{id}', [EditItemsController::class, 'rename_item']);
    Route::post('/remove', [EditItemsController::class, 'delete_item']);
    Route::post('/upload', [EditItemsController::class, 'upload']);
    Route::post('/move', [EditItemsController::class, 'move']);

    Route::group(['prefix' => 'zip'], function () {
        Route::post('/files', [EditItemsController::class, 'zip_multiple_files']);
        Route::get('/folder/{id}', [EditItemsController::class, 'zip_folder']);
    });
});
