@php
    use VueFileManager\Subscription\Domain\Plans\Models\Plan;
    use VueFileManager\Subscription\Domain\Transactions\Models\Transaction;
    use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

    // Subscription
    $plan = Plan::where('status', 'active')->where('type', 'metered');

    $isEmptyPlans = Plan::count() === 0;
    $isEmptyTransactions = Transaction::count() === 0;
    $isEmptySubscriptions = Subscription::count() === 0;

    // User
    $isUser = auth()->check();
    $user = Auth::user();

    $defaultEmoji = $isUser ? $user->settings->emoji_type : 'twemoji';
    $defaultThemeMode = $isUser ? $user->settings->theme_mode : 'system';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="description" content="{{ $settings->app_description ?? __t('app_description') }}">

    <title>{{ $settings->app_title ?? 'VueFileManager' }} | {{ $settings->app_description ?? __t('app_description') }}</title>

    {{--StyleSheet--}}
    <link href="{{ mix('css/app.css') }}?v={{ get_version() }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/tailwind.css') }}?v={{ get_version() }}" rel="stylesheet" type="text/css">

    {{--OG items--}}
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $settings->app_title ?? 'VueFileManager' }} | {{ $settings->app_description ?? __t('app_description') }}">
    <meta property="og:description" content="{{ $settings->app_description ?? __t('app_description') }}">
    <meta property="og:image" content="{{ isset($settings->app_og_image) ? url($settings->app_og_image) : '' }}">

    {{-- Apple Mobile Web App--}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="apple-mobile-web-app-title" content="{{ $settings->app_title ?? 'VueFileManager' }}">

    {{--Icons--}}
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ isset($settings->app_touch_icon) ? url($settings->app_touch_icon) : '' }}">
    <link rel="icon" href="{{ isset($settings->app_favicon) ? url($settings->app_favicon) : asset('favicon.png') }}?v={{ get_version() }}">

    {{--Format Detection--}}
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">

    @include('vuefilemanager.others.color-template')
</head>
<body class="{{ is_dev() ? 'debug-screens' : '' }}">

    <div id="app"></div>

    <script>
        // todo: refactoring

        let config = {
            host: '{{ url('/') }}',
            api: '{{ url('/api') }}',
            locale: '{{ app()->getLocale() }}',

            app_color: '{{ $settings->app_color ?? '#00BC7E' }}',
            app_logo: '{{ $settings->app_logo ?? null }}',
            app_footer: '{!! $settings->footer_content ?? null !!}',
            app_name: '{{ $settings->app_title ?? 'VueFileManager' }}',
            app_logo_horizontal: '{{ $settings->app_logo_horizontal ?? null }}',
            app_description: '{{ $settings->app_description ?? __t('app_description') }}',

            allowHomepage: {{ $settings->allow_homepage ?? 1 }},
            userRegistration: {{ $settings->registration ?? 1 }},
            userVerification: {{ $settings->user_verification ?? 0 }},
            storageLimit: {{ $settings->storage_limitation ?? 1 }},
            storageDefaultSpace: {{ $settings->default_max_storage_amount ?? 5 }},
            storageDefaultSpaceFormatted: '{{ isset($settings->default_max_storage_amount) ? format_gigabytes($settings->default_max_storage_amount) : format_gigabytes(5) }}',
            mimetypesBlacklist: '{{ isset($settings->mimetypes_blacklist) ? $settings->mimetypes_blacklist: null}}',
            uploadLimit: {{ isset($settings->upload_limit) ? format_bytes($settings->upload_limit) : 'undefined' }},
            uploadLimitFormatted: '{{ isset($settings->upload_limit) ? format_megabytes($settings->upload_limit) : null }}',
            chunkSize: {{ format_bytes(config('vuefilemanager.chunk_size')) }},

            isAuthenticated: {{ auth()->check() ? 1 : 0 }},
            isSaaS: {{ $settings && $settings->license === 'Extended' ? 1 : 0 }},

            isDev: {{ is_dev() ? 1 : 0 }},
            isDemo: {{ config('vuefilemanager.is_demo') ? 1 : 0 }},

            legal: {!! $legal ?? 'undefined' !!},

            installation: '{{ $installation ?? 'initial' }}',
            statusCheck: {!! json_encode($status_check) ?? 'undefined' !!},

            // States
			isEmptyPlans: {{ $isEmptyPlans ? 1 : 0 }},
			isEmptyTransactions: {{ $isEmptyTransactions ? 1 : 0 }},
			isEmptySubscriptions: {{ $isEmptySubscriptions ? 1 : 0 }},

            // Hidden set ups
            isAdminVueFileManagerBar: {{ env('IS_ADMIN_VUEFILEMANAGER_BAR', 1) ? 1 : 0 }},

            // Metered
			allowed_registration_bonus: {{ $settings->allowed_registration_bonus ?? 0 }},
			registration_bonus_amount: {{ $settings->registration_bonus_amount ?? 0 }},
			isCreatedMeteredPlan: {{ $plan->exists() ? 1 : 0 }},
			meteredPlanId: '{{ $plan->exists() ? $plan->first()->id : null }}',

            // Payments
            allowed_payments: {{ $settings->allowed_payments ?? 0 }},
			subscriptionType: '{{ $settings->subscription_type ?? 'none' }}',

            // PayPal
            isPayPal: {{ $settings->allowed_paypal ?? 0 }},
            isPayPalLive: {{ env('PAYPAL_IS_LIVE') ? 1 : 0 }},
            paypal_client_id: '{{ env('PAYPAL_CLIENT_ID') }}',
			paypal_payment_description: '{{ $settings->paypal_payment_description ?? '' }}',

            // Paystack
            isPaystack: {{ $settings->allowed_paystack ?? 0 }},
            paystack_public_key: '{{ env('PAYSTACK_PUBLIC_KEY') }}',
			paystack_payment_description: '{{ $settings->paystack_payment_description ?? '' }}',

            // Stripe
            isStripe: {{ $settings->allowed_stripe ?? 0 }},
            stripe_public_key: '{{ env('STRIPE_PUBLIC_KEY') }}',
			stripe_payment_description: '{{ $settings->stripe_payment_description ?? '' }}',

            // ReCaptcha
            recaptcha_client_id: '{{ env('RECAPTCHA_CLIENT_ID') }}',
            allowedRecaptcha: {{ $settings->allowed_recaptcha ?? 0 }},
            isRecaptchaConfigured: {{ env('RECAPTCHA_CLIENT_ID') ? 1 : 0 }},

            // Social logins
            allowedFacebookLogin: {{ $settings->allowed_facebook_login ?? 0 }},
            isFacebookLoginConfigured: {{ env('FACEBOOK_CLIENT_ID') ? 1 : 0 }},

			allowedGoogleLogin: {{ $settings->allowed_google_login ?? 0 }},
            isGoogleLoginConfigured: {{ env('GOOGLE_CLIENT_ID') ? 1 : 0 }},

			allowedGithubLogin: {{ $settings->allowed_github_login ?? 0 }},
			isGithubLoginConfigured: {{ env('GITHUB_CLIENT_ID') ? 1 : 0 }},

            // User settings
            defaultEmoji: '{{ $defaultEmoji }}',
            defaultThemeMode: '{{ $defaultThemeMode }}',
        }
    </script>

    @if(env('APP_ENV') !== 'local')

        {{--Application production script--}}
        <script src="{{ asset('js/main.js') }}?v={{ get_version() }}"></script>

        {{--Global site tag (gtag.js) - Google Analytics--}}
        @if(isset($settings->google_analytics) && $settings->google_analytics)
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings->google_analytics }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '{{ $settings->google_analytics }}');
            </script>
        @endif
    @else
        {{--Application development script--}}
        <script src="{{ mix('js/main.js') }}"></script>
    @endif
</body>
</html>
