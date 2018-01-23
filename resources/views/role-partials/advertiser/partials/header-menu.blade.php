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
    <a href="{{ route('transaction.list') }}">Operations</a>
    @if(false)
        <ul>
            <li><a href="{{ route('transaction.list') }}">Transactions list</a></li>
            <li><a href="{{ route('transaction.create') }}">Create transaction</a></li>
        </ul>
    @endif
</li>
<li class="sub-menu">
    <a href="{{ route('advert.operators.index') }}">Operators</a>
    <ul>
        <li><a href="{{ route('advert.operators.create') }}">Create operator</a></li>
    </ul>
</li>