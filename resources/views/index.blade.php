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
            hasAuthCookie: {{ Cookie::has('token') ? 1 : 0 }},
            api: '{{ url('/api') }}',
        }
    </script>

    <script src="{{ mix('js/main.js') }}"></script>
</body>
</html>
