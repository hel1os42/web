<div class="container">
    <div class="clearfix">
        <div class="logo pull-left">
            <a href="/"><img src="{{ asset('img/logo.png') }}" alt="nau.io"></a>
        </div>
        @auth
            <div class="controls pull-right">
                <a href="{{ route('advert.profile') }}" title="Pofile"><i class="fa fa-user-o"></i></a>
                <a href="{{ route('logout') }}" title="Logout"><i class="fa fa-sign-out"></i></a>
            </div>
            <div class="advert-name pull-right">
                {{ $authUser['name'] }}
            </div>
            @if(!is_null(auth()->user()))
                @if(auth()->user()->isImpersonated())
                    <div class="advert-name pull-right">
                        <a href="{{ route('stop_impersonate') }}" class="btn-nau btn-create-offer dropdown-toggle" style="width:auto">
                            <i class="fa fa-fighter-jet"></i>
                            Leave impersonation
                        </a>
                    </div>
                @endif
            @endif
        @endauth
    </div>
    @auth
        @include('advert.partials.top-menu')
    @endauth
</div>
