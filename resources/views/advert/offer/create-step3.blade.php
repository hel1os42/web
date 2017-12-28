<div id="tab_step3" class="tab-pane fade">
    <p class="title">Working area</p>

    <div class="control-box">
        <p class="control-select valid-not-empty">
            <label>
                <span class="input-label">Your place</span>
                <select name="____place">
                    <option value="" selected>Select a place</option>
                    <option value="Place 1">Place 1</option>
                    <option value="Place 2">Place 2</option>
                    <option value="Place 3">Place 3</option>
                </select>
            </label>
        </p>
        <p class="hint">Please, select a place.</p>
    </div>

    <div class="control-box">
        <p><strong>Setting map radius</strong></p>

        <div class="map-wrap">
            <div class="leaflet-map" id="mapid"></div>
            <div id="marker"></div>
        </div>

        @push('styles')
            <link rel="stylesheet" type="text/css" href="{{ asset('css/leaflet.css') }}">
        @endpush

        @push('scripts')
            <script src="{{ asset('js/leaflet.js') }}"></script>
            <script>
                window.addEventListener('load', function() {
                    let GPS = {};
                    if (navigator.geolocation) navigator.geolocation.getCurrentPosition(getGPS, defaultGPS);
                    else defaultGPS();

                    function getGPS(pos){
                        GPS = { lat: pos.coords.latitude, lng: pos.coords.longitude };
                        mapInitialize(GPS);
                    }

                    function defaultGPS(){
                        /* Los Angeles: */
                        GPS = { lat: 34.0143733, lng: -118.2831973 };
                        mapInitialize(GPS);
                    }

                    function mapInitialize(GPS){

                        function getAddress(GPS) {
                            return location.protocol + `//nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${GPS.lat}&lon=${GPS.lng}`;
                        }

                        let offerMap = L.map('mapid', { center: [34.0143733, -118.2831973], zoom: 13 });//.setView(GPS, 16);
                        L.circle([34.0143733, -118.2831973], 50, {
                            color: 'red',
                            fillColor: '#f03',
                            fillOpacity: 0.5
                        }).addTo(offerMap).bindPopup("I am a circle.");
                        /*L.tileLayer(getAddress(GPS), {
                            maxZoom: 19,
                            minZoom: 1,
                        }).addTo(offerMap);
                        /*L.tileLayer(getAddress(GPS), {
                            attribution: '© OpenStreetMap',
                            maxZoom: 19,
                            minZoom: 1,
                            maxNativeZoom: 18,
                        }).addTo(offerMap);*/
                        /*offerMap.options = {
                            //layers: [offerMap.tileLayer],
                            center: L.latLng(GPS.lat, GPS.lng),
                            zoom: 13,
                            zoomSnap: 0.5,
                            zoomDelta: 0.5
                        };

                        /*offerMap.tileLayer = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            minZoom: 1,
                            maxNativeZoom: 18,
                            attribution: '© OpenStreetMap',
                            tileSize: 512,
                            zoomOffset: -1,
                            detectRetina: true
                        });*/

                        console.dir(offerMap);
                    }
                });
            </script>
        @endpush
    </div>

    <p class="step-footer">
        <a href="#tab_step2" data-toggle="tab" class="tab-nav btn-nau pull-left">&lt; prev step</a>
        <a href="#tab_step4" data-toggle="tab" class="tab-nav btn-nau pull-right">next step &gt;</a>
    </p>
</div>