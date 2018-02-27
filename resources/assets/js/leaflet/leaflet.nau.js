/* function mapInit({ id, setPosition, done, move })
*
* id - string
* setPosition - { lat, lng, radius }, optional
* done - function, optional
* move - function, optional
*
*
*
* function getTimeZone(map, callback)
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


function getTimeZone(map, callback){
    let googleApiKey = 'AIzaSyBDIVqRKhG9ABriA2AhOKe238NZu3cul9Y';
    let url = 'https://maps.googleapis.com/maps/api/timezone/json?';
    let timestamp = Math.round(new Date().valueOf() / 1000);
    let lat = map.getCenter().lat;
    let lng = map.getCenter().lng;
    while (lng > 180) lng -= 360;
    while (lng < -180) lng += 360;
    let requestUrl = url + `location=${lat},${lng}&timestamp=${timestamp}&key=${googleApiKey}`;
    return httpGetAsync(requestUrl, callback);

    function httpGetAsync(theUrl, callback){
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (4 === xhr.readyState && 200 === xhr.status){
                let response = JSON.parse(xhr.responseText);
                let tz = convertRawOffset(response.rawOffset);
                callback(tz);
                function convertRawOffset(raw){
                    let converted = 'error';
                    if (raw || raw === 0) {
                        let absRawInHr = Math.abs(raw / 3600);
                        let h = Math.trunc(absRawInHr);
                        let m = Math.round((absRawInHr - h) * 60);
                        converted = (raw < 0 ? '-' : '+') + add0(h) + add0(m);
                    }
                    console.log('timezone: ' + converted);
                    return converted;
                }
            }
        };
        xhr.open("GET", theUrl, true);
        xhr.send(null);
        function add0(n) { return n < 10 ? '0' + n : '' + n; }
    }
}

function getTimeZoneGPS(gps, callback){
    let googleApiKey = 'AIzaSyBDIVqRKhG9ABriA2AhOKe238NZu3cul9Y';
    let url = 'https://maps.googleapis.com/maps/api/timezone/json?';
    let timestamp = Math.round(new Date().valueOf() / 1000);
    if (typeof gps.lng === 'string') gps.lng = parseFloat(gps.lng);
    while (gps.lng > 180) gps.lng -= 360;
    while (gps.lng < -180) gps.lng += 360;
    let requestUrl = url + `location=${gps.lat},${gps.lng}&timestamp=${timestamp}&key=${googleApiKey}`;
    return httpGetAsync(requestUrl, callback);

    function httpGetAsync(theUrl, callback){
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (4 === xhr.readyState && 200 === xhr.status){
                let response = JSON.parse(xhr.responseText);
                let tz = convertRawOffset(response.rawOffset);
                callback(tz);
                function convertRawOffset(raw){
                    let converted = 'error';
                    if (raw || raw === 0) {
                        let absRawInHr = Math.abs(raw / 3600);
                        let h = Math.trunc(absRawInHr);
                        let m = Math.round((absRawInHr - h) * 60);
                        converted = (raw < 0 ? '-' : '+') + add0(h) + add0(m);
                    }
                    console.log('timezone: ' + converted);
                    return converted;
                }
            }
        };
        xhr.open("GET", theUrl, true);
        xhr.send(null);
        function add0(n) { return n < 10 ? '0' + n : '' + n; }
    }
}

function getGpsByAddress(address, callback){
    let googleApiKey = 'AIzaSyBDIVqRKhG9ABriA2AhOKe238NZu3cul9Y';
    let url = 'https://maps.googleapis.com/maps/api/geocode/json?';
    let requestUrl = url + `address=${encodeURIComponent(address)}&key=${googleApiKey}`;
    return httpGetAsync(requestUrl, callback);

    function httpGetAsync(theUrl, callback){
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (4 === xhr.readyState && 200 === xhr.status){
                let response = JSON.parse(xhr.responseText);
                callback(response);
            }
        };
        xhr.open("GET", theUrl, true);
        xhr.send(null);
    }
}

