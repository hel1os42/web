@if($parents->count())
    <div class="row">
        <div class="col-sm-3 p-5">
            <strong>{{ __('users.fields.parents') }}</strong>
        </div>
        <div class="col-sm-9 p-5">
            @foreach($parents as $user)
                @php
                    $contacts = $user['email'] ?: $user['phone'];
                    $role     = __('words.' . $user['roles']->pluck('name')->implode(', '));
                @endphp

                <p data-id="{{ $user['id'] }}" class="m-b-5">
                    {!! sprintf('%s (%s) - <i>%s</i>', $user['name'], $contacts, $role) !!}
                    <a href="{{ route('users.show', $user['id']) }}">
                        <i class="fa fa-pencil-square-o m-l-5" aria-hidden="true"></i>
                    </a>
                </p>

            @endforeach
        </div>
    </div>
@endif

<div class="row">
    <div class="col-sm-3 p-5">
        {{ __('users.fields.id') }}
    </div>
    <div class="col-sm-9 p-5" id="editable_user_id">
        {{ $id }}
    </div>
</div>

<div class="row">
    <div class="col-sm-3 p-5">
        {{ __('users.fields.approved') }}
    </div>
    <div class="col-sm-9 p-5">
        <div class="user-approve-controls status-{{ $approved ? '' : 'dis' }}approved">
            <span class="span-approved">{{ __('words.yes') }}</span>
            <span class="span-disapproved">{{ __('words.no') }}</span>
            <span class="span-wait">...</span>

            <form action="{{ route('users.update', $id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <input type="hidden" name="approved" value="{{ $approved ? '0' : '1' }}">
                <button type="submit" class="btn btn-xs b-approved">{{ __('buttons.disapprove') }}</button>
                <button type="submit" class="btn btn-xs b-disapproved">{{ __('buttons.approve') }}</button>
                <span class="waiting-response"><img src="{{ asset('img/loading.gif') }}" alt="wait..."></span>
            </form>
        </div>
    </div>
</div>