<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="Manage your folders and files with Vue File Manager client powered by Laravel API endpoint.">

    <title>VueFileManager | Make your own Private Cloud with VueFileManager client powered by Laravel and Vue</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="apple-mobile-web-app-title" content="{{ config('vuefilemanager.app_name') }}">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ asset('assets/images/app-icon.png') }}">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
</head>
<body>
<div id="app"></div>

    <script>
        let config = {
            app_name: '{{ config('vuefilemanager.app_name') }}',
            app_logo: '{{ asset(config('vuefilemanager.app_logo')) }}',
            api: '{{ url('/api') }}',
            hasAuthCookie: {{ Cookie::has('token') ? 1 : 0 }},
            userRegistration: {{ config('vuefilemanager.registration') ? 1 : 0 }},
            storageLimit: {{ config('vuefilemanager.limit_storage_by_capacity') ? 1 : 0 }},
        }
    </script>

    @if(env('APP_ENV') !== 'local')
        <script src="{{ asset('js/main.js') }}"></script>
    @else
        <script src="{{ mix('js/main.js') }}"></script>
    @endif
</body>
</html>
