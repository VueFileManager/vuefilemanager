<?php
namespace App\Http\Controllers\App;

use Schema;
use Stripe;
use Artisan;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\SetupService;
use App\Services\StripeService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use App\Http\Requests\SetupWizard\StoreAppSetupRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Requests\SetupWizard\StoreStripePlansRequest;
use App\Http\Requests\SetupWizard\StoreStripeBillingRequest;
use App\Http\Requests\SetupWizard\StoreEnvironmentSetupRequest;
use App\Http\Requests\SetupWizard\StoreStripeCredentialsRequest;
use App\Http\Requests\SetupWizard\StoreDatabaseCredentialsRequest;

class SetupWizardController extends Controller
{
    /**
     * Inject Stripe Service
     */
    public function __construct()
    {
        $this->stripe = resolve(StripeService::class);
        $this->setup = resolve(SetupService::class);

        $this->check_setup_status();
    }

    /**
     * Verify Envato purchase code
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function verify_purchase_code(Request $request)
    {
        // Verify purchase code
        $response = Http::get('https://verify.vuefilemanager.com/api/verify-code/' . $request->purchaseCode);

        if ($response->successful()) {
            return response($response, 204);
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
        if (! app()->runningUnitTests()) {
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

            // TODO: add SANCTUM_STATEFUL_DOMAINS parameter

            setEnvironmentValue([
                'DB_CONNECTION' => $request->connection,
                'DB_HOST' => $request->host,
                'DB_PORT' => $request->port,
                'DB_DATABASE' => $request->name,
                'DB_USERNAME' => $request->username,
                'DB_PASSWORD' => $request->password,
            ]);

            Artisan::call('config:cache');

            Artisan::call('key:generate', [
                '--force' => true,
            ]);

            Artisan::call('migrate:fresh', [
                '--force' => true,
            ]);
        }

        // Store setup wizard progress
        Setting::forceCreate([
            'name' => 'setup_wizard_database',
            'value' => 1,
        ]);

        return response('Done', 204);
    }

    /**
     * Store and test stripe credentials
     *
     * @param StoreStripeCredentialsRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function store_stripe_credentials(StoreStripeCredentialsRequest $request)
    {
        if (! app()->runningUnitTests()) {
            // Create stripe instance
            $stripe = Stripe::make($request->secret, '2020-03-02');

            try {
                // Try to get stripe account details
                $stripe->account()->details();
            } catch (UnauthorizedException $e) {
                throw new HttpException(401, $e->getMessage());
            }
        }

        // Set settings
        collect([
            [
                'name' => 'stripe_currency',
                'value' => $request->currency,
            ],
            [
                'name' => 'payments_configured',
                'value' => 1,
            ],
            [
                'name' => 'payments_active',
                'value' => 1,
            ],
        ])->each(function ($col) {
            Setting::forceCreate([
                'name' => $col['name'],
                'value' => $col['value'],
            ]);
        });

        if (! app()->runningUnitTests()) {
            // Set stripe credentials to .env
            setEnvironmentValue([
                'CASHIER_CURRENCY' => $request->currency,
                'STRIPE_KEY' => $request->key,
                'STRIPE_SECRET' => $request->secret,
                'STRIPE_WEBHOOK_SECRET' => $request->webhookSecret,
            ]);

            // Clear cache
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }

    /**
     * Store Stripe billings
     *
     * @param StoreStripeBillingRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function store_stripe_billings(StoreStripeBillingRequest $request)
    {
        // Get options
        collect([
            [
                'name' => 'billing_phone_number',
                'value' => $request->billing_phone_number,
            ],
            [
                'name' => 'billing_postal_code',
                'value' => $request->billing_postal_code,
            ],
            [
                'name' => 'billing_vat_number',
                'value' => $request->billing_vat_number,
            ],
            [
                'name' => 'billing_address',
                'value' => $request->billing_address,
            ],
            [
                'name' => 'billing_country',
                'value' => $request->billing_country,
            ],
            [
                'name' => 'billing_state',
                'value' => $request->billing_state,
            ],
            [
                'name' => 'billing_city',
                'value' => $request->billing_city,
            ],
            [
                'name' => 'billing_name',
                'value' => $request->billing_name,
            ],
        ])->each(function ($col) {
            Setting::forceCreate([
                'name' => $col['name'],
                'value' => $col['value'],
            ]);
        });

        if (! app()->runningUnitTests()) {
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }

    /**
     * Create Stripe subscription plan
     *
     * @param StoreStripePlansRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function store_stripe_plans(StoreStripePlansRequest $request)
    {
        foreach ($request->plans as $plan) {
            $this->stripe->createPlan($plan);
        }

        return response('Done', 204);
    }

    /**
     * Store environment setup
     *
     * @param StoreEnvironmentSetupRequest $request
     * @return string
     */
    public function store_environment_setup(StoreEnvironmentSetupRequest $request)
    {
        if (! app()->runningUnitTests()) {
            $drivers = [
                'local' => [
                    'FILESYSTEM_DRIVER' => 'local',
                ],
                's3' => [
                    'FILESYSTEM_DRIVER' => $request->storage['driver'] ?? null,
                    'AWS_ACCESS_KEY_ID' => $request->storage['key'] ?? null,
                    'AWS_SECRET_ACCESS_KEY' => $request->storage['secret'] ?? null,
                    'AWS_DEFAULT_REGION' => $request->storage['region'] ?? null,
                    'AWS_BUCKET' => $request->storage['bucket'] ?? null,
                ],
                'spaces' => [
                    'FILESYSTEM_DRIVER' => $request->storage['driver'] ?? null,
                    'DO_SPACES_KEY' => $request->storage['key'] ?? null,
                    'DO_SPACES_SECRET' => $request->storage['secret'] ?? null,
                    'DO_SPACES_ENDPOINT' => $request->storage['endpoint'] ?? null,
                    'DO_SPACES_REGION' => $request->storage['region'] ?? null,
                    'DO_SPACES_BUCKET' => $request->storage['bucket'] ?? null,
                ],
                'wasabi' => [
                    'FILESYSTEM_DRIVER' => $request->storage['driver'] ?? null,
                    'WASABI_KEY' => $request->storage['key'] ?? null,
                    'WASABI_SECRET' => $request->storage['secret'] ?? null,
                    'WASABI_ENDPOINT' => $request->storage['endpoint'] ?? null,
                    'WASABI_REGION' => $request->storage['region'] ?? null,
                    'WASABI_BUCKET' => $request->storage['bucket'] ?? null,
                ],
                'backblaze' => [
                    'FILESYSTEM_DRIVER' => $request->storage['driver'] ?? null,
                    'BACKBLAZE_KEY' => $request->storage['key'] ?? null,
                    'BACKBLAZE_SECRET' => $request->storage['secret'] ?? null,
                    'BACKBLAZE_ENDPOINT' => $request->storage['endpoint'] ?? null,
                    'BACKBLAZE_REGION' => $request->storage['region'] ?? null,
                    'BACKBLAZE_BUCKET' => $request->storage['bucket'] ?? null,
                ],
                'oss' => [
                    'FILESYSTEM_DRIVER' => $request->storage['driver'] ?? null,
                    'OSS_ACCESS_KEY_ID' => $request->storage['key'] ?? null,
                    'OSS_SECRET_ACCESS_KEY' => $request->storage['secret'] ?? null,
                    'OSS_ENDPOINT' => $request->storage['endpoint'] ?? null,
                    'OSS_REGION' => $request->storage['region'] ?? null,
                    'OSS_BUCKET' => $request->storage['bucket'] ?? null,
                ],
            ];

            // Storage credentials for storage
            setEnvironmentValue(
                $drivers[$request->storage['driver']]
            );

            // Store credentials for mail
            // TODO: add options for mailgun
            setEnvironmentValue([
                'MAIL_DRIVER' => $request->mail['driver'],
                'MAIL_HOST' => $request->mail['host'],
                'MAIL_PORT' => $request->mail['port'],
                'MAIL_USERNAME' => $request->mail['username'],
                'MAIL_PASSWORD' => $request->mail['password'],
                'MAIL_ENCRYPTION' => $request->mail['encryption'],
            ]);

            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }

    /**
     * Store app settings
     * @param StoreAppSetupRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function store_app_settings(StoreAppSetupRequest $request)
    {
        // Get options
        collect([
            [
                'name' => 'app_title',
                'value' => $request->title,
            ],
            [
                'name' => 'app_description',
                'value' => $request->description,
            ],
            [
                'name' => 'app_logo',
                'value' => store_system_image($request, 'logo'),
            ],
            [
                'name' => 'app_logo_horizontal',
                'value' => store_system_image($request, 'logo_horizontal'),
            ],
            [
                'name' => 'app_favicon',
                'value' => store_system_image($request, 'favicon'),
            ],
            [
                'name' => 'app_og_image',
                'value' => store_system_image($request, 'og_image'),
            ],
            [
                'name' => 'app_touch_icon',
                'value' => store_system_image($request, 'touch_icon'),
            ],
            [
                'name' => 'google_analytics',
                'value' => $request->googleAnalytics,
            ],
            [
                'name' => 'contact_email',
                'value' => $request->contactMail,
            ],
            [
                'name' => 'registration',
                'value' => $request->userRegistration,
            ],
            [
                'name' => 'storage_limitation',
                'value' => $request->storageLimitation,
            ],
            [
                'name' => 'storage_default',
                'value' => $request->defaultStorage ?? 5,
            ],
        ])->each(function ($col) {
            Setting::forceCreate([
                'name' => $col['name'],
                'value' => $col['value'],
            ]);
        });

        if (! app()->runningUnitTests()) {
            setEnvironmentValue([
                'APP_NAME' => Str::camel($request->title),
            ]);
        }

        return response('Done', 204);
    }

    /**
     * Create and login admin account
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function create_admin_account(Request $request)
    {
        // Validate request
        // TODO: validator do requestu
        $request->validate([
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'name' => 'required|string',
            'purchase_code' => 'required|string',
            'license' => 'required|string',
            'avatar' => 'sometimes|file',
        ]);

        // Create user
        $user = User::forceCreate([
            'role' => 'admin',
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user
            ->settings()
            ->create([
                'storage_capacity' => get_setting('storage_default') ?? 5,
                'avatar' => store_avatar($request, 'avatar'),
                'name' => $request->name,
            ]);

        collect([
            [
                'name' => 'setup_wizard_success',
                'value' => 1,
            ],
            [
                'name' => 'license',
                'value' => $request->license,
            ],
            [
                'name' => 'purchase_code',
                'value' => $request->purchase_code,
            ],
        ])->each(function ($col) {
            Setting::forceCreate([
                'name' => $col['name'],
                'value' => $col['value'],
            ]);
        });

        // Set up application
        $this->setup->seed_default_pages();
        $this->setup->seed_default_settings($request->license);
        $this->setup->seed_default_language();

        // Login account
        if (Auth::attempt($request->only(['email', 'password']))) {
            $request->session()->regenerate();

            return response('Registration was successful', 204);
        }

        return response('Something went wrong', 500);
    }

    /**
     * Get setup wizard status
     *
     * @return false | null
     */
    private function check_setup_status()
    {
        try {
            // Check database connections
            DB::getPdo();

            // Get setup_wizard status
            if (Schema::hasTable('settings') && get_setting('setup_wizard_success')) {
                abort(410, 'Gone');
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
