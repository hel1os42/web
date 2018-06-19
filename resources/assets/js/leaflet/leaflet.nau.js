/* function mapInit({ id, setPosition, done, move })
*
* id - string
* setPosition - { lat, lng, radius }, optional
* done - function, optional
* move - function, optional
*
*
*
* function getTimeZone(gps, callback)
* function getTimeZoneMap(map, callback)
*
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

const EQUATORIAL_LENGTH = 40075016.686;

const GOOGLE_API_KEY = 'AIzaSyC48pxde4WB6_JWxR9KOcLID8CAQQRyapo';
const GOOGLE_TIMEZONE_URI = 'https://maps.googleapis.com/maps/api/timezone/json?key=' + GOOGLE_API_KEY;
const GOOGLE_GEOCODE_URI = 'https://maps.googleapis.com/maps/api/geocode/json?key=' + GOOGLE_API_KEY;

function add0(n) { return n < 10 ? '0' + n : n.toString(); }

function mapInit(options){

    let $map = $('#' + options.id);
    if (!$map.length) { console.log('Map not found.'); return false; }

    if (options.setPosition) {
        options.setPosition.lat || (options.setPosition.lat = 34.0143733); /* Los Angeles */
        options.setPosition.lng || (options.setPosition.lng = -118.2831973);
        options.setPosition.radius || (options.setPosition.radius = 1500);
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
        if (options.done) options.done(map);
        if (options.move) $(map).on('zoomend, moveend', function(){ options.move(this); });
    }

    function getZoom(latitude, radius) {
        function round(value, step) {
            step || (step = 1.0);
            let inv = 1.0 / step;
            return Math.round(value * inv) / inv;
        }
        radius = radius / 2; /* we get diameter, not radius */
        return round(Math.log2(EQUATORIAL_LENGTH * 75 * Math.abs(Math.cos(latitude / 180 * Math.PI)) / radius) - 8, 0.25);
    }
}

function leafletGetURI(uri, handle){
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if (xhr.readyState !== 4) return;
        //if (xhr.status === 0) AdBlockNotification();
        if (xhr.status === 200) handle(xhr);
    };
    xhr.open('GET', uri, true);
    xhr.send();
}

function leafletConvertRawOffset(raw){
    let timeZone = 'error';
    if (raw || raw === 0) {
        let absRawInHr = Math.abs(raw / 3600);
        let h = Math.trunc(absRawInHr);
        let m = Math.round((absRawInHr - h) * 60);
        timeZone = (raw < 0 ? '-' : '+') + add0(h) + add0(m);
    }
    console.log('timezone: ' + timeZone);
    return timeZone;
}

function mapValues(map){
    let radiusPx = 190;
    let res = { lat: map.getCenter().lat, lng: map.getCenter().lng };
    while (res.lng > 180) res.lng -= 360;
    while (res.lng < -180) res.lng += 360;
    res.radius = Math.round(getRadius(radiusPx, map));
    return res;

    function getRadius(radiusPx, map) {
        return EQUATORIAL_LENGTH * Math.abs(Math.cos(map.getCenter().lat / 180 * Math.PI)) / Math.pow(2, map.getZoom() + 8) * radiusPx;
    }
}

function getTimeZone(gps, callback){
    let timestamp = Math.round(new Date().valueOf() / 1000);
    if (typeof gps.lng === 'string') gps.lng = parseFloat(gps.lng);
    while (gps.lng > 180) gps.lng -= 360;
    while (gps.lng < -180) gps.lng += 360;
    let uri = GOOGLE_TIMEZONE_URI + '&location=' + gps.lat + ',' + gps.lng + '&timestamp=' + timestamp;
    leafletGetURI(uri, function(xhr){
        xhr = JSON.parse(xhr.response);
        console.dir(xhr);
        let timeZone = leafletConvertRawOffset(xhr.rawOffset);
        callback(timeZone, xhr);
    });
}

function getTimeZoneMap(map, callback){
    getTimeZone({
        lat: map.getCenter().lat,
        lng: map.getCenter().lng
    }, callback);
}

function getGpsByAddress(address, callback){
    let uri = GOOGLE_GEOCODE_URI + '&address=' + encodeURIComponent(address);
    leafletGetURI(uri, function(xhr){
        xhr = JSON.parse(xhr.response);
        console.groupCollapsed('Get GPS by Address');
        console.log(address);
        console.dir(xhr);
        console.groupEnd();
        callback(xhr);
    });
}

function getAddressByGps(lat, lng, callback){
    let uri = GOOGLE_GEOCODE_URI + '&latlng=' + lat + ',' + lng;
    leafletGetURI(uri, function(xhr){
        xhr = JSON.parse(xhr.response);
        console.groupCollapsed('Get Address by GPS');
        console.log(lat, lng);
        console.dir(xhr);
        console.groupEnd();
        callback(xhr);
    });
}