<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>

<div id="app">

    <md-layout md-flex="100">
        <vmenu></vmenu>
    </md-layout>

    <md-layout md-gutter="24">

        <md-layout md-row>
            @yield('content')
        </md-layout>

    </md-layout>

    <md-layout>
        <md-layout md-flex="33">
            <md-subheader>
                About
            </md-subheader>
        </md-layout>
        <md-layout md-flex="33">
            <md-subheader>
                Column 2
            </md-subheader>
        </md-layout>
        <md-layout md-flex="33">
            <md-subheader>
                Column 3
            </md-subheader>
        </md-layout>
    </md-layout>

</div>







<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
