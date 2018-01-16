@if(!$isPlaceCreated)
    <li><a href="{{ route('places.create') }}">Fill place info</a></li>
@else
    <li><a href="{{ route('profile.place.show') }}">Place</a></li>
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