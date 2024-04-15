<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Platform Login &mdash; ALPHA') }}</title>

        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset( 'assets/css/theme.css' ) }}">
        @vite(['resources/js/app.js'])
    </head>
    <body>
        <main id="auth" class="alpha">
            <header class="alpha__login">
                <a href="{{ url( '/' ) }}">{{ __( 'ALPHA' ) }}</a>
            </header>

            <article>
                @yield( 'content' )
            </article>
        </main>
    </body>
</html>
