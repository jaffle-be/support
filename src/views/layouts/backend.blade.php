<html>
    <head>

        <meta charset="utf-8">

        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <title></title>

        <link rel="stylesheet" href="/assets/stylesheets/backend/main.css"/>

    </head>
    <body>

        @include('layouts.backend.header')

        <div class="container">
            @yield('content')
        </div>

        @include('layouts.backend.footer')

        <script src="/assets/javascript/backend/application.js"></script>

    </body>
</html>