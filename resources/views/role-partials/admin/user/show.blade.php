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

        <form action="{{ route('users.update', $id) }}" method="PATCH">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <input type="hidden" name="approved" value="{{ $approved ? '0' : '1' }}">
            <button type="submit" class="btn btn-xs b-approved">disapprove</button>
            <button type="submit" class="btn btn-xs b-disapproved">approve</button>
            <span class="waiting-response"><img src="{{ asset('img/loading.gif') }}" alt="wait..."></span>
        </form>
    </div>
</div>