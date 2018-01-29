@if(false)
    <form method="POST" action="{{ route('place.cover.store') }}" enctype="multipart/form-data">
@endif

<p><strong>Place cover:</strong></p>
<div class="form-group" id="place_cover_box">
    {{ csrf_field() }}
    <div class="fileinput fileinput-new text-center" data-provides="fileinput">
        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
            <div class="fileinput-new thumbnail">
                <img src="{{ asset('img/image_placeholder.jpg') }}" alt="Place cover">
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail" style=""></div>
            <div class="btn btn-default btn-fill btn-file">
                <span class="fileinput-new">Pick cover</span>
                <span class="fileinput-exists">Change cover</span>
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