@guest
    <script>
        /* анонимов со всех страниц, кроме /auth/* сразу перенаправляем на логинацию */
        if (location.pathname.substr(0, 6) !== '/auth/') location.pathname = '/auth/login';
    </script>
@endguest

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
                {{ auth()->user()->name }}
            </div>
        @endauth
    </div>
    @auth
        <nav>
            <menu>
                @if(!auth()->user()->isAdvertiser())
                    <li><a href="{{ route('home') }}">Home</a></li>
                @endif
                @if(auth()->user()->isAdvertiser())
                    @if(!$isPlaceCreated)
                        <li><a href="{{ route('places.create') }}">Fill place info</a></li>
                    @else
                        <li><a href="{{ route('profile.place.show') }}">Place</a></li>
                    @endif
                    @if(false)
                    <li><a href="{{ route('profile.place.show') }}">Place show</a></li>
                    <li><a href="{{ route('profile.place.offers') }}">Place offers</a></li>
                    @endif
                    <li class="sub-menu">
                        <a href="{{ route('advert.offers.index') }}">Offers</a>
                        @if(false)
                        <ul>
                            <li><a href="{{ route('advert.offers.index') }}">Dashboard</a></li>
                            <li><a href="{{ route('advert.offers.create') }}">Create offer</a></li>
                        </ul>
                        @endif
                    </li>
                    <li class="sub-menu">
                        <a href="{{ route('transactionList') }}">Operations</a>
                        @if(false)
                        <ul>
                            <li><a href="{{ route('transactionList') }}">Transactions list</a></li>
                            <li><a href="{{ route('transactionCreate') }}">Create transaction</a></li>
                        </ul>
                        @endif
                    </li>
                @endif
                @can('users.list')
                    <li>
                    <a href="{{ route('users.index') }}">{{auth()->user()->isAgent() ? 'Advertisers' : 'Users'}}</a>
                    </li>
                @endcan
                <li><a href="{{ route('referrals') }}">Referrals</a></li>
            </menu>
        </nav>
    @endauth
</div>
