<?php

namespace App\Http\Controllers;

use App\Content;
use App\Http\Requests\PublicPages\SendMessageRequest;
use App\Http\Resources\PageResource;
use App\Mail\SendSupportForm;
use App\Page;
use App\Setting;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppFunctionsController extends Controller
{
    /**
     * List of allowed settings to get from public request
     *
     * @var array
     */
    private $whitelist = [
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
        'section_features',
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

            $connection = $this->get_setup_status();
            $settings = json_decode(Setting::all()->pluck('value', 'name')->toJson());
            $legal = Page::whereIn('slug', ['terms-of-service', 'privacy-policy', 'cookie-policy'])->get(['visibility', 'title', 'slug']);

        } catch (PDOException $e) {
            $connection = 'setup-database';
            $settings = null;
        }

        return view("index")
            ->with('settings', $settings)
            ->with('legal', isset($legal) ? $legal : null)
            ->with('installation', $connection);
    }

    /**
     * Send contact message from pages
     *
     * @param SendMessageRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function contact_form(SendMessageRequest $request)
    {
        // Get receiver email
        $receiver = Setting::where('name', 'contact_email')->first();

        // Send message
        Mail::to($receiver->value)->send(new SendSupportForm($request->all()));

        return response('Done', 200);
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
                if (! in_array($column, $this->whitelist)) abort(401);
            });

            return Setting::whereIn('name', $columns)->pluck('value', 'name');
        }

        if (! in_array($column, $this->whitelist)) abort(401);

        return Setting::where('name', $column)->pluck('value', 'name');
    }

    /**
     * Check if setup wizard was passed
     *
     * @return string
     */
    private function get_setup_status(): string
    {
        $setup_success = Setting::where('name', 'setup_wizard_success')->first();

        $connection = $setup_success ? 'setup-done' : 'setup-disclaimer';

        return $connection;
    }
}
