<div class="container">
    <div class="clearfix">
        <div class="logo pull-left">
            <a href="/"><img src="{{ asset('img/logo.png') }}" alt="nau.io"></a>
        </div>
        @auth
            <div class="controls pull-right">
{{--                {{ dd(get_defined_vars()['__data']) }}--}}
                <a href="{{ route('profile.place.show') }}" title="Pofile"><i class="fa fa-user-o"></i></a>
                <a href="{{ route('logout') }}" title="Logout"><i class="fa fa-sign-out"></i></a>
            </div>
            <div class="advert-name pull-right">
                {{ auth()->user()->name }}
            </div>
        @endauth
    </div>
    @auth
        @include('advert.partials.top-menu')
    @endauth
</div>
