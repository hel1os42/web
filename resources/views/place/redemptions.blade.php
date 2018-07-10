@if (isset($data))
    @foreach($data as $redemptions)
        <p>{{ $redemptions }}</p>
    @endforeach
@endif