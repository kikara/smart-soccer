<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/fav.svg" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Футбол</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/js/main.js'])
    @yield('head')
</head>
<body>
@yield('content')
</body>
</html>
