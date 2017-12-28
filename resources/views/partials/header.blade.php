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
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('referrals') }}">Referrals</a></li>
                <li><a href="{{ route('profile.place.show') }}">Place show</a></li>
                <li><a href="{{ route('profile.place.offers') }}">Place offers</a></li>
                <li class="sub-menu">
                    <a href="{{ route('advert.offers.index') }}">Offers</a>
                    <ul>
                        <li><a href="{{ route('advert.offers.index') }}">Dashboard</a></li>
                        <li><a href="{{ route('advert.offers.create') }}">Create offer</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="{{ route('transactionList') }}">Operations</a>
                    <ul>
                        <li><a href="{{ route('transactionList') }}">Transactions list</a></li>
                        <li><a href="{{ route('transactionCreate') }}">Create transaction</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="{{ route('places.index') }}">Places</a>
                    <ul>
                        <li><a href="{{ route('places.index') }}">Places list</a></li>
                        <li><a href="{{ route('places.create') }}">Create</a></li>
                    </ul>
                </li>
            </menu>
        </nav>
    @endauth
</div>
