<p class="title" style="margin-top: 40px;">Working area</p>

<div class="control-box" id="map_box">
    <p><strong>Setting map radius</strong></p>

    <div class="map-wrap" >
        <div class="leaflet-map" id="mapid">
        </div>
        <div id="marker"></div>
    </div>
    <p id="mapradius">Radius: <span>unknown</span> km.</p>

    <input type="hidden" name="latitude" value="" class="mapFields nullableFormData">
    <input type="hidden" name="longitude" value="" class="mapFields nullableFormData">
    <input type="hidden" name="radius" value="" class="mapFields nullableFormData">
    <input type="hidden" name="timezone" value="">
</div>
<p class="hint">You can not choose sea or ocean.</p>

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
