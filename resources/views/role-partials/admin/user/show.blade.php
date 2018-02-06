<div class="col-sm-6 p-5">
    <p><strong>Id</strong></p>
    <p><strong>Approved</strong></p>
</div>
<div class="col-sm-6 p-5">
    <p>{{$id}}</p>
    <div>
        @if($approved)
            <span style="color: green;">Yes</span>
        @else
            No
        @endif
        <form action="{{route('users.update', $id)}}" method="post" style="display: inline-block;">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            @if($approved)
                <input type="hidden" name="approved" value="0">
                <button type="submit" class="btn btn-xs">disapprove</button>
            @else
                <input type="hidden" name="approved" value="1">
                <button type="submit"class="btn btn-xs">approve</button>
            @endif
        </form>
    </div>
</div>