@if(in_array('advertiser', array_column($user['roles'], 'name')))
    @if($user['approved'])
        <span style="color:green">Yes</span>
    @else
        <span style="color:red">No</span>
    @endif
    <form action="{{route('users.update', $user['id'])}}" method="post"
          style="display:  inline-block;">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        @if($user['approved'])
            <input hidden type="text" name="approved" value="0">
            <button style="display:  inline-block;" type="submit">disapprove
            </button>
        @else
            <input hidden type="text" name="approved" value="1">
            <button style="display:  inline-block; padding: 3px;" type="submit" class="btn">approve
            </button>
        @endif
    </form>
@else
    -
@endif