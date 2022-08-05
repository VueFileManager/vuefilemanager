<?php

namespace Domain\Settings\Actions;

use DB;
use PDOException;
use Domain\Pages\Models\Page;
use Monolog\Handler\MissingExtensionException;
use Support\Status\Actions\GetServerSetupStatusAction;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Transactions\Models\Transaction;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class GetConfigAction
{
    public function __construct(
        public GetServerSetupStatusAction $getServerSetupStatus,
    ) {}

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
            'app'                 => [
                'host'            => url('/'),
                'api'             => url('/api'),
                'locale'          => app()->getLocale(),
                'isDev'           => is_dev() ? 1 : 0,
                'isDemo'          => config('vuefilemanager.is_demo') ? 1 : 0,
                'isAuthenticated' => $isUser ? 1 : 0,
                'installation'    => $setupStatus ?? 'initial',
                'name'            => $settings->app_title ?? 'VueFileManager',
                'description'     => $settings->app_description ?? __t('app_description'),
                'footer'          => $settings->footer_content ?? null,
            ],
            'debug'               => [
                'isSetupWizardDemo'        => config('vuefilemanager.is_setup_wizard_demo') ? 1 : 0,
                'isSetupWizardDebug'       => config('vuefilemanager.is_setup_wizard_debug') ? 1 : 0,
                'isAdminVueFileManagerBar' => config('vuefilemanager.is_admin_vuefilemanager_bar', 1) ? 1 : 0,
                'isPrefilledUsers'         => config('vuefilemanager.is_prefilled_users') ? 1 : 0,
                'statusCheck'              => json_encode($serverInfo) ?? 'undefined',
            ],
            'broadcasting'        => [
                'driver'  => config('broadcasting.default'),
                'key'     => config('broadcasting.connections.pusher.key'),
                'host'    => config('broadcasting.connections.pusher.options.host'),
                'port'    => config('broadcasting.connections.pusher.options.port'),
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            ],
            'logos'               => [
                'main'            => $settings->app_logo ?? null,
                'og_image'        => $settings->app_og_image ?? null,
                'main_dark'       => $settings->app_logo_dark ?? null,
                'horizontal'      => $settings->app_logo_horizontal ?? null,
                'horizontal_dark' => $settings->app_logo_horizontal_dark ?? null,
                'touch_icon'      => $settings->app_touch_icon ?? null,
                'favicon'         => $settings->app_favicon ?? null,
            ],
            'theme'               => [
                'color' => $settings->app_color ?? '#00BC7E',
                'emoji' => $defaultEmoji,
                'mode'  => $defaultThemeMode,
            ],
            'upload'              => [
                'disabledMimetypes' => isset($settings->mimetypes_blacklist) ? $settings->mimetypes_blacklist : null,
                'limit'             => $uploadLimit,
                'chunkSize'         => $chunkSize,
                'limitFormatted'    => $uploadLimitFormatted,
            ],
            'storage'             => [
                'defaultSpace'          => intval($settings->default_max_storage_amount ?? 5),
                'limit'                 => intval($settings->storage_limitation ?? 1),
                'defaultSpaceFormatted' => $storageDefaultSpaceFormatted,
            ],
            'billing_metered'     => [
                'allowed_registration_bonus' => intval($settings->allowed_registration_bonus ?? 0),
                'registration_bonus_amount'  => intval($settings->registration_bonus_amount ?? 0),
                'isCreatedMeteredPlan'       => $plan && $plan->exists() ? 1 : 0,
                'meteredPlanId'              => $plan && $plan->exists() ? $plan->first()->id : null,
            ],
            'payments'            => [
                'allowed'              => intval($settings->allowed_payments ?? 0),
                'type'                 => $settings->subscription_type ?? 'none',
                'isEmptyPlans'         => $isEmptyPlans ? 1 : 0,
                'isEmptyTransactions'  => $isEmptyTransactions ? 1 : 0,
                'isEmptySubscriptions' => $isEmptySubscriptions ? 1 : 0,
            ],
            'gateways'            => [
                'paypal'   => [
                    'allowed'             => intval($settings->allowed_paypal ?? 0),
                    'live'                => config('subscription.credentials.paypal.is_live') ? 1 : 0,
                    'client_id'           => config('subscription.credentials.paypal.id'),
                    'payment_description' => $settings->paypal_payment_description ?? null,
                ],
                'stripe'   => [
                    'allowed'             => intval($settings->allowed_stripe ?? 0),
                    'public_key'          => config('subscription.credentials.stripe.public_key'),
                    'payment_description' => $settings->stripe_payment_description ?? null,
                ],
                'paystack' => [
                    'allowed'             => intval($settings->allowed_paystack ?? 0),
                    'public_key'          => config('subscription.credentials.paystack.public_key'),
                    'payment_description' => $settings->paystack_payment_description ?? null,
                ],
            ],
            'recaptcha'           => [
                'allowed'       => intval($settings->allowed_recaptcha ?? 0),
                'is_configured' => config('services.recaptcha.client_id') ? 1 : 0,
                'client_id'     => config('services.recaptcha.client_id'),
            ],
            'social_logins'       => [
                'is_facebook_allowed'    => $settings->allowed_facebook_login ?? 0,
                'is_google_allowed'      => $settings->allowed_google_login ?? 0,
                'is_github_allowed'      => $settings->allowed_github_login ?? 0,
                'is_facebook_configured' => config('services.facebook.client_id') ? 1 : 0,
                'is_google_configured'   => config('services.google.client_id') ? 1 : 0,
                'is_github_configured'   => config('services.github.client_id') ? 1 : 0,
            ],
            'adsense'             => [
                'allowed'  => intval($settings?->allowed_adsense ?? 0),
                'clientId' => $settings->adsense_client_id ?? null,
                'banner01' => $settings->adsense_banner_01 ?? null,
                'banner02' => $settings->adsense_banner_02 ?? null,
                'banner03' => $settings->adsense_banner_03 ?? null,
            ],
            'registration'        => [
                'allowed'      => $settings->registration ?? 1,
                'verification' => $settings->user_verification ?? 0,
            ],

            // Public Pages
            'allowHomepage'       => intval($settings->allow_homepage ?? 1),
            'teamsDefaultMembers' => intval($settings->default_max_team_member ?? 10),
            'legal'               => $pages ? json_encode($pages) : 'undefined',
            'google_analytics'    => optional($settings)->google_analytics ?? null,
        ];
    }
}
