<nav>
    <menu>
        @if(!$isPlaceCreated)
            <li><a href="{{ route('places.create') }}">Fill account info</a></li>
        @else
            <li><a href="{{ route('advert.profile') }}">Profile</a></li>
        @endif
        <li><a href="{{ route('advert.offers.index') }}">Offers</a></li>
    </menu>
</nav>