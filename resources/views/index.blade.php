<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="description" content="{{ isset($settings->app_description) && $settings->app_description ? $settings->app_description : __('vuefilemanager.app_description') }}">

    <title>{{ isset($settings->app_title) && $settings->app_title ? $settings->app_title : 'VueFileManager' }} | {{ isset($settings->app_description) && $settings->app_description ? $settings->app_description : __('vuefilemanager.app_description') }}</title>

    {{--StyleSheet--}}
    <link href="{{ asset('css/app.css') }}?v={{ get_version() }}" rel="stylesheet">

    {{--OG items--}}
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ isset($settings->app_title) && $settings->app_title ? $settings->app_title : 'VueFileManager' }} | {{ isset($settings->app_description) && $settings->app_description ? $settings->app_description : __('vuefilemanager.app_description') }}">
    <meta property="og:description" content="{{ isset($settings->app_description) && $settings->app_description ? $settings->app_description : __('vuefilemanager.app_description') }}">
    <meta property="og:image" content="{{ asset('assets/images/vuefilemanager-og-image.jpg') }}">

    {{-- Apple Mobile Web App--}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="apple-mobile-web-app-title" content="{{ isset($settings->app_title) && $settings->app_title ? $settings->app_title : 'VueFileManager' }}">

    {{--Icons--}}
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ asset('assets/images/app-icon.png') }}">
    <link rel="icon" href="{{ isset($settings->app_favicon) && $settings->app_favicon ? $settings->app_favicon : asset('favicon.png') }}?v={{ get_version() }}">

    {{--Format Detection--}}
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
</head>
<body>
<div id="app"></div>

    <script>
        let config = {
            host: '{{ url('/') }}',
            api: '{{ url('/api') }}',

            locale: '{{ \Illuminate\Support\Facades\App::getLocale() }}',

            app_name: '{{ isset($settings->app_title) && $settings->app_title ? $settings->app_title : 'VueFileManager' }}',
            app_description: '{{ isset($settings->app_description) && $settings->app_description ? $settings->app_description : 'Your self-hosted storage cloud software powered by Laravel and Vue' }}',
            app_footer: '{!! isset($settings->footer_content) && $settings->footer_content ? $settings->footer_content : null !!}',

            app_logo: '{{ isset($settings->app_logo) && $settings->app_logo ? $settings->app_logo : null }}',
            app_logo_horizontal: '{{ isset($settings->app_logo_horizontal) && $settings->app_logo_horizontal ? $settings->app_logo_horizontal : null }}',

            app_payments_active: {{ isset($settings->payments_active) ? $settings->payments_active : 0 }},

            stripe_public_key: '{{ config('cashier.key') ? config('cashier.key') : null }}',

            userRegistration: {{ isset($settings->registration) ? $settings->registration : 1 }},
            storageLimit: {{ isset($settings->storage_limitation) ? $settings->storage_limitation : 1 }},
            storageDefaultSpace: {{ isset($settings->storage_default) ? $settings->storage_default : 5 }},
            storageDefaultSpaceFormatted: '{{ isset($settings->storage_default) ? format_gigabytes($settings->storage_default) : format_gigabytes(5) }}',
            mimetypesBlacklist: '{{ isset($settings->mimetypes_blacklist) ? $settings->mimetypes_blacklist: null}}',

            hasAuthCookie: {{ Cookie::has('token') ? 1 : 0 }},
            isSaaS: {{ isset($settings->license) && $settings->license === 'Extended' ? 1 : 0 }},
            isDemo: {{ env('APP_DEMO') ? 1 : 0 }},

            legal: {!! isset($legal) ? $legal : 'undefined' !!},

            installation: '{{ $installation ?? '' }}',
            latest_upgrade: '{{ isset($settings->latest_upgrade) && $settings->latest_upgrade ? $settings->latest_upgrade : null }}',

            chunkSize: {{ format_bytes(config('vuefilemanager.chunk_size')) }},
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
