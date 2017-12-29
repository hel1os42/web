<form method="POST" action="{{route('place.picture.store')}}" enctype="multipart/form-data">
    <label>Set logo:</label>
    <div class="form-group">
        {{ csrf_field() }}
        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
            <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                <div class="fileinput-new thumbnail">
                    <img src="{{asset('img/image_placeholder.jpg')}}" alt="...">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style=""></div>
                <div class="btn btn-default btn-fill btn-file">
                    <span class="fileinput-new">Pick logo</span>
                    <span class="fileinput-exists">Change logo</span>
                    <input type="hidden">
                    <input type="file" name="picture">
                </div>
            </div>
        </div>
    <input class="btn btn-rose btn-wd btn-md" type="submit">
    </div>
</form>
