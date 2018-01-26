@if(false)
    <form method="POST" action="{{ route('offer.picture.store', ['offerId' => $offerId]) }}" enctype="multipart/form-data">
@endif

<p><strong>Offer image:</strong></p>
<div class="form-group" id="offer_image_box">
    {{ csrf_field() }}
    <div class="fileinput fileinput-new text-center" data-provides="fileinput">
        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
            <div class="fileinput-new thumbnail">
                <img src="{{ asset('img/image_placeholder.jpg') }}" alt="Offer image">
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail" style=""></div>
            <div class="btn btn-default btn-fill btn-file">
                <span class="fileinput-new">Pick image</span>
                <span class="fileinput-exists">Change image</span>
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
