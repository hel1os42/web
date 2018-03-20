@if (isset($data))
    @foreach($data as $redemptions)
        @foreach($redemptions as $key => $field)
            <p>{{$key}} {{ $field }}</p>
        @endforeach
    @endforeach
@endif