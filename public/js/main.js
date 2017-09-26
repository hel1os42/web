$( document ).ready(function() {
    $( function() {

        // Date, time Pickers
        var date = $("#start_date, #finish_date");
        var time = $("#start_time, #finish_time");


        date.datepicker({
            dateFormat: "yy-mm-dd"
        }).val();

        date.on("change", function() {
            var selected = $(this).val();
            var momentDate = moment(selected).format('YYYY-MM-DD hh:mm:ss.000000ZZ');
            console.log(momentDate);
        });


        time.timepicker({
            'showDuration': true,
            'timeFormat': 'H:i:s'
        }).val();

        time.on("change", function() {
            var sel = $(this).val();
            var momentTime = moment(sel, '+-HH:mm').format('hh:mm:00.000000ZZ');
            console.log(momentTime);
        });


        // AJAX
        $('.offer-form form').on('submit', function (e) {
            e.preventDefault();

            var params = $(e.target).serialize();
            var data = JSON.parse('{"' + decodeURI(params).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + '"}');

            data.start_date = moment(data.start_date).format('YYYY-MM-DD hh:mm:ss.000000ZZ');
            data.finish_date = moment(data.finish_date).format('YYYY-MM-DD hh:mm:ss.000000ZZ');

            data.start_time = moment(data.start_time, '+-HH:mm').format('hh:mm:00.000000ZZ');
            data.finish_time = moment(data.finish_time, '+-HH:mm').format('hh:mm:00.000000ZZ');

            debugger;

            $.ajax({
                url: '/advert/offers',
                type: 'POST',
                data: data,
                success: function (res) {
                    debugger;
                },
                error: function (xhr, status, error) {

                }
            });
        });


        // Map Picker
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
