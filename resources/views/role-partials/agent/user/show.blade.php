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

                <div data-id="{{ $user['id'] }}" class="m-b-5">
                    {!! sprintf('%s (%s) - <i>%s</i>', $user['name'], $contacts, $role) !!}
                    <a href="{{ route('users.show', $user['id']) }}">
                        <i class="fa fa-pencil-square-o m-l-5" aria-hidden="true"></i>
                    </a>
                </div>

            @endforeach
        </div>
    </div>
@endif

<div class="row">
    <div class="col-sm-3 p-5">
        <p><strong>Id</strong></p>
        <p><strong>Approved</strong></p>
    </div>
    <div class="col-sm-9 p-5">
        <p>{{ $id }}</p>
        <div class="user-approve-controls status-{{ $approved ? '' : 'dis' }}approved">
            <span class="span-approved">Yes</span>
            <span class="span-disapproved">No</span>
            <span class="span-wait">...</span>

            <form action="{{ route('users.update', $id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <input type="hidden" name="approved" value="{{ $approved ? '0' : '1' }}">
                <button type="submit" class="btn btn-xs b-approved">disapprove</button>
                <button type="submit" class="btn btn-xs b-disapproved">approve</button>
                <span class="waiting-response"><img src="{{ asset('img/loading.gif') }}" alt="wait..."></span>
            </form>
        </div>
    </div>
</div>