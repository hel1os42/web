<!DOCTYPE html>
<html>
    @include('partials.head')
    <body>
    @include('partials.msg')
        <div class="wrapper perfect-scrollbar-off">
            @section('sidebar')
                @include('partials.sidebar')
            @show
            <div class="main-panel">
                @section('main-panel')
                    @include('partials.header')
                @show
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    @stack('scripts')
    @include('partials.javascripts')
</html>
