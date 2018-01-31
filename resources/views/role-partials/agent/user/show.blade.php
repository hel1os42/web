<div class="col-sm-6 p-5">
    <p><strong>Id</strong></p>
    <p><strong>Approved</strong></p>
</div>
<div class="col-sm-6 p-5">
    <p>{{$id}}</p>
    <div>
        @if($approved)
            <p style="color: green;">Yes</p>
        @else
            No
            <form action="{{route('users.update', $id)}}" method="post"
                  style="display: inline-block;">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <input type="hidden" name="approved" value="1">
                <button type="submit" class="btn btn-xs">approve</button>
            </form>
        @endif
    </div>
</div>