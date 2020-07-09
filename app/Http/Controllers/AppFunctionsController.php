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

        } catch (PDOException $e) {
            $connection = 'setup-database';
            $settings = null;
        }

        return view("index")
            ->with('settings', $settings)
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
