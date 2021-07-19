<?php
namespace Domain\Homepage\Controllers;

use Illuminate\Http\Request;
use Domain\Pages\Models\Page;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Database\QueryException;
use Domain\Localization\Models\Language;
use Domain\Pages\Resources\PageResource;
use Domain\Homepage\Mail\SendContactMessage;
use Domain\Plans\Resources\PricingCollection;
use Domain\Subscriptions\Services\StripeService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\Homepage\Requests\SendContactMessageRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppFunctionsController extends Controller
{
    /**
     * List of allowed settings to get from public request
     *
     * @var array
     */
    private array $blacklist = [
        'purchase_code',
        'license',
    ];

    public function __construct(
        private StripeService $stripe
    ) {
    }

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

        return view('index')
            ->with('settings', $settings ?? null)
            ->with('legal', $pages ?? null)
            ->with('installation', $setup_status);
    }

    /**
     * Get og site for web crawlers
     *
     * @param Share $shared
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function og_site(Share $shared)
    {
        $namespace = match ($shared->type) {
            'folder' => 'Domain\\Folders\\Models\\Folder',
            'file'   => 'Domain\\Files\\Models\\File',
        };

        // Get file/folder record
        $item = ($namespace)::where('user_id', $shared->user->id)
            ->where('id', $shared->item_id)
            ->first();

        if ($item->thumbnail) {
            $item->setPublicUrl($shared->token);
        }

        return view('vuefilemanager.crawler.og-view')
            ->with('settings', get_settings_in_json())
            ->with('metadata', [
                'url'          => url('/share', ['token' => $shared->token]),
                'is_protected' => $shared->is_protected,
                'user'         => $shared->user->settings->name,
                'name'         => $item->name,
                'size'         => $shared->type === 'folder'
                    ? $item->items
                    : $item->filesize,
                'thumbnail' => $item->thumbnail ?? null,
            ]);
    }

    /**
     * Send contact message from pages
     *
     * @param SendContactMessageRequest $request
     * @return ResponseFactory|Response
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
     * Get all active storage plans
     *
     * @return PricingCollection
     */
    public function get_storage_plans()
    {
        // Get pricing from cache
        $pricing = Cache::rememberForever('pricing', function () {
            return $this->stripe->getActivePlans();
        });

        // Format pricing to collection
        $collection = new PricingCollection($pricing);

        // Sort and return pricing
        return $collection
            ->sortBy('product.metadata.capacity')
            ->values()
            ->all();
    }

    /**
     * Get language translations for frontend app
     */
    public function get_translations($lang)
    {
        $translations = cache()
            ->rememberForever("language-translations-$lang", function () use ($lang) {
                try {
                    return Language::whereLocale($lang)
                        ->firstOrFail()
                        ->languageTranslations;
                } catch (QueryException | ModelNotFoundException $e) {
                    return null;
                }
            });

        return $translations
            ? map_language_translations($translations)
            : get_default_language_translations();
    }
}
