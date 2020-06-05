<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\User;
use Rinvex\Subscriptions\Models\PlanFeature;

Route::get('/debug', function () {


    /*
     * 1. Create plan
    */
    /*    $plan = app('rinvex.subscriptions.plan')->create([
            'name' => 'Starter Pack',
            'description' => 'The best for start with',
            'price' => 9.99,
            'signup_fee' => 0,
            'invoice_period' => 1,
            'invoice_interval' => 'month',
            'trial_period' => 7,
            'trial_interval' => 'day',
            'sort_order' => 1,
            'currency' => 'USD',
        ]);

        // Create multiple plan features at once
        $plan->features()->saveMany([
            new PlanFeature(['name' => 'Storage capacity', 'value' => 200, 'sort_order' => 1]),
        ]);

        return $plan;
    */

    /*
     * 2. Get plan
    */

/*    $plan = app('rinvex.subscriptions.plan')->find(6);

    return $plan;
    //return $plan->subscriptions;

    $space = $plan->getFeatureBySlug('storage-capacity')->value;*/

    //return $space;

    /*
     * 3. Create subscription
    */

    $user = Auth::user();
    //$plan = app('rinvex.subscriptions.plan')->find(6);

    //return $user->activeSubscriptions();

    return $user->subscription('Starter Pack')->cancel();

    //$user->newSubscription('Starter Pack', $plan);

    //return $plan->subscriptions;
    //return $user->subscribedTo(5);
});

// Deployment Webhook URL
Route::post('/deploy/github', 'DeployController@github');

// Get public thumbnails and files
Route::get('/thumbnail/{name}/public/{token}', 'FileAccessController@get_thumbnail_public');
Route::get('/avatars/{avatar}', 'FileAccessController@get_avatar')->name('avatar');
Route::get('/file/{name}/public/{token}', 'FileAccessController@get_file_public');

// User master,editor,visitor access to image thumbnails and file downloads
Route::group(['middleware' => ['auth:api', 'auth.shared', 'auth.master', 'scope:master,editor,visitor']], function () {
    Route::get('/thumbnail/{name}', 'FileAccessController@get_thumbnail')->name('thumbnail');
    Route::get('/file/{name}', 'FileAccessController@get_file')->name('file');
});

Route::group(['middleware' => ['auth:api', 'auth.master', 'scope:master']], function () {
    Route::get('/invoice/{token}', 'Admin\InvoiceController@show');
});

// Pages
Route::get('/shared/{token}', 'Sharing\FileSharingController@index');
Route::get('/{any?}', 'AppFunctionsController@index')->where('any', '.*');
