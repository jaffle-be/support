<html>
    <head>
        <title></title>

        <link rel="stylesheet" href="/assets/stylesheets/backend/main.css"/>

    </head>
    <body>

        @include('support::layouts.backend.header')

        <div class="container">
            @yield('content')
        </div>

        @include('support::layouts.backend.footer')

        <script src="/assets/javascript/backend/application.js"></script>

    </body>
</html>