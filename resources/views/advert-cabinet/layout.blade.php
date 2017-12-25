<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no"/>

    @include('styles')
    @stack('styles')
</head>

<body>

<div id="mainwrapper">
    <header>
        @section('header')
            @include('partials.header')
        @show
    </header>

    <main class="clearfix">
        @yield('content')
    </main>
</div>

<footer>
    @section('footer')
        @include('partials.footer')
    @show
</footer>

</body>

@include('partials.javascripts')
@stack('scripts')

</html>
