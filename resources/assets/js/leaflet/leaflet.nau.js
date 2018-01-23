/* function mapInit({ id, setPosition, done, move })
*
* id - string
* setPosition - { lat, lng, radius }, optional
* done - function, optional
* move - function, optional
*
* */

/* example

mapInit({
    id: 'mapid',
    setPosition: null,
    done: mapDone,
    move: mapMove
});

function mapDone(map){
    let values = mapValues(map);
    values.lat
    values.lng
    values.radius
}

function mapMove(map){
    let values = mapValues(map);
    ...
}
*/

function mapInit(options){

    let $map = $('#' + options.id);
    if ($map.length === 0) {
        console.log('Map not found.');
        return false;
    }

    if (options.setPosition) {
        if (!options.setPosition.lat) options.setPosition.lat = 34.0143733; /* Los Angeles: */
        if (!options.setPosition.lng) options.setPosition.lng = -118.2831973;
        if (!options.setPosition.radius) options.setPosition.radius = 1500;
        mapInitialize(options, { lat: options.setPosition.lat, lng: options.setPosition.lng });
    } else {
        function locationSuccess(pos){ mapInitialize(options, { lat: pos.coords.latitude, lng: pos.coords.longitude }); }
        function locationError(){ mapInitialize(options, { lat: 34.0143733, lng: -118.2831973 }); } /* Los Angeles: */
        navigator.geolocation.getCurrentPosition(locationSuccess, locationError);
    }

    function mapInitialize(options, GPS) {

        let Zoom = options.setPosition ? getZoom(GPS.lat, options.setPosition.radius) : 13;

        let map = L.map(options.id, { center: GPS, zoom: Zoom });
        L.tileLayer( '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom:       19,
            minZoom:       1,
            maxNativeZoom: 18,
            attribution:   '© OpenStreetMap',
        }).addTo(map);

        if (options.done) {
            options.done(map);
        }

        if (options.move) {
            $(map).on('zoomend, moveend', function(){ options.move(this); });
        }
    }

    function getZoom(latitude, radius) {
        function round(value, step) {
            step || (step = 1.0);
            let inv = 1.0 / step;
            return Math.round(value * inv) / inv;
        }
        /* не знаю в чём прикол, но ввместо радиуса мы получаем диаметр, потому делю на 2 */
        radius = radius / 2;
        return round(Math.log2(40075016.686 * 75 * Math.abs(Math.cos(latitude / 180 * Math.PI)) / radius) - 8, 0.25);
    }
}

function mapValues(map){
    let res = {};
    let radiusPx = 190;
    res.lat = map.getCenter().lat;
    res.lng = map.getCenter().lng;
    while (res.lng > 180) res.lng -= 360;
    while (res.lng < -180) res.lng += 360;
    res.radius = Math.round(getRadius(radiusPx, map));
    return res;

    function getRadius(radiusPx, map) {
        return 40075016.686 * Math.abs(Math.cos(map.getCenter().lat / 180 * Math.PI)) / Math.pow(2, map.getZoom()+8) * radiusPx;
    }
}


