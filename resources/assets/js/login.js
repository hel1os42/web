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
            }
        }
    });
});