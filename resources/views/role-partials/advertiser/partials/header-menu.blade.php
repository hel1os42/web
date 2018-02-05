@if(!$isPlaceCreated)
    <li><a href="{{ route('places.create') }}">Fill place info</a></li>
@else
    <li><a href="{{ route('profile.place.show') }}">Place</a></li>
@endif
@if($isPlaceCreated)
    <li><a href="{{ route('advert.offers.index').'?orderBy=updated_at&sortedBy=desc' }}">Offers</a></li>
@endif
<li><a href="{{ route('transaction.list') }}">Operations</a></li>
<li class="sub-menu">
    <a href="{{ route('advert.operators.index') }}">Operators</a>
    <ul>
        <li><a href="{{ route('advert.operators.index') }}">Operators</a></li>
        <li><a href="{{ route('advert.operators.create') }}">Create operator</a></li>
    </ul>
</li>