<?php

namespace App\Http\Controllers;

use App;
use App\Http\Mail\SendContactMessage;
use App\Models\Content;
use App\Models\File;
use App\Models\Folder;
use App\Http\Requests\PublicPages\SendContactMessageRequest;
use App\Http\Resources\PageResource;
use App\Http\Tools\Demo;
use App\Models\Setting;
use App\Models\Page;
use App\Models\User;
use Artisan;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Schema;

class AppFunctionsController extends Controller
{
    /**
     * List of allowed settings to get from public request
     *
     * @var array
     */
    private $blacklist = [
        'contact_email',
        'purchase_code',
        'license',
    ];

    /**
     * Show index page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {
            // Try to connect to database
            \DB::getPdo();

            // Get setup status
            $setup_status = get_setup_status();

            // Get app pages
            $pages = Page::all();

            // Get all settings
            $settings = get_settings_in_json();

        } catch (PDOException $e) {

            $setup_status = 'setup-database';
        }

        return view("index")
            ->with('settings', $settings ?? null)
            ->with('legal', $pages ?? null)
            ->with('installation', $setup_status);
    }

    /**
     * Get og site for web crawlers
     *
     * @param $token
     */
    public function og_site($token)
    {
        // Get all settings
        $settings = get_settings_in_json();

        // Get shared token
        $shared = get_shared($token);

        // Get user
        $user = User::findOrFail($shared->user_id);

        // Handle single file
        if ($shared->type === 'file') {

            // Get file record
            $file = File::where('user_id', $shared->user_id)
                ->where('id', $shared->item_id)
                ->first();

            if ($file->thumbnail) {
                $file->setPublicUrl($token);
            }

            $metadata = [
                'is_protected' => $shared->is_protected,
                'url'          => url('/shared', ['token' => $token]),
                'user'         => $user->name,
                'name'         => $file->name,
                'size'         => $file->filesize,
                'thumbnail'    => $file->thumbnail ? $file->thumbnail : null,
            ];
        }

        // Handle single file
        if ($shared->type === 'folder') {

            // Get file record
            $folder = Folder::where('user_id', $shared->user_id)
                ->where('unique_id', $shared->item_id)
                ->first();

            $metadata = [
                'is_protected' => $shared->protected,
                'url'          => url('/shared', ['token' => $token]),
                'user'         => $user->name,
                'name'         => $folder->name,
                'size'         => $folder->items,
                'thumbnail'    => null,
            ];
        }

        // Return view
        return view("og-view")
            ->with('settings', $settings)
            ->with('metadata', $metadata);
    }

    /**
     * Send contact message from pages
     *
     * @param SendContactMessageRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function contact_form(SendContactMessageRequest $request)
    {
        Mail::to(
            get_setting('contact_email')
        )->send(
            new SendContactMessage($request->all())
        );

        return response('Done', 201);
    }

    /**
     * Get single page content
     *
     * @param Page $page
     * @return PageResource
     */
    public function get_page(Page $page)
    {
        return new PageResource($page);
    }

    /**
     * Get selected settings from public route
     *
     * @param Request $request
     * @return mixed
     */
    public function get_setting_columns(Request $request)
    {
        if (strpos($request->column, '|') !== false) {

            $columns = collect(explode('|', $request->column))
                ->each(function ($column) {
                    if (in_array($column, $this->blacklist)) {
                        abort(401);
                    }
                });

            return Setting::whereIn('name', $columns)
                ->pluck('value', 'name');
        }

        if (in_array($request->column, $this->blacklist)) {
            abort(401);
        }

        return Setting::where('name', $request->column)
            ->pluck('value', 'name');
    }

    /**
     * Clear application cache
     */
    public function flush_cache()
    {
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        if (!app()->runningUnitTests()) {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
