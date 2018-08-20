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
                if (401 === resp.status) UnAuthorized();
                else if (0 === resp.status) AdBlockNotification();
                else {
                    modalBody.hide();
                    resultBody.show();
                    console.log(data);
                    resultBody.text('There were problems creating the transaction. Please try again later.');
                }
            }
        } );
    }
} );



function add0(n) { return n < 10 ? '0' + n : n.toString(); }

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
            if (xhr.status === 401) UnAuthorized();
            else if (xhr.status === 0) AdBlockNotification();
            else if (xhr.status === 200) callback(xhr.response);
            else if (xhr.status === 400) console.log('Error 400 - bad request');
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
    let html = '<div class="waitPopup"><h3 class="text-center">Please wait...</h3><p class="text-center img">';
    html += '<img src="/img/loading.gif"></p>' + rqCounter + '<p id="waitError"></p></div>';
    waitPopup = document.createElement('div');
    waitPopup.setAttribute('id', 'waitPopupOverlay');
    waitPopup.innerHTML = html;
    document.body.appendChild(waitPopup);
}

function UnAuthorized(s){
    if (!s) s = 'You are not authorized.';
    alert(s);
    location.reload();
}

function AdBlockNotification(s){
    if (!s) s = 'Please disable Adblock to work with NAU cabinet.';
    alert(s);
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


// opt   - default nau options with data from ajax response
// block - DOM element which is main wrapper of the pagination
function pagenavyCompactAjax( opt, block ) {
    if ( opt.last_page < 2 ) {
        block.innerHTML = '';
        return;
    }

    let inner_body = '',
        dots_item  = '<span class="dots">...</span>',
        cntBefore  = false,
        cntAfter   = false;

    if (opt.prev_page_url) inner_body += '<a href="' + opt.path + '?page=' + (opt.current_page - 1 ) + '" class="prev"></a>';

    for (i = 1; i <= opt.last_page; i++) {
        if (i > 2 && i < opt.current_page - 2) {
            if (!cntBefore) {
                inner_body += dots_item;
                cntBefore = true;
            }
            continue;
        }
        if (i > opt.current_page + 2 && i < opt.last_page - 1) {
            if (!cntAfter) {
                inner_body += dots_item;
                cntAfter = true;
            }
            continue;
        }
        if ( opt.current_page === i ) {
            inner_body += '<span class="current">' + i + '</span>';
            continue;
        }

        inner_body += '<a href="' + opt.path + '?page=' + i + '">' + i + '</a>';
    }

    if (opt.next_page_url) inner_body += '<a href="' + opt.path + '?page=' + (opt.current_page + 1 ) + '" class="next"></a>';

    block.innerHTML = inner_body;
}

function setFieldLimit(selector){
    document.querySelectorAll(selector).forEach(function(input){
        createSpan(input);
        ['keyup', 'paste', 'change'].forEach(function(e){
            input.addEventListener(e, trimValue);
        });
    });
    function trimValue(){
        let val = this.value;
        let len = parseInt(this.dataset.maxLength);
        if (val.length > len) this.value = val.substr(0, len);
        this.parentElement.querySelector('.character-counter').innerText = this.value.length + ' / ' + len;
    }
    function createSpan(input){
        let span = document.createElement('span');
        span.classList.add('character-counter');
        span.innerText = input.value.length + ' / ' + input.dataset.maxLength;
        input.parentElement.appendChild(span);
    }
}

function base64toBlob(base64Data, contentType) {
    contentType = contentType || '';
    let sliceSize = 1024;
    let byteCharacters = atob(base64Data);
    let bytesLength = byteCharacters.length;
    let slicesCount = Math.ceil(bytesLength / sliceSize);
    let byteArrays = new Array(slicesCount);

    for (let sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
        let begin = sliceIndex * sliceSize;
        let end = Math.min(begin + sliceSize, bytesLength);

        let bytes = new Array(end - begin);
        for (let offset = begin, i = 0; offset < end; ++i, ++offset) {
            bytes[i] = byteCharacters[offset].charCodeAt(0);
        }
        byteArrays[sliceIndex] = new Uint8Array(bytes);
    }
    return new Blob(byteArrays, { type: contentType });
}

function convertTimezoneOffsetFromSecToHrsMin(sec){
    let sign = sec < 0 ? '-' : '+';
    sec = Math.abs(sec);
    let h = Math.floor(sec / 3600);
    let m = Math.floor(sec / 60) % 60;
    return sign + add0(h) + add0(m);
}

// action value  : add|remove|update
// type   value  : error|warning|info|success
// element value : HTML DOM Element
function messages(action, type, text, element) {
    if (!element || !element.nodeName) return;
    let message_tmpl = '<p class="%type%">%text%</p>';
    switch (action) {
        case 'remove':
            element.innerHTML = '';
            break;
        case 'update':
            if (text) element.innerHTML = message_tmpl.replace('%text%', text).replace('%type%', type);
            break;
        case 'add':
        default:
            if (text) element.innerHTML += message_tmpl.replace('%text%', text).replace('%type%', type);
            break;
    }
}

// message_block value : null | HTML DOM Element
function ajax_callback(xhr, callback, message_block) {
    let message;

    if (xhr.readyState === XMLHttpRequest.DONE) {
        switch (xhr.status) {
            case 200 : callback( JSON.parse(xhr.response) );
                break;
            case 401 : UnAuthorized();
                break;
            case 500 :
            default  : {
                try {
                    let responseObj = JSON.parse(xhr.response);
                    if (responseObj.error && responseObj.message)
                        message = 'Error: ' + responseObj.message;
                } catch (e) {
                    message = nau_lang.an_error;
                }

                if (message_block) {
                    messages('add', 'error', message, message_block);
                } else {
                    message_block = document.createElement('div');
                    message_block.classList.add('alert');
                    message_block.classList.add('alert-danger');
                    message_block.innerText = message;

                    let parent = document.querySelector('#mainwrapper > main');
                    parent.insertBefore(message_block, parent.children[0]);
                }
            }
        }
    }
}