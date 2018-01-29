<div class="col-sm-6 p-5">
    <p><strong>Id</strong></p>
    <p><strong>Approved</strong></p>
</div>
<div class="col-sm-6 p-5">
    <p>{{$id}}</p>
    <div>
        @if($approved)
            <p style="color:green">Yes</p>
        @else
            No
        @endif
        <form action="{{route('users.update', $id)}}" method="post"
              style="display:  inline-block;">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            @if($approved)
                <input hidden type="text" name="approved" value="0">
                <button style="display:  inline-block;" type="submit">disapprove
                </button>
            @else
                <input hidden type="text" name="approved" value="1">
                <button style="display:  inline-block;" type="submit">approve
                </button>
            @endif
        </form>
    </div>
</div>