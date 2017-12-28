// $(document).ready(function() {
//     let GPS = {};
//     if (navigator.geolocation) navigator.geolocation.getCurrentPosition(getGPS, defaultGPS);
//     else defaultGPS();
//
//     function getGPS(pos){
//         GPS = { lat: pos.coords.latitude, lng: pos.coords.longitude };
//         mapInitialize(GPS);
//     }
//
//     function defaultGPS(){
//         /* Los Angeles: */
//         GPS = { lat: 34.0143733, lng: -118.2831973 };
//         mapInitialize(GPS);
//     }
//
//     function mapInitialize(GPS){
//         function getAddress(GPS) {
//             return location.protocol + `//nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${GPS.lat}&lon=${GPS.lng}`;
//         }
//
//         let map = L.map('mapid', { center: GPS, zoom: 13 });
//
//         L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//             maxZoom: 19,
//             minZoom: 1,
//             maxNativeZoom: 18,
//             attribution: '© OpenStreetMap',
//             // tileSize: 512,
//             // zoomOffset: -1,
//             detectRetina: true,
//         }).addTo(map);
//     }
// });
