// send nau part

$( document ).ready( function() {
    let modal = $( '#sendNauModal' );
    let modalBody = $( '.transaction.modal-body' );
    let resultBody = $( '.transaction-result.modal-body' );

    $( ".transaction-open-dialog" ).on( 'click', function() {
        $( "#sendTransaction" ).prop('disabled', false);
        modalBody.show();
        resultBody.hide();
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

function uuid2id(uuid) {
    return 'id_' + uuid.replace(/-/g, '');
}

function waitPopup(withRqCounter){
    let waitPopup = document.querySelector('#waitPopupOverlay');
    if (waitPopup) waitPopup.parentNode.removeChild(waitPopup);
    let rqCounter = withRqCounter ? '<p>Requests: <span id="waitRequests">0</span></p>' : '';
    let html = `<div class="waitPopup"><h3 class="text-center">Please wait...</h3><p class="text-center img">
        <img src="/img/loading.gif"></p>${rqCounter}<p id="waitError"></p></div>`;
    waitPopup = document.createElement('div');
    waitPopup.setAttribute('id', 'waitPopupOverlay');
    waitPopup.innerHTML = html;
    document.body.appendChild(waitPopup);
}

function pagenavyCompact(pagenavy){
    console.log('pagenavyCompact', new Date());
    if (!pagenavy) return false;
    let buttons = pagenavy.children;
    let currentIndex = 0, cntBefore = 0, cntAfter = 0;
    let searchOptions = location.search.substr(1).split('&');
    searchOptions = searchOptions.map(function(e){ return e.split('='); });
    searchOptions = searchOptions.map(function(e){ return e[0] !== 'page' ? e.join('=') : null; });
    for (let i = 0; i < searchOptions.length;){
        if (searchOptions[i] === null) searchOptions.splice(i, 1);
        else i++;
    }
    searchOptions = '&' + searchOptions.join('&');
    for (let i = 0; i < buttons.length; i++) {
        buttons[i].setAttribute('href', buttons[i].getAttribute('href') + searchOptions);
    }
    for (let i = 0; i < buttons.length; i++) {
        if (buttons[i].classList.contains('current')) { currentIndex = i; break; }
    }
    for (let i = 3; i < currentIndex - 2; i++) {
        buttons[i].style.display = 'none';
        cntBefore++;
    }
    for (let i = currentIndex + 3; i < buttons.length - 3; i++) {
        buttons[i].style.display = 'none';
        cntAfter++;
    }
    if (cntBefore) pagenavy.insertBefore(dots(), buttons[3]);
    if (cntAfter) pagenavy.insertBefore(dots(), buttons[currentIndex + 4]);
    function dots(){
        let span = document.createElement('span');
        span.classList.add('dots');
        span.innerText = '...';
        return span;
    }
}

function setFieldLimit(selector){
    document.querySelectorAll(selector).forEach(function(input){
        createSpan(input);
        ['keyup', 'paste', 'change'].forEach(function(e){
            input.addEventListener(e, trimValue);
        });
    });
    function trimValue(){
        let val = getValue(this);
        let len = parseInt(this.dataset.maxLength);
        if (val.length > len) this.value = val.substr(0, len);
        this.parentElement.querySelector('.character-counter').innerText = val.length + ' / ' + len;
    }
    function createSpan(input){
        let span = document.createElement('span');
        span.classList.add('character-counter');
        span.innerText = getValue(input).length + ' / ' + input.dataset.maxLength;
        input.parentElement.appendChild(span);
    }
    function getValue(e){
        return e.value.replace(/\{.+?\}/g, '');
    }
}

