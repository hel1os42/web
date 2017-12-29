<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no"/>

    @include('advert.styles')
    @stack('styles')
</head>

<body>

<div id="mainwrapper">
    <header>
        @section('header')
            @include('advert.partials.header')
        @show
    </header>

    <main class="clearfix">
        @yield('content')
    </main>
</div>

<footer>
    @section('footer')
        @include('advert.partials.footer')
    @show
</footer>

</body>

@include('advert.scripts')
@stack('scripts')

</html>
