<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="description" content="{{ __('vuefilemanager.app_description') }}">

    <title>{{ config('vuefilemanager.app_name') }} | {{ __('vuefilemanager.app_description') }}</title>

    <link rel="icon" href="{{ asset('favicon.png') }}?v={{ get_version() }}">
    <link href="{{ asset('css/app.css') }}?v={{ get_version() }}" rel="stylesheet">

    {{-- Apple Mobile Web App--}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="apple-mobile-web-app-title" content="{{ config('vuefilemanager.app_name') }}">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ asset('assets/images/app-icon.png') }}">

    {{--Format Detection--}}
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
</head>
<body>

<div id="app"></div>

    <script>
        let config = {
            locale: '{{ \Illuminate\Support\Facades\App::getLocale() }}',
            app_name: '{{ config('vuefilemanager.app_name') }}',
            app_logo: '{{ asset(config('vuefilemanager.app_logo')) }}',
            api: '{{ url('/api') }}',
            hasAuthCookie: {{ Cookie::has('token') ? 1 : 0 }},
            userRegistration: {{ config('vuefilemanager.registration') ? 1 : 0 }},
            storageLimit: {{ config('vuefilemanager.limit_storage_by_capacity') ? 1 : 0 }},
        }
    </script>

    @if(env('APP_ENV') !== 'local')
        <script src="{{ asset('js/main.js') }}?v={{ get_version() }}"></script>
    @else
        <script src="{{ mix('js/main.js') }}"></script>
    @endif
</body>
</html>
