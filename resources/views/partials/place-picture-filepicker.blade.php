@if(false)
    <form method="POST" action="{{ route('place.picture.store') }}" enctype="multipart/form-data">
@endif

<p><strong>Place picture:</strong></p>
<div class="form-group" id="place_picture_box">
    {{ csrf_field() }}
    <div class="fileinput fileinput-new text-center" data-provides="fileinput">
        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
            <div class="fileinput-new thumbnail">
                <img src="{{ asset('img/image_placeholder.jpg') }}" alt="Place picture">
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail" style=""></div>
            <div class="btn btn-default btn-fill btn-file">
                <span class="fileinput-new">Pick picture</span>
                <span class="fileinput-exists">Change picture</span>
                <input type="hidden">
                <input type="file" name="picture">
            </div>
        </div>
    </div>
    @if(false)
        <input class="btn btn-rose btn-wd btn-md" type="submit">
    @endif
</div>

@if(false)
    </form>
@endif
