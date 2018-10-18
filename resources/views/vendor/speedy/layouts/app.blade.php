<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    {{--<link href="/css/app.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/vendor/speedy/css/sidebar.css">
    <link href="{{ asset('css/loading.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <style>
        .table > thead > tr > th,
        .table > tbody > tr > th,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > td,
        .table > tfoot > tr > td {
            vertical-align: unset;
        }
    </style>
</head>
<body>
@include('vendor.speedy.layouts.loading')
@include('vendor.speedy.partials.sidebar')
<div id="app" style="display: none;">
    <div class="content-wrapper">
        <nav class="navbar navbar-default navbar-static-top" style="background-color: #fff;">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}" style="color: #5c5b4b;">
                        {{ config('app.name', 'Laravel') }} - Information Management System
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav" >
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right" style="background-color: #2A9D8F;">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li style="background-color: #2A9D8F;"><a href="{{ route('login') }}">Login</a></li>
                            <li style="background-color: #2A9D8F;"><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown" style="background-color: #2A9D8F;">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false" style="color: #fff;background-color: #2A9D8F;">
                                    {{ Auth::user()->role ? Auth::user()->role->display_name.' - ':'' }} {{ Auth::user()->display_name }}
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu" style="color: white;">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();" style="color:  #2A9D8F;">
                                            登出
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
</div>

<!-- Scripts -->
{{--<script src="/js/app.js"></script>--}}
<script
        src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="https://unpkg.com/vue@2.1.10/dist/vue.min.js"></script>
<script>
    window.onload = function () {

    };

    $(document).ready(function(){
        $('#sidebar').fadeIn(150);
        $('#app').fadeIn(200);
        $('.spinner').fadeOut(300);
    })

    window.onbeforeunload = function(event) {
        $('#sidebar').fadeOut(50);
        $('#app').fadeOut(50);
        $('.spinner').fadeIn(100);
    };

    window.onunload = function() {
        $('#sidebar').fadeOut(50);
        $('#app').fadeOut(50);
        $('.spinner').fadeIn(100);
    };

    var sidebar = new Vue({
        el: '#sidebar',
        data: {
            menus: [],
            active: ''
        },
        methods: {
            toggleMenu: function (id) {
                this.active = this.active == id ? '' : id;
            },
            setMenus: function (menus) {
                this.menus = menus;
            },
            isCurrentUrl: function (url, key) {
                var parser = document.createElement('a');
                parser.href = url;

                if (parser.pathname === window.location.pathname) {
                    this.active = key ? key : this.active;
                    return true;
                }

                return false;
            }
        },
        created: function () {
            this.setMenus(JSON.parse('{!! Speedy::getMenus(true) !!}'));
        }
    });

</script>
</body>
</html>
