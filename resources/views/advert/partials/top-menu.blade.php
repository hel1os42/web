<nav>
    <menu>
        @if(!$isPlaceCreated)
            <li><a href="{{ route('places.create') }}">Fill place info</a></li>
        @else
            <li><a href="{{ route('profile.place.show') }}">Place</a></li>
        @endif
        <li><a href="{{ route('advert.offers.index') }}">Offers</a></li>
    </menu>
</nav>