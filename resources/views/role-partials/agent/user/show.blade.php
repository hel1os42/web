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
            <form action="{{route('users.update', $id)}}" method="post"
                  style="display:  inline-block;">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <input hidden type="text" name="approved" value="1">
                <button style="display:  inline-block;" type="submit">approve
                </button>
            </form>
        @endif
    </div>
</div>