@php

    try {
        // Bcmath Extension
        $storageDefaultSpaceFormatted = isset($settings->default_max_storage_amount)
            ? format_gigabytes($settings->default_max_storage_amount)
            : format_gigabytes(5);

        $uploadLimit = isset($settings->upload_limit)
            ? format_bytes($settings->upload_limit)
            : 'undefined';

        $chunkSize = isset($settings->chunk_size)
            ? format_bytes($settings->chunk_size)
            : format_bytes(64);

        $uploadLimitFormatted = isset($settings->upload_limit)
            ? format_megabytes($settings->upload_limit)
            : null;

    } catch (MissingExtensionException $exception) {
        $storageDefaultSpaceFormatted = '5GB';
        $uploadLimit = 'undefined';
        $uploadLimitFormatted = 5;
        $chunkSize = 64000000;
    }

    try {
        // User
        $isUser = auth()->check();
        $user = Auth::user();

        // Default user settings
        $defaultEmoji = $isUser ? $user->settings->emoji_type : 'twemoji';
        $defaultThemeMode = $isUser ? $user->settings->theme_mode : 'system';
    } catch (PDOException $e) {
        [$isUser, $isEmptyPlans, $isEmptyTransactions, $isEmptySubscriptions] = false;

        $defaultEmoji = 'twemoji';
        $defaultThemeMode = 'system';
    }
@endphp

<!DOCTYPE html>
<html class="{{ $installation === 'installation-needed' ? 'dark:bg-dark-background bg-light-background' : '' }}" style="min-height: 100%" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="theme-color" content="{{ $settings->app_color ?? '#00BC7E' }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
    <meta name="description" content="{{ $settings->app_description ?? __t('app_description') }}">

    <title>
        {{ $settings->app_title ?? 'VueFileManager' }} | {{ $settings->app_description ?? __t('app_description') }}
    </title>

    {{--StyleSheet--}}
    <link href="{{ mix('css/app.css') }}?v={{ get_version() }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/tailwind.css') }}?v={{ get_version() }}" rel="stylesheet" type="text/css">

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
<body style="min-height: 100%">

    <div id="app"></div>

    <script>
		let config = {
			host: '{{ url('/') }}',
			api: '{{ url('/api') }}',
			locale: '{{ app()->getLocale() }}',

			app_logo: '{{ $settings->app_logo ?? null }}',
			app_logo_dark: '{{ $settings->app_logo_dark ?? null }}',
			app_logo_horizontal: '{{ $settings->app_logo_horizontal ?? null }}',
			app_logo_horizontal_dark: '{{ $settings->app_logo_horizontal_dark ?? null }}',

            app_color: '{{ $settings->app_color ?? '#00BC7E' }}',
			app_footer: '{!! $settings->footer_content ?? null !!}',
			app_name: '{{ $settings->app_title ?? 'VueFileManager' }}',
			app_description: '{{ $settings->app_description ?? __t('app_description') }}',

			storageLimit: {{ $settings->storage_limitation ?? 1 }},
			storageDefaultSpace: {{ $settings->default_max_storage_amount ?? 5 }},
			storageDefaultSpaceFormatted: '{{ $storageDefaultSpaceFormatted }}',
			mimetypesBlacklist: '{{ isset($settings->mimetypes_blacklist) ? $settings->mimetypes_blacklist: null}}',
			uploadLimit: {{ $uploadLimit }},
			uploadLimitFormatted: '{{ $uploadLimitFormatted }}',
			chunkSize: {{ $chunkSize }},

			isAuthenticated: {{ $isUser ? 1 : 0 }},

			isDev: {{ is_dev() ? 1 : 0 }},
			isDemo: {{ config('vuefilemanager.is_demo') ? 1 : 0 }},

			installation: '{{ $installation ?? 'initial' }}',
			statusCheck: {!! json_encode($status_check) ?? 'undefined' !!},
			isSetupWizardDemo: {{ config('vuefilemanager.is_setup_wizard_demo') ? 1 : 0 }},
			isSetupWizardDebug: {{ config('vuefilemanager.is_setup_wizard_debug') ? 1 : 0 }},

			// Hidden set ups
			isAdminVueFileManagerBar: {{ config('vuefilemanager.is_admin_vuefilemanager_bar', 1) ? 1 : 0 }},

			// ReCaptcha
			recaptcha_client_id: '{{ config('services.recaptcha.client_id') }}',
			allowedRecaptcha: {{ $settings->allowed_recaptcha ?? 0 }},
			isRecaptchaConfigured: {{ config('services.recaptcha.client_id') ? 1 : 0 }},

            // User settings
            defaultEmoji: '{{ $defaultEmoji }}',
            defaultThemeMode: '{{ $defaultThemeMode }}',

            // App settings
			userRegistration: {{ $settings->registration ?? 1 }},
			userVerification: {{ $settings->user_verification ?? 0 }},
        }
    </script>

    @if(config('app.env') !== 'local')

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
