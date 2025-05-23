<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('images/logo.webp') }}" type="image/webp">

    <link rel="preload" href="/fonts/webfonts/JetBrainsMono-Regular.woff2" as="font" type="font/woff2" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
    <body style="background: #181c1f; margin: 0; padding: 0;">
        <main>
            @yield('content')
        </main>
    </body>
</html>