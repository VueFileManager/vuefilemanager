<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="description" content="{{ $settings->app_description ?? __t('app_description') }}">

    <title>{{ $settings->app_title ?? 'VueFileManager' }} | {{ $settings->app_description ?? __t('app_description') }}</title>

    {{--StyleSheet--}}
    {{--<link href="{{ asset('css/app.css') }}?v={{ get_version() }}" rel="stylesheet">--}}
    <link href="{{ mix('css/app.css') }}?v={{ get_version() }}" rel="stylesheet">
    <link href="{{ asset('css/tailwind.css') }}?v={{ get_version() }}" rel="stylesheet">

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
<body>
<div id="app"></div>

    <script>
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

            stripe_public_key: '{{ config('cashier.key') ?? null }}',
            app_payments_active: {{ $settings->payments_active ?? 0 }},

			allowHomepage: {{ $settings->allow_homepage ?? 1 }},
            userRegistration: {{ $settings->registration ?? 1 }},
            userVerification: {{ $settings->user_verification ?? 0 }},
            storageLimit: {{ $settings->storage_limitation ?? 1 }},
            storageDefaultSpace: {{ $settings->storage_default ?? 5 }},
            storageDefaultSpaceFormatted: '{{ isset($settings->storage_default) ? format_gigabytes($settings->storage_default) : format_gigabytes(5) }}',
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
