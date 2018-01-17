<div class="container">
    <div class="clearfix">
        <div class="logo pull-left">
            <a href="/"><img src="{{ asset('img/logo.png') }}" alt="nau.io"></a>
        </div>
        @auth
            <div class="controls pull-right">
                @if(!is_null(auth()->user()))
                    @if(auth()->user()->isImpersonated())
                            <a href="{{ route('stop_impersonate') }}" class="dropdown-toggle" style="width:auto">
                                <i class="fa fa-fighter-jet"></i>
                                Leave impersonation
                            </a>
                    @endif
                @endif
                <a href="{{ route('profile') }}" title="Pofile"><i class="fa fa-user-o"></i></a>
                <a href="{{ route('logout') }}" title="Logout"><i class="fa fa-sign-out"></i></a>
            </div>

            <div class="advert-name pull-right">
                {{ $authUser["name"] }}
            </div>

            <div class="advert-name pull-right">
                @if(isset($authUser["accounts"]["NAU"]["balance"]))
                Balance: <span style="color: #f08301;"> {{$authUser["accounts"]["NAU"]["balance"]}}  NAU</span>
                @else
                    The problem of getting a balance, contact us if this message does not disappear in the near future.
                    @endif
            </div>
        @endauth
    </div>
    @auth
        <nav>
            <menu>
                @include('role-partials.selector', ['partialRoute' => 'partials.header-menu'])
            </menu>
        </nav>
    @endauth
</div>
