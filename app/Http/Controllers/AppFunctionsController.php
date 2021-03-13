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
use Illuminate\Support\Facades\Cookie;
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
        $shared = get_shared($token);

        // Get file/folder record
        $item = ('App\\Models\\' . ucfirst($shared->type))
            ::where('user_id', $shared->user->id)
            ->where('id', $shared->item_id)
            ->first();

        if ($item->thumbnail) {
            $item->setPublicUrl($token);
        }

        return view("vuefilemanager.crawler.og-view")
            ->with('settings', get_settings_in_json())
            ->with('metadata', [
                'url'          => url('/shared', ['token' => $token]),
                'is_protected' => $shared->is_protected,
                'user'         => $shared->user->settings->name,
                'name'         => $item->name,
                'size'         => $shared->type === 'folder'
                    ? $item->items
                    : $item->filesize,
                'thumbnail'    => $item->thumbnail ?? null,
            ]);
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
