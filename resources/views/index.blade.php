<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="Manage your folders and files with Vue File Manager client powered by Laravel API endpoint.">

    <title>VueFileManager | Manage your folders and files with Vue File Manager client powered by Laravel API endpoint.</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
