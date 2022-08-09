<!DOCTYPE html>
<html class="{{ $config->app->installation === 'installation-needed' ? 'dark:bg-dark-background bg-light-background' : '' }}" style="min-height: 100%" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="theme-color" content="{{ $config->theme->color ?? '#00BC7E' }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
    <meta name="description" content="{{ $config->app->description ?? __t('app_description') }}">

    <title>
        {{ $config->app->name ?? 'VueFileManager' }} | {{ $config->app->description ?? __t('app_description') }}
    </title>

    {{--StyleSheet--}}
    <link href="{{ mix('css/app.css') }}?v={{ get_version() }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/tailwind.css') }}?v={{ get_version() }}" rel="stylesheet" type="text/css">

    {{--OG items--}}
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $config->app->name ?? 'VueFileManager' }} | {{ $config->app->description ?? __t('app_description') }}">
    <meta property="og:description" content="{{ $config->app->description ?? __t('app_description') }}">
    <meta property="og:image" content="{{ isset($config->logos->og_image) ? url($config->logos->og_image) : '' }}">

    {{-- Apple Mobile Web App--}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="apple-mobile-web-app-title" content="{{ $config->app->name ?? 'VueFileManager' }}">

    {{--Icons--}}
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ isset($config->logos->touch_icon) ? url($config->logos->touch_icon) : '' }}">
    <link rel="icon" href="{{ isset($config->logos->favicon) ? url($config->logos->favicon) : asset('favicon.png') }}?v={{ get_version() }}">

    {{--Format Detection--}}
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">

    @include('vuefilemanager.others.color-template')
</head>
<body style="min-height: 100%">

    <div id="app"></div>

    <script>
		let config = {
			host: '{{ $config->app->host }}',
			api: '{{ $config->app->api }}',
			locale: '{{ $config->app->locale }}',

            broadcasting: '{{ $config->broadcasting->driver }}',
            broadcastingKey: '{{ $config->broadcasting->key }}',
            broadcastingHost: '{{ $config->broadcasting->host }}',
            broadcastingPort: '{{ $config->broadcasting->port }}',
            broadcastingCluster: '{{ $config->broadcasting->cluster }}',

			app_logo: '{{ $config->logos->main }}',
			app_logo_dark: '{{ $config->logos->main_dark }}',
			app_logo_horizontal: '{{ $config->logos->horizontal }}',
			app_logo_horizontal_dark: '{{ $config->logos->horizontal_dark }}',

            app_color: '{{ $config->theme->color }}',
			app_footer: '{!! $config->app->footer !!}',
			app_name: '{{ $config->app->name }}',
			app_description: '{{ $config->app->description }}',

			allowHomepage: {{ $config->allowHomepage }},
			storageLimit: {{ $config->storage->limit }},
			teamsDefaultMembers: {{ $config->teamsDefaultMembers }},
			storageDefaultSpace: {{ $config->storage->defaultSpace }},
			storageDefaultSpaceFormatted: '{{ $config->storage->defaultSpaceFormatted }}',
			mimetypesBlacklist: '{{ $config->upload->disabledMimetypes }}',
			uploadLimit: {{ $config->upload->limit }},
			uploadLimitFormatted: '{{ $config->upload->limitFormatted }}',
			chunkSize: {{ $config->upload->chunkSize }},

			isAuthenticated: {{ $config->app->isAuthenticated }},

			isDev: {{ $config->app->isDev }},
			isDemo: {{ $config->app->isDemo }},

			legal: {!! $config->legal !!},

			installation: '{{ $config->app->installation }}',
			statusCheck: {!! $config->debug->statusCheck !!},
			isSetupWizardDemo: {{ $config->debug->isSetupWizardDemo }},
			isSetupWizardDebug: {{ $config->debug->isSetupWizardDebug }},
			isPrefilledUsers: {{ $config->debug->isPrefilledUsers }},

			// States
			isEmptyPlans: {{ $config->payments->isEmptyPlans }},
			isEmptyTransactions: {{ $config->payments->isEmptyTransactions }},
			isEmptySubscriptions: {{ $config->payments->isEmptySubscriptions }},

			// Hidden set ups
			isAdminVueFileManagerBar: {{ $config->debug->isAdminVueFileManagerBar }},

			// Metered
			allowed_registration_bonus: {{ $config->billing_metered->allowed_registration_bonus }},
			registration_bonus_amount: {{ $config->billing_metered->registration_bonus_amount }},
			isCreatedMeteredPlan: {{ $config->billing_metered->isCreatedMeteredPlan }},
			meteredPlanId: '{{ $config->billing_metered->meteredPlanId }}',

			// Payments
			allowed_payments: {{ $config->payments->allowed }},
			subscriptionType: '{{ $config->payments->type }}',

			// PayPal
			isPayPal: {{ $config->gateways->paypal->allowed }},
			isPayPalLive: {{ $config->gateways->paypal->live }},
			paypal_client_id: '{{ $config->gateways->paypal->client_id }}',
			paypal_payment_description: '{{ $config->gateways->paypal->payment_description }}',

			// Paystack
			isPaystack: {{ $config->gateways->paystack->allowed }},
			paystack_public_key: '{{ $config->gateways->paystack->public_key }}',
			paystack_payment_description: '{{ $config->gateways->paystack->payment_description }}',

			// Stripe
			isStripe: {{ $config->gateways->stripe->allowed }},
			stripe_public_key: '{{ $config->gateways->stripe->public_key }}',
			stripe_payment_description: '{{ $config->gateways->stripe->payment_description }}',

			// ReCaptcha
			recaptcha_client_id: '{{ $config->recaptcha->client_id }}',
			allowedRecaptcha: {{ $config->recaptcha->allowed }},
			isRecaptchaConfigured: {{ $config->recaptcha->is_configured }},

			// Social logins
			allowedFacebookLogin: {{ $config->social_logins->is_facebook_allowed }},
			isFacebookLoginConfigured: {{ $config->social_logins->is_facebook_configured }},

			allowedGoogleLogin: {{ $config->social_logins->is_google_allowed }},
			isGoogleLoginConfigured: {{ $config->social_logins->is_google_configured }},

			allowedGithubLogin: {{ $config->social_logins->is_github_allowed }},
			isGithubLoginConfigured: {{ $config->social_logins->is_github_configured }},

            // Adsense
            allowedAdsense: {{ $config->adsense->allowed }},
            adsenseClientId: '{{ $config->adsense->clientId }}',
			adsenseBanner01: `{!! $config->adsense->banner01 !!}`,
			adsenseBanner02: `{!! $config->adsense->banner02 !!}`,
			adsenseBanner03: `{!! $config->adsense->banner03 !!}`,

            // User settings
            defaultEmoji: '{{ $config->theme->emoji }}',
            defaultThemeMode: '{{ $config->theme->mode }}',

            // App settings
			userRegistration: {{ $config->registration->allowed }},
			userVerification: {{ $config->registration->verification }},

            // Google Analytics
            googleAnalytics: '{{ $config->google_analytics }}',
        }
    </script>

    @if(config('app.env') !== 'local')

        {{--Application production script--}}
        <script src="{{ asset('js/main.js') }}?v={{ get_version() }}"></script>
    @else
        {{--Application development script--}}
        <script src="{{ mix('js/main.js') }}"></script>
    @endif

    {{--Adsense code--}}
    @if(optional($config)->allowed_adsense)
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $config->adsense_client_id ?? '' }}" crossorigin="anonymous"></script>

        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    @endif
</body>
</html>
