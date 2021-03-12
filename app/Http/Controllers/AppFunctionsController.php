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
    private $whitelist = [
        'section_features',
        'footer_content',
        'get_started_description',
        'get_started_title',
        'pricing_description',
        'pricing_title',
        'feature_description_3',
        'feature_title_3',
        'feature_description_2',
        'feature_title_2',
        'feature_description_1',
        'feature_title_1',
        'features_description',
        'features_title',
        'header_description',
        'header_title',
        'section_get_started',
        'section_pricing_content',
        'section_feature_boxes',
        'allow_homepage',
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
            $setup_status = $this->get_setup_status();

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
                'is_protected' => $shared->protected,
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
                'url'       => url('/shared', ['token' => $token]),
                'user'      => $user->name,
                'name'      => $folder->name,
                'size'      => $folder->items,
                'thumbnail' => null,
            ];
        }

        // Return view
        return view("og-view")
            ->with('settings', $settings)
            ->with('metadata', $metadata);
    }

    /**
     * Check if setup wizard was passed
     *
     * @return string
     */
    private function get_setup_status(): string
    {
        $setup_success = get_setting('setup_wizard_success');

        return boolval($setup_success) ? 'setup-done' : 'setup-disclaimer';
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
     * @param $slug
     * @return PageResource
     */
    public function get_page($slug)
    {
        return new PageResource(
            Page::where('slug', $slug)->first()
        );
    }

    /**
     * Get selected settings from public route
     *
     * @param Request $request
     * @return mixed
     */
    public function get_settings(Request $request)
    {
        $column = $request->get('column');

        if (strpos($column, '|') !== false) {

            $columns = collect(explode('|', $column));

            $columns->each(function ($column) {
                if (!in_array($column, $this->whitelist)) abort(401);
            });

            return Setting::whereIn('name', $columns)->pluck('value', 'name');
        }

        if (!in_array($column, $this->whitelist)) abort(401);

        return Setting::where('name', $column)->pluck('value', 'name');
    }

    /**
     * Clear application cache
     */
    public function flush_cache()
    {
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        if (! app()->runningUnitTests()) {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
