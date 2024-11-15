<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Dashboard</title>

        <link rel="stylesheet" type="text/css" href="{{ asset( 'assets/css/theme.css' ) }}" media="all">
        <link rel="stylesheet" type="text/css" href="{{ asset( 'app/dist/assets/index.3538b5c3.css') }}" media="all">
        <script type="module" crossorigin src="{{ asset( 'app/dist/assets/index.85f5c769.js' ) }}"></script>
    </head>
    <body>
        @yield( 'content' )
    </body>
</html>
