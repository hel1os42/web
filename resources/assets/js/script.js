// send nau part

$( document ).ready( function() {
    let modal = $( '#sendNauModal' );
    let modalBody = $( '.transaction.modal-body' );
    let resultBody = $( '.transaction-result.modal-body' );

    $( ".transaction-open-dialog" ).on( 'click', function() {
        $( "#sendTransaction" ).prop('disabled', false);
        modalBody.show();
        resultBody.hide();
        modalBody.find( '#source' ).val( $( this ).data( 'source' ) );
        modalBody.find( '#destination' ).val( $( this ).data( 'destination' ) );
    } );

    $( "#sendTransaction" ).on( 'click', function() {
        $( this ).prop('disabled', true);
        sendNau( modalBody.data( 'url' ), modalBody.find( 'input[name=_token]' ).val(),
            modalBody.find( '#source' ).val(), modalBody.find( '#destination' ).val(),
            modalBody.find( '#amount' ).val(), modalBody.find( '#noFee' ).val() )
    } );

    function sendNau( url, token, source, destination, amount, noFeeFlag = 0 ) {
        let noFee = '';
        if ( noFeeFlag !== 0 ) {
            noFee = '&no_fee=1';
        }
        $.ajax( {
            type:       "POST",
            url:        url,
            data:       "_token=" + token + "&source=" + source + "&destination=" + destination + "&amount=" + amount + noFee,
            beforeSend: function() {
                modalBody.hide();
                resultBody.show();
                resultBody.text( 'Sending request...' )
            },
            success:    function() {
                modalBody.hide();
                resultBody.show();
                resultBody.text( 'The transaction is accepted, it will be conducted in the next couple of minutes.' );
                setTimeout( function() {
                    modal.modal( 'hide' );
                }, 6000 );
            },
            error:      function( data ) {
                modalBody.hide();
                resultBody.show();
                console.log( data );
                resultBody.text( 'There were problems creating the transaction. Please try again later.' );
            }
        } );
    }
} );

// --send nau part


/* 
url - string of request-URL
method - string 'GET', 'POST', 'PATCH', etc.
respType - null or string 'ajax'
callback(response) - callback-function
*/

function srvRequest(url, method, respType, callback){
    let xhr = new XMLHttpRequest();
    if (respType) xhr.responseType = respType;
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) { callback(xhr.response); }
            else if (xhr.status === 400) { console.log('Error 400'); }
            else { console.log('Something else other than 200 was returned'); }
        }
    };
    xhr.open(method, url, true);
    if (respType) xhr.setRequestHeader('Accept', 'application/' + respType);
    xhr.send();
}





