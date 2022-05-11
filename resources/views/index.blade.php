<!DOCTYPE html>
<html class="{{ $config->installation === 'installation-needed' ? 'dark:bg-dark-background bg-light-background' : '' }}" style="min-height: 100%" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="theme-color" content="{{ $config->app_color ?? '#00BC7E' }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
    <meta name="description" content="{{ $config->app_description ?? __t('app_description') }}">

    <title>
        {{ $config->app_title ?? 'VueFileManager' }} | {{ $config->app_description ?? __t('app_description') }}
    </title>

    {{--StyleSheet--}}
    <link href="{{ mix('css/app.css') }}?v={{ get_version() }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/tailwind.css') }}?v={{ get_version() }}" rel="stylesheet" type="text/css">

    {{--OG items--}}
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $config->app_title ?? 'VueFileManager' }} | {{ $config->app_description ?? __t('app_description') }}">
    <meta property="og:description" content="{{ $config->app_description ?? __t('app_description') }}">
    <meta property="og:image" content="{{ isset($config->app_og_image) ? url($config->app_og_image) : '' }}">

    {{-- Apple Mobile Web App--}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="apple-mobile-web-app-title" content="{{ $config->app_title ?? 'VueFileManager' }}">

    {{--Icons--}}
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ isset($config->app_touch_icon) ? url($config->app_touch_icon) : '' }}">
    <link rel="icon" href="{{ isset($config->app_favicon) ? url($config->app_favicon) : asset('favicon.png') }}?v={{ get_version() }}">

    {{--Format Detection--}}
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">

    @include('vuefilemanager.others.color-template')
</head>
<body style="min-height: 100%">

    <div id="app"></div>

    <script>
		let config = {
			host: '{{ $config->host }}',
			api: '{{ $config->api }}',
			locale: '{{ $config->locale }}',

            broadcasting: '{{ $config->broadcasting }}',
            broadcastingKey: '{{ $config->broadcastingKey }}',
            broadcastingHost: '{{ $config->broadcastingHost }}',
            broadcastingPort: '{{ $config->broadcastingPort }}',
            broadcastingCluster: '{{ $config->broadcastingCluster }}',

			app_logo: '{{ $config->app_logo }}',
			app_logo_dark: '{{ $config->app_logo_dark }}',
			app_logo_horizontal: '{{ $config->app_logo_horizontal }}',
			app_logo_horizontal_dark: '{{ $config->app_logo_horizontal_dark }}',

            app_color: '{{ $config->app_color }}',
			app_footer: '{!! $config->app_footer !!}',
			app_name: '{{ $config->app_name }}',
			app_description: '{{ $config->app_description }}',

			allowHomepage: {{ $config->allowHomepage }},
			storageLimit: {{ $config->storageLimit }},
			teamsDefaultMembers: {{ $config->teamsDefaultMembers }},
			storageDefaultSpace: {{ $config->storageDefaultSpace }},
			storageDefaultSpaceFormatted: '{{ $config->storageDefaultSpaceFormatted }}',
			mimetypesBlacklist: '{{ $config->mimetypesBlacklist }}',
			uploadLimit: {{ $config->uploadLimit }},
			uploadLimitFormatted: '{{ $config->uploadLimitFormatted }}',
			chunkSize: {{ $config->chunkSize }},

			isAuthenticated: {{ $config->isAuthenticated }},
			isSaaS: {{ $config->isSaaS }},

			isDev: {{ $config->isDev }},
			isDemo: {{ $config->isDemo }},

			legal: {!! $config->legal !!},

			installation: '{{ $config->installation }}',
			statusCheck: {!! $config->statusCheck !!},
			isSetupWizardDemo: {{ $config->isSetupWizardDemo }},
			isSetupWizardDebug: {{ $config->isSetupWizardDebug }},

			// States
			isEmptyPlans: {{ $config->isEmptyPlans }},
			isEmptyTransactions: {{ $config->isEmptyTransactions }},
			isEmptySubscriptions: {{ $config->isEmptySubscriptions }},

			// Hidden set ups
			isAdminVueFileManagerBar: {{ $config->isAdminVueFileManagerBar }},

			// Metered
			allowed_registration_bonus: {{ $config->allowed_registration_bonus }},
			registration_bonus_amount: {{ $config->registration_bonus_amount }},
			isCreatedMeteredPlan: {{ $config->isCreatedMeteredPlan }},
			meteredPlanId: '{{ $config->meteredPlanId }}',

			// Payments
			allowed_payments: {{ $config->allowed_payments }},
			subscriptionType: '{{ $config->subscriptionType }}',

			// PayPal
			isPayPal: {{ $config->isPayPal }},
			isPayPalLive: {{ $config->isPayPalLive }},
			paypal_client_id: '{{ $config->paypal_client_id }}',
			paypal_payment_description: '{{ $config->paypal_payment_description }}',

			// Paystack
			isPaystack: {{ $config->isPaystack }},
			paystack_public_key: '{{ $config->paystack_public_key }}',
			paystack_payment_description: '{{ $config->paystack_payment_description }}',

			// Stripe
			isStripe: {{ $config->isStripe }},
			stripe_public_key: '{{ $config->stripe_public_key }}',
			stripe_payment_description: '{{ $config->stripe_payment_description }}',

			// ReCaptcha
			recaptcha_client_id: '{{ $config->recaptcha_client_id }}',
			allowedRecaptcha: {{ $config->allowedRecaptcha }},
			isRecaptchaConfigured: {{ $config->isRecaptchaConfigured }},

			// Social logins
			allowedFacebookLogin: {{ $config->allowedFacebookLogin }},
			isFacebookLoginConfigured: {{ $config->isFacebookLoginConfigured }},

			allowedGoogleLogin: {{ $config->allowedGoogleLogin }},
			isGoogleLoginConfigured: {{ $config->isGoogleLoginConfigured }},

			allowedGithubLogin: {{ $config->allowedGithubLogin }},
			isGithubLoginConfigured: {{ $config->isGithubLoginConfigured }},

            // Adsense
            allowedAdsense: {{ $config->allowedAdsense }},
            adsenseClientId: '{{ $config->adsenseClientId }}',
			adsenseBanner01: `{!! $config->adsenseBanner01 !!}`,
			adsenseBanner02: `{!! $config->adsenseBanner02 !!}`,
			adsenseBanner03: `{!! $config->adsenseBanner03 !!}`,

            // User settings
            defaultEmoji: '{{ $config->defaultEmoji }}',
            defaultThemeMode: '{{ $config->defaultThemeMode }}',

            // App settings
			userRegistration: {{ $config->userRegistration }},
			userVerification: {{ $config->userVerification }},
        }
    </script>

    @if(config('app.env') !== 'local')

        {{--Application production script--}}
        <script src="{{ asset('js/main.js') }}?v={{ get_version() }}"></script>

        {{--Global site tag (gtag.js) - Google Analytics--}}
        @if(isset($config->google_analytics) && $config->google_analytics)
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $config->google_analytics }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '{{ $config->google_analytics }}');
            </script>
        @endif
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
