{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

@if ($errors)
    @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
@endif

<form action="{{route('transComplete')}}" method="post" target="_top">
    {{ csrf_field() }}

    <label>sender</label><input type="text" name="sender" value="{{ old('sender') }}"> <br>
    <label>destination</label><input type="text" name="destination" value="{{ old('destination') }}"> <br>
    <label>amount</label><input type="text" name="amount" value="{{ old('amount') }}"> <br>
    <input type="submit" value="Send">
</form>
