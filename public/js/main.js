$( document ).ready(function() {
    $( function() {

        // Date, time Pickers
        $( "#start_date" ).datepicker();

        $('#start_time, #finish_time').timepicker({
            'timeFormat': 'h:mm p'
        });

        $('#finish_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });



        // Map location picker
        function updateControls(addressComponents) {
            $('#city').val(addressComponents.city);
            $('#country').val(addressComponents.country);
        }

        $('#map').locationpicker({
            location: { latitude: 38.9071923, longitude: -77.03687070000001 },
            radius: 500,
            zoom: 13,

            inputBinding: {
                latitudeInput: $('#latitude'),
                longitudeInput: $('#longitude'),
                radiusInput: $('#radius'),
                locationNameInput: $('#address')
            },
            enableAutocomplete: true,

            onchanged: function (currentLocation, radius, isMarkerDropped) {
                var addressComponents = $(this).locationpicker('map').location.addressComponents;
                updateControls(addressComponents);
            },
            oninitialized: function(component) {
                var addressComponents = $(component).locationpicker('map').location.addressComponents;
                updateControls(addressComponents);
            }
        });

    });
});
