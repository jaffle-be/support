<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <title></title>
        <link rel="stylesheet" href="/assets/stylesheets/flat-ui/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" href="/assets/stylesheets/frontend/main.css"/>

    </head>
    <body>

    <div class="page-wrapper">

        @include('layouts.frontend.header')

        <div class="container">
            @yield('content')
        </div>

        @include('layouts.frontend.footer')

    </div>

        <script src="/assets/javascript/frontend/application.js"></script>

    </body>
</html>