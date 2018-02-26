@if(in_array('advertiser', array_column($user['roles'], 'name')))
    <div class="user-approve-controls status-{{ $user['approved'] ? '' : 'dis' }}approved">
        <span class="span-approved">Yes</span>
        <span class="span-disapproved">No</span>
        <span class="span-wait">...</span>

        <form action="{{ route('users.update', $user['id']) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <input type="hidden" name="approved" value="{{ $user['approved'] ? '0' : '1' }}">
            <button type="submit" class="btn btn-xs b-approved">disapprove</button>
            <button type="submit" class="btn btn-xs b-disapproved">approve</button>
            <span class="waiting-response"><img src="{{ asset('img/loading.gif') }}" alt="wait..."></span>
        </form>
    </div>
@else
    -
@endif