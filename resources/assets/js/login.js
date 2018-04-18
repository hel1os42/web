$().ready(function () {
    $("form").validate({
        errorClass: 'text-danger',
        errorElement: 'p',
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 6,
            },

            // for operator login
            alias: {
                required: true,
                minlength: 3,
            },
            login: {
                required: true,
                minlength: 3,
            },
            pin: {
                required: true,
                minlength: 3,
            }
        }
    });
});