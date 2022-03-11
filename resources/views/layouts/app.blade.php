<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Homas') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- <script src="https://blogy-new.herokuapp.com/js/app.js"></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito&family=Arimo&family=Barlow:wght@200;300;400&family=Berkshire+Swash&family=Karla:wght@200;300;400;600&family=Mulish:wght@300;400;600&family=Noto+Sans+TC:wght@100;400&family=Open+Sans:ital,wght@0,300;0,400;1,600&family=Oxygen:wght@300;400&family=PT+Sans&family=Titillium+Web:wght@200;300;400&display=swap" rel="stylesheet"> -->
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/shared/sc.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Cabin&family=IBM+Plex+Sans:wght@300&family=Montserrat:wght@300;500&family=Nunito:wght@300&family=Quicksand:wght@300;600&family=Source+Sans+Pro:wght@300;600&display=swap" rel="stylesheet">

    <!-- bootstrap cdn compiled and minified CSS-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- material design icon fonts -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- <script src="https://checkout.flutterwave.com/v3.js"></script> -->

    <!-- set a global variable here -->
    <script>
        window.Laravel = {!! json_encode([
            'app_name' => 'hozulinks.com',
        ]) !!};
    </script>

</head>
<body class="body_wrap">
    <div id="app">
        @yield('content')
    </div>
</body>
</html>
