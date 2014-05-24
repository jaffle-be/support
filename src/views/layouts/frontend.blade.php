<html>
    <head>
        <title></title>

        <link rel="stylesheet" href="/assets/stylesheets/frontend/main.css"/>

    </head>
    <body>

        @include('support::layouts.frontend.header')

        <div class="container">
            @yield('content')
        </div>

        @include('support::layouts.frontend.footer')

        <script src="/assets/javascript/frontend/application.js"></script>

    </body>
</html>