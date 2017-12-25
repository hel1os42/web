(function() {
    // map = L.map('mapid', {doubleClickZoom: false}).locate({setView: true, maxZoom: 16});


    // var latit = 0;
    // var longit = 0;
    // var map = L.map('mapid', {
    //     doubleClickZoom: false
    // }).setView([latit, longit], 13);
    //
    // if (navigator.geolocation) {
    //     navigator.geolocation.getCurrentPosition(function(position) {
    //         latit = position.coords.latitude;
    //         longit = position.coords.longitude;
    //         // this is just a marker placed in that position
    //         var abc = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
    //         // move the map to have the location in its center
    //         map.panTo(new L.LatLng(latit, longit));
    //     })
    // }


    var mymap = L.map('mapid').setView([51.505, -0.09], 13);
    console.log(mymap.locate());
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                 '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                 'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(mymap);
}());
