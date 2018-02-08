<script type="text/javascript">
    function loadRoles() {
        let xmlhttp = new XMLHttpRequest();
        let currentRoles = {!! json_encode(array_column($roles, 'id')) !!};

        xmlhttp.onreadystatechange = function() {
            if ( xmlhttp.readyState === XMLHttpRequest.DONE ) {
                if ( xmlhttp.status === 200 ) {
                    let sel = document.getElementById( "roles" );
                    sel.innerHTML = xmlhttp.responseText;
                    for ( let rolesIndex = 0; rolesIndex < sel.options.length; rolesIndex++ ) {
                        let option = sel.options[rolesIndex];
                        if ( currentRoles.indexOf( option.value ) != -1 ) {
                            option.selected = true;
                            console.log( option.value );
                        }
                    }
                } else if ( xmlhttp.status === 400 ) {
                    alert( 'There was an error 400' );
                } else {
                    alert( xmlhttp.status + ' was returned' );
                }
            }
        };

        xmlhttp.open( "GET", "{{route('roles')}}", true );
        xmlhttp.send();
    }

    loadRoles();
</script>