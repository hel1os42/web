<div class="row">
    <div class="col-sm-3 p-5">
        {{ __('users.fields.approved') }}
    </div>
    <div class="col-sm-9 p-5">
        <div>
            @if($approved)
                <p style="color:green">{{ __('words.yes') }}</p>
            @else
                <p style="color:red">{{ __('words.no') }}</p>
            @endif
        </div>
    </div>
</div>