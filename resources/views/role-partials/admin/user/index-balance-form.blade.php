Source: <select id="source" type="text" name="source">
    <option selected value="{{auth()->user()->getAccountForNau()->getAddress()}}">Your account (Balance: {{auth()->user()->getAccountForNau()->getBalance()}} NAU)</option>
    @foreach($specialUserAccounts as $account)
        <option value="{{$account['address']}}">{{$account['nau_owner_name']}} (Balance: {{$account['balance']}} NAU)</option>
    @endforeach
</select><br>