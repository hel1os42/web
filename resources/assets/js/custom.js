$(document).ready(function() {
    $('.clickable-table-row').click(function(e) {
        if (e.target.nodeName !== 'INPUT') {
            window.location = $(this).data("uuid");
        }
    });
});
