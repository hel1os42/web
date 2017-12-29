
{{--<div id="tab_step3" class="tab-pane fade">--}}
    <p class="title" style="margin-top: 110px;">Working area</p>

    <div class="control-box">
        <p><strong>Setting map radius</strong></p>

        <div class="map-wrap" >
            <div class="leaflet-map" id="mapid"></div>
            <div id="marker"></div>
        </div>

        <input type="hidden" name="latitude" value="" class="mapFields nullableFormData">
        <input type="hidden" name="longitude" value="" class="mapFields nullableFormData">
        <input type="hidden" name="radius" value="" class="mapFields nullableFormData">

        @push('styles')
            <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
        @endpush

        @push('scripts')
            <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
        @endpush
    </div>

    <div class="control-box">
        <p class="control-text">
            <label>
                <span class="input-label">Country</span>
                <input name="country" value="" class="nullableFormData">
            </label>
        </p>
    </div>

    <div class="control-box">
        <p class="control-text">
            <label>
                <span class="input-label">City</span>
                <input name="city" value="" class="nullableFormData">
            </label>
        </p>
    </div>



    {{--<p class="step-footer">--}}
        {{--<a href="#tab_step2" data-toggle="tab" class="tab-nav btn-nau pull-left">&lt; prev step</a>--}}
        {{--<a href="#tab_step4" data-toggle="tab" class="tab-nav btn-nau pull-right">next step &gt;</a>--}}
    {{--</p>--}}
{{--</div>`--}}