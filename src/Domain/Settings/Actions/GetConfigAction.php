<?php
namespace Domain\Settings\Actions;

use DB;
use PDOException;
use Domain\Pages\Models\Page;
use Monolog\Handler\MissingExtensionException;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use Domain\Settings\Controllers\GetServerStatusController;
use VueFileManager\Subscription\Domain\Transactions\Models\Transaction;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class GetConfigAction
{
    public function __construct(
        public GetServerStatusController $getServerSetupStatus,
    ) {
    }

    public function __invoke(): array
    {
        $setupStatus = 'installation-needed';
        $serverInfo = [];
        $settings = null;

        try {
            // Try to connect to database
            DB::getPdo();

            // Get setup status
            $setupStatus = getInstallationStatus();

            // Get all settings
            $settings = getAllSettings();

            // Get app pages
            $pages = Page::all();

            // Subscription
            $isEmptySubscriptions = Subscription::count() === 0;
            $isEmptyTransactions = Transaction::count() === 0;
            $isEmptyPlans = Plan::count() === 0;
            $plan = Plan::where('status', 'active')
                ->where('type', 'metered');

            // User
            $isUser = auth()->check();
            $user = auth()->user();

            // Default user settings
            $defaultEmoji = $isUser ? $user->settings->emoji_type : 'twemoji';
            $defaultThemeMode = $isUser ? $user->settings->theme_mode : 'system';
        } catch (PDOException $e) {
            [$isUser, $isEmptyPlans, $isEmptyTransactions, $isEmptySubscriptions] = false;

            $pages = null;
            $plan = null;
            $defaultEmoji = 'twemoji';
            $defaultThemeMode = 'system';
        }

        if ($setupStatus === 'installation-needed') {
            $serverInfo = ($this->getServerSetupStatus)();
        }

        // Bcmath Extension
        try {
            $storageDefaultSpaceFormatted = isset($settings->default_max_storage_amount)
                ? toGigabytes($settings->default_max_storage_amount)
                : toGigabytes(5);

            $uploadLimit = isset($settings->upload_limit)
                ? toBytes($settings->upload_limit)
                : 'undefined';

            $chunkSize = isset($settings->chunk_size)
                ? toBytes($settings->chunk_size)
                : toBytes(64);

            $uploadLimitFormatted = isset($settings->upload_limit)
                ? toMegabytes($settings->upload_limit)
                : null;
        } catch (MissingExtensionException $e) {
            $storageDefaultSpaceFormatted = '5GB';
            $uploadLimit = 'undefined';
            $uploadLimitFormatted = 5;
            $chunkSize = 64000000;
        }

        return [
            // App
            'host'                         => url('/'),
            'api'                          => url('/api'),
            'locale'                       => app()->getLocale(),
            'isDev'                        => is_dev() ? 1 : 0,
            'isDemo'                       => config('vuefilemanager.is_demo') ? 1 : 0,
            'isSaaS'                       => $settings && optional($settings)->license === 'extended' ? 1 : 0,
            'isAuthenticated'              => $isUser ? 1 : 0,
            'isSetupWizardDemo'            => config('vuefilemanager.is_setup_wizard_demo') ? 1 : 0,
            'isSetupWizardDebug'           => config('vuefilemanager.is_setup_wizard_debug') ? 1 : 0,
            'isAdminVueFileManagerBar'     => config('vuefilemanager.is_admin_vuefilemanager_bar', 1) ? 1 : 0,
            'installation'                 => $setupStatus ?? 'initial',
            'statusCheck'                  => json_encode($serverInfo) ?? 'undefined',

            // Broadcasting
            'broadcasting'                 => config('broadcasting.default'),
            'broadcastingKey'              => config('broadcasting.connections.pusher.key'),
            'broadcastingHost'             => config('broadcasting.connections.pusher.options.host'),
            'broadcastingPort'             => config('broadcasting.connections.pusher.options.port'),
            'broadcastingCluster'          => config('broadcasting.connections.pusher.options.cluster'),

            // App Logos
            'app_logo'                     => $settings->app_logo ?? null,
            'app_logo_dark'                => $settings->app_logo_dark ?? null,
            'app_logo_horizontal'          => $settings->app_logo_horizontal ?? null,
            'app_logo_horizontal_dark'     => $settings->app_logo_horizontal_dark ?? null,

            // App theme
            'app_color'                    => $settings->app_color ?? '#00BC7E',
            'app_footer'                   => $settings->footer_content ?? null,

            // App title & name
            'app_name'                     => $settings->app_title ?? 'VueFileManager',
            'app_description'              => $settings->app_description ?? __t('app_description'),
            'defaultEmoji'                 => $defaultEmoji,
            'defaultThemeMode'             => $defaultThemeMode,

            // Upload settings
            'mimetypesBlacklist'           => isset($settings->mimetypes_blacklist) ? $settings->mimetypes_blacklist : null,
            'storageDefaultSpace'          => $settings->default_max_storage_amount ?? 5,
            'storageLimit'                 => $settings->storage_limitation ?? 1,
            'storageDefaultSpaceFormatted' => $storageDefaultSpaceFormatted,
            'uploadLimitFormatted'         => $uploadLimitFormatted,
            'uploadLimit'                  => $uploadLimit,
            'chunkSize'                    => $chunkSize,

            // Metered billings
            'allowed_registration_bonus'   => $settings->allowed_registration_bonus ?? 0,
            'registration_bonus_amount'    => $settings->registration_bonus_amount ?? 0,
            'isCreatedMeteredPlan'         => $plan && $plan->exists() ? 1 : 0,
            'meteredPlanId'                => $plan && $plan->exists() ? $plan->first()->id : null,

            // Payments setup
            'allowed_payments'             => $settings->allowed_payments ?? 0,
            'subscriptionType'             => $settings->subscription_type ?? 'none',
            'isEmptyPlans'                 => $isEmptyPlans ? 1 : 0,
            'isEmptyTransactions'          => $isEmptyTransactions ? 1 : 0,
            'isEmptySubscriptions'         => $isEmptySubscriptions ? 1 : 0,

            // Payment gateways
            'isPayPal'                     => $settings->allowed_paypal ?? 0,
            'isPaystack'                   => $settings->allowed_paystack ?? 0,
            'isStripe'                     => $settings->allowed_stripe ?? 0,
            'isPayPalLive'                 => config('subscription.credentials.paypal.is_live') ? 1 : 0,
            'paypal_client_id'             => config('subscription.credentials.paypal.id'),
            'paystack_public_key'          => config('subscription.credentials.paystack.public_key'),
            'stripe_public_key'            => config('subscription.credentials.stripe.public_key'),
            'paypal_payment_description'   => $settings->paypal_payment_description ?? null,
            'paystack_payment_description' => $settings->paystack_payment_description ?? null,
            'stripe_payment_description'   => $settings->stripe_payment_description ?? null,

            // Google reCaptcha
            'allowedRecaptcha'             => $settings->allowed_recaptcha ?? 0,
            'recaptcha_client_id'          => config('services.recaptcha.client_id'),
            'isRecaptchaConfigured'        => config('services.recaptcha.client_id') ? 1 : 0,

            // Social Authentication
            'allowedFacebookLogin'         => $settings->allowed_facebook_login ?? 0,
            'allowedGoogleLogin'           => $settings->allowed_google_login ?? 0,
            'allowedGithubLogin'           => $settings->allowed_github_login ?? 0,
            'isFacebookLoginConfigured'    => config('services.facebook.client_id') ? 1 : 0,
            'isGoogleLoginConfigured'      => config('services.google.client_id') ? 1 : 0,
            'isGithubLoginConfigured'      => config('services.github.client_id') ? 1 : 0,

            // Google Adsense
            'allowedAdsense'               => $settings?->allowed_adsense ?? 0,
            'adsenseClientId'              => $settings->adsense_client_id ?? null,
            'adsenseBanner01'              => $settings->adsense_banner_01 ?? null,
            'adsenseBanner02'              => $settings->adsense_banner_02 ?? null,
            'adsenseBanner03'              => $settings->adsense_banner_03 ?? null,

            // Registration
            'userRegistration'             => $settings->registration ?? 1,
            'userVerification'             => $settings->user_verification ?? 0,

            // Public Pages
            'allowHomepage'                => $settings->allow_homepage ?? 1,
            'teamsDefaultMembers'          => $settings->default_max_team_member ?? 10,
            'legal'                        => $pages ? json_encode($pages) : 'undefined',
        ];
    }
}
