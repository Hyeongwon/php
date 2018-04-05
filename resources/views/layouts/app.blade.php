<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name', 'Laravel')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body id = "app-layout">
    @include('layouts.partials.navigation')

    <div class ="container">
        @include('flash::message')
        @yield('content')
    </div>

    @include('layouts.partials.footer')

    <script src="{{asset('js/app.js')}}"></script>
    @yield('script')
</body>
</html>