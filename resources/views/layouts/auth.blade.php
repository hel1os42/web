@include('partials.head')
<body>
<div class="wrapper wrapper-full-page">
    <div class="full-page login-page"  data-color="blue">
        <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                        @include('partials.msg')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

@stack('scripts')