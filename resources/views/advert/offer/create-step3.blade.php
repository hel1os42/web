<div class="map-wrap">
<div class="leaflet-map" id="mapid"></div>
<div id="marker"></div>
</div>
<div id="tab_step3" class="tab-pane fade">
<p class="title">Working area</p>

<div class="control-box">
<p><strong>Setting map radius</strong></p>

{{--<div class="map-wrap">--}}
            {{--<div class="leaflet-map" id="mapid"></div>--}}
            {{--<div id="marker"></div>--}}
        {{--</div>--}}

        @push('styles')
            <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
        @endpush

        @push('scripts')
            <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>

            <script>
                $( document ).ready( function() {
                    let GPS = {};
                    if ( navigator.geolocation ) {
                        navigator.geolocation.getCurrentPosition( getGPS, defaultGPS );
                    } else {
                        defaultGPS();
                    }

                    function getGPS( pos ) {
                        GPS = {
                            lat: pos.coords.latitude,
                            lng: pos.coords.longitude
                        };
                        mapInitialize( GPS );
                    }

                    function defaultGPS() {
                        /* Los Angeles: */
                        GPS = {
                            lat: 34.0143733,
                            lng: -118.2831973
                        };
                        mapInitialize( GPS );
                    }


                    function mapInitialize( GPS ) {

                        let map = L.map( 'mapid', {
                            center: GPS,
                            zoom:   13
                        } );

                        L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom:       19,
                            minZoom:       1,
                            maxNativeZoom: 18,
                            attribution:   '© OpenStreetMap',
                        } ).addTo( map );

                        map.on('zoomend, moveend', function(e){
                            console.log(this.getCenter());
                            console.log(this.getZoom());
                            console.log(this);
                        });

//                        map.on({
//                            moveend: {
//                                this.coords = this._map.getCenter();
//                                this.geocoder.getAddress(this.coords.lat, this.coords.lng)
//                                    .subscribe(data => {
//                                        let address = data.address;
//                                        this.city = address.city || address.town || address.county || address.state;
//                                        this.country = address.country;
//                                        this.changeDetectorRef.detectChanges();
//                                    })
//                                this.coords = this._map.getCenter();
//                                this.radius = MapUtils.getRadius(95, this._map);
//                                this.zoom = map.getZoom();
//                            }
//                        });
                    }
                } );
            </script>
        @endpush
    </div>

    <p class="step-footer">
        <a href="#tab_step2" data-toggle="tab" class="tab-nav btn-nau pull-left">&lt; prev step</a>
        <a href="#tab_step4" data-toggle="tab" class="tab-nav btn-nau pull-right">next step &gt;</a>
    </p>
</div>`