<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetupWizard\CreateAdminRequest;
use App\Http\Requests\SetupWizard\StoreAppSetupRequest;
use App\Http\Requests\SetupWizard\StoreDatabaseCredentialsRequest;
use App\Http\Requests\SetupWizard\StoreEnvironmentSetupRequest;
use App\Http\Requests\SetupWizard\StoreStripeBillingRequest;
use App\Http\Requests\SetupWizard\StoreStripeCredentialsRequest;
use App\Http\Requests\SetupWizard\StoreStripePlansRequest;
use App\Page;
use App\Services\StripeService;
use App\Setting;
use App\User;
use App\UserSettings;
use Artisan;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Schema;
use Stripe;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SetupWizardController extends Controller
{
    /**
     * Inject Stripe Service
     */
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * Verify Envato purchase code
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function verify_purchase_code(Request $request)
    {
        // Check setup status
        if ($this->get_setup_status()) abort(410, 'Gone');

        // Verify purchase code
        $response = Http::get('https://verify.vuefilemanager.com/api/verify-code/' . $request->purchaseCode);

        if ($response->successful()) {
            return $response;
        }

        return response('Purchase code is invalid.', 400);
    }

    /**
     * Set up database credentials
     *
     * @param StoreDatabaseCredentialsRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function setup_database(StoreDatabaseCredentialsRequest $request)
    {
        // Check setup status
        if ($this->get_setup_status()) abort(410, 'Gone');

        try {
            // Set temporary database connection
            config(['database.connections.test.driver' => $request->connection]);
            config(['database.connections.test.host' => $request->host]);
            config(['database.connections.test.port' => $request->port]);
            config(['database.connections.test.database' => $request->name]);
            config(['database.connections.test.username' => $request->username]);
            config(['database.connections.test.password' => $request->password]);

            // Test connection
            \DB::connection('test')->getPdo();

        } catch (PDOException $e) {
            throw new HttpException(500, $e->getMessage());
        }

        setEnvironmentValue([
            'DB_CONNECTION' => $request->connection,
            'DB_HOST'       => $request->host,
            'DB_PORT'       => $request->port,
            'DB_DATABASE'   => $request->name,
            'DB_USERNAME'   => $request->username,
            'DB_PASSWORD'   => $request->password,
        ]);

        // Clear cache
        Artisan::call('config:cache');

        // Set up application
        $this->set_up_application();

        // Store setup wizard progress
        Setting::create([
            'name'  => 'setup_wizard_database',
            'value' => 1,
        ]);

        return response('Done', 200);
    }

    /**
     * Store and test stripe credentials
     *
     * @param StoreStripeCredentialsRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function store_stripe_credentials(StoreStripeCredentialsRequest $request)
    {
        // Check setup status
        if ($this->get_setup_status()) abort(410, 'Gone');

        // Create stripe instance
        $stripe = Stripe::make($request->secret, '2020-03-02');

        // Try to get stripe account details
        try {
            $stripe->account()->details();
        } catch (UnauthorizedException $e) {
            throw new HttpException(401, $e->getMessage());
        }

        // Get options
        $settings = collect([
            [
                'name'  => 'stripe_currency',
                'value' => $request->currency,
            ],
            [
                'name'  => 'payments_configured',
                'value' => 1,
            ],
            [
                'name'  => 'payments_active',
                'value' => 1,
            ],
        ]);

        // Store options
        $settings->each(function ($col) {
            Setting::updateOrCreate(['name' => $col['name']], $col);
        });

        // Set stripe credentials to .env
        setEnvironmentValue([
            'CASHIER_CURRENCY'      => $request->currency,
            'STRIPE_KEY'            => $request->key,
            'STRIPE_SECRET'         => $request->secret,
            'STRIPE_WEBHOOK_SECRET' => $request->webhookSecret,
        ]);

        // Clear cache
        Artisan::call('config:cache');

        return response('Done', 200);
    }

    /**
     * Store Stripe billings
     *
     * @param StoreStripeBillingRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function store_stripe_billings(StoreStripeBillingRequest $request)
    {
        // Check setup status
        if ($this->get_setup_status()) abort(410, 'Gone');

        // Get options
        $settings = collect([
            [
                'name'  => 'billing_phone_number',
                'value' => $request->billing_phone_number,
            ],
            [
                'name'  => 'billing_postal_code',
                'value' => $request->billing_postal_code,
            ],
            [
                'name'  => 'billing_vat_number',
                'value' => $request->billing_vat_number,
            ],
            [
                'name'  => 'billing_address',
                'value' => $request->billing_address,
            ],
            [
                'name'  => 'billing_country',
                'value' => $request->billing_country,
            ],
            [
                'name'  => 'billing_state',
                'value' => $request->billing_state,
            ],
            [
                'name'  => 'billing_city',
                'value' => $request->billing_city,
            ],
            [
                'name'  => 'billing_name',
                'value' => $request->billing_name,
            ],
        ]);

        // Store options
        $settings->each(function ($col) {
            Setting::updateOrCreate(['name' => $col['name']], $col);
        });

        // Clear cache
        Artisan::call('config:cache');

        return response('Done', 200);
    }

    /**
     * Create Stripe subscription plan
     *
     * @param StoreStripePlansRequest $request
     */
    public function store_stripe_plans(StoreStripePlansRequest $request)
    {
        // Check setup status
        if ($this->get_setup_status()) abort(410, 'Gone');

        foreach ($request->input('plans') as $plan) {
            $this->stripe->createPlan($plan);
        }
    }

    /**
     * Store environment setup
     *
     * @param StoreEnvironmentSetupRequest $request
     * @return string
     */
    public function store_environment_setup(StoreEnvironmentSetupRequest $request)
    {
        // Check setup status
        if ($this->get_setup_status()) abort(410, 'Gone');

        $storage_driver = $request->input('storage.driver');

        if ($storage_driver === 'local') {

            setEnvironmentValue([
                'FILESYSTEM_DRIVER' => 'local',
            ]);
        }

        if ($storage_driver === 's3') {

            setEnvironmentValue([
                'FILESYSTEM_DRIVER'     => $request->input('storage.driver'),
                'AWS_ACCESS_KEY_ID'     => $request->input('storage.key'),
                'AWS_SECRET_ACCESS_KEY' => $request->input('storage.secret'),
                'AWS_DEFAULT_REGION'    => $request->input('storage.region'),
                'AWS_BUCKET'            => $request->input('storage.bucket'),
            ]);
        }

        if ($storage_driver === 'spaces') {

            setEnvironmentValue([
                'FILESYSTEM_DRIVER'  => $request->input('storage.driver'),
                'DO_SPACES_KEY'      => $request->input('storage.key'),
                'DO_SPACES_SECRET'   => $request->input('storage.secret'),
                'DO_SPACES_ENDPOINT' => $request->input('storage.endpoint'),
                'DO_SPACES_REGION'   => $request->input('storage.region'),
                'DO_SPACES_BUCKET'   => $request->input('storage.bucket'),
            ]);
        }

        if ($storage_driver === 'wasabi') {

            setEnvironmentValue([
                'FILESYSTEM_DRIVER' => $request->input('storage.driver'),
                'WASABI_KEY'        => $request->input('storage.key'),
                'WASABI_SECRET'     => $request->input('storage.secret'),
                'WASABI_ENDPOINT'   => $request->input('storage.endpoint'),
                'WASABI_REGION'     => $request->input('storage.region'),
                'WASABI_BUCKET'     => $request->input('storage.bucket'),
            ]);
        }

        if ($storage_driver === 'backblaze') {

            setEnvironmentValue([
                'FILESYSTEM_DRIVER'  => $request->input('storage.driver'),
                'BACKBLAZE_KEY'      => $request->input('storage.key'),
                'BACKBLAZE_SECRET'   => $request->input('storage.secret'),
                'BACKBLAZE_ENDPOINT' => $request->input('storage.endpoint'),
                'BACKBLAZE_REGION'   => $request->input('storage.region'),
                'BACKBLAZE_BUCKET'   => $request->input('storage.bucket'),
            ]);
        }

        setEnvironmentValue([
            'MAIL_DRIVER'     => $request->input('mail.driver'),
            'MAIL_HOST'       => $request->input('mail.host'),
            'MAIL_PORT'       => $request->input('mail.port'),
            'MAIL_USERNAME'   => $request->input('mail.username'),
            'MAIL_PASSWORD'   => $request->input('mail.password'),
            'MAIL_ENCRYPTION' => $request->input('mail.encryption'),
        ]);

        // Clear cache
        Artisan::call('config:cache');

        return response('Done', 200);
    }

    /**
     * Store app settings
     * @param StoreAppSetupRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function store_app_settings(StoreAppSetupRequest $request)
    {
        // Check setup status
        if ($this->get_setup_status()) abort(410, 'Gone');

        // Store Logo
        if ($request->hasFile('logo')) {
            $logo = store_system_image($request->file('logo'), 'system');
        }

        // Store Logo horizontal
        if ($request->hasFile('logo_horizontal')) {
            $logo_horizontal = store_system_image($request->file('logo_horizontal'), 'system');
        }

        // Store favicon
        if ($request->hasFile('favicon')) {
            $favicon = store_system_image($request->file('favicon'), 'system');
        }

        // Get options
        $settings = collect([
            [
                'name'  => 'app_title',
                'value' => $request->title,
            ],
            [
                'name'  => 'app_description',
                'value' => $request->description,
            ],
            [
                'name'  => 'app_logo',
                'value' => $request->hasFile('logo') ? $logo : null,
            ],
            [
                'name'  => 'app_logo_horizontal',
                'value' => $request->hasFile('logo_horizontal') ? $logo_horizontal : null,
            ],
            [
                'name'  => 'app_favicon',
                'value' => $request->hasFile('favicon') ? $favicon : null,
            ],
            [
                'name'  => 'google_analytics',
                'value' => $request->googleAnalytics,
            ],
            [
                'name'  => 'contact_email',
                'value' => $request->contactMail,
            ],
            [
                'name'  => 'registration',
                'value' => $request->userRegistration,
            ],
            [
                'name'  => 'storage_limitation',
                'value' => $request->storageLimitation,
            ],
            [
                'name'  => 'storage_default',
                'value' => $request->defaultStorage ? $request->defaultStorage : 5,
            ],
        ]);

        // Store options
        $settings->each(function ($col) {
            Setting::updateOrCreate(['name' => $col['name']], $col);
        });

        setEnvironmentValue([
            'APP_NAME'     => Str::camel($request->title),
        ]);

        return response('Done', 200);
    }

    /**
     * Create and login admin account
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function create_admin_account(Request $request)
    {
        // Check setup status
        if ($this->get_setup_status()) abort(410, 'Gone');

        // Validate request
        $request->validate([
            'email'         => 'required|string|email|unique:users',
            'password'      => 'required|string|min:6|confirmed',
            'name'          => 'required|string',
            'purchase_code' => 'required|string',
            'license'       => 'required|string',
            'avatar'        => 'sometimes|file',
        ]);

        // Store avatar
        if ($request->hasFile('avatar')) {
            $avatar = store_avatar($request->file('avatar'), 'avatars');
        }

        // Create user
        $user = User::forceCreate([
            'avatar'   => $request->hasFile('avatar') ? $avatar : null,
            'name'     => $request->name,
            'role'     => 'admin',
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Get default storage capacity
        $storage_capacity = Setting::where('name', 'storage_default')->first();

        // Create settings
        UserSettings::forceCreate([
            'user_id'          => $user->id,
            'storage_capacity' => $storage_capacity->value,
        ]);

        // Store setup wizard progress
        Setting::updateOrCreate([
            'name'  => 'setup_wizard_success',
            'value' => 1,
        ]);

        // Store License
        Setting::updateOrCreate([
            'name'  => 'license',
            'value' => $request->license,
        ]);

        // Store Purchase Code
        Setting::updateOrCreate([
            'name'  => 'purchase_code',
            'value' => $request->purchase_code,
        ]);

        // Create legal pages and index content
        if ($request->license === 'Extended') {

            $pages = collect(config('content.pages'));
            $content = collect(config('content.content'));

            $content->each(function ($content) {
                Setting::updateOrCreate($content);
            });

            $pages->each(function ($page) {
                Page::updateOrCreate($page);
            });
        }

        // Retrieve access token
        $response = Route::dispatch(self::make_login_request($request));

        // Send access token to user if request is successful
        if ($response->isSuccessful()) {

            $data = json_decode($response->content(), true);

            return response('Admin was created', 200)->cookie('access_token', $data['access_token'], 43200);
        }

        return $response;
    }

    /**
     * Migrate database and generate necessary things
     */
    private function set_up_application()
    {
        // Generate app key
        Artisan::call('key:generate', [
            '--force' => true
        ]);

        // Migrate database
        Artisan::call('migrate:fresh', [
            '--force' => true
        ]);

        // Create Passport Keys
        Artisan::call('passport:keys', [
            '--force' => true
        ]);

        // Create Password grant client
        Artisan::call('passport:client', [
            '--password' => true,
            '--name'     => 'vuefilemanager',
        ]);

        // Create Personal access client
        Artisan::call('passport:client', [
            '--personal' => true,
            '--name'     => 'shared',
        ]);

        // Get generated client
        $client = \DB::table('oauth_clients')->where('name', '=', 'vuefilemanager')->first();

        // Set passport client to .env
        setEnvironmentValue([
            'PASSPORT_CLIENT_ID'     => $client->id,
            'PASSPORT_CLIENT_SECRET' => $client->secret,
        ]);
    }

    /**
     * Make login request for get access token
     *
     * @param Request $request
     * @return Request
     */
    private static function make_login_request($request)
    {
        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => 'master',
        ]);

        return Request::create(url('/oauth/token'), 'POST', $request->all());
    }

    /**
     * Get setup wizard status
     *
     * @return |null
     */
    private function get_setup_status()
    {
        try {
            // Check database connections
            DB::getPdo();

            // Get setup_wizard status
            return Schema::hasTable('settings') ? Setting::where('name', 'setup_wizard_success')->first() : false;

        } catch (PDOException $e) {

            return false;
        }
    }
}
