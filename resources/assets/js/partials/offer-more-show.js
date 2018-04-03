function offerMoreShow(){
    /* get place links */
    let table = document.getElementById('tab_your_offers');
    let token = table.dataset.token;
    let url = table.dataset.linksUrl;
    let xhr = new XMLHttpRequest();
    xhr.responseType = 'json';
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 401) UnAuthorized();
            else if (xhr.status === 0) AdBlockNotification();
            else if (xhr.status === 200) {
                console.dir(xhr.response.data);
                parseDescriptions(xhr.response.data);
            }
        }
    };
    xhr.open('GET', url, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', token);
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.send(JSON.stringify({ _token: token }));

    function parseDescriptions(json){
        json.sort(function(a, b){ return a.tag.length < b.tag.length ? 1 : -1; });
        document.querySelectorAll('.offer-description').forEach(function(description, i){
            let val = description.innerText;
            json.forEach(function(link){
                let re = new RegExp('#' + link.tag, 'g');
                val = val.replace(re, '<a href="#' + link.tag + '">' + link.title + '</a>');
            });
            description.innerHTML = val;
        });
        table.addEventListener('click', function(e){
            if (e.target.tagName.toLowerCase() === 'a') {
                let tag = e.target.getAttribute('href').substr(1);
                let link = json.find(function(item){ return item.tag === tag; });
                if (link) {
                    e.preventDefault();
                    showLinkModal(link.title, link.description);
                }
            }
        });
    }

    function showLinkModal(title, value){
        let div = document.getElementById('offerLinkModal');
        if (div) div.parentElement.removeChild(div);
        div = document.createElement('div');
        div.setAttribute('id', 'offerLinkModal');
        let html = '<div class="offer-link-modal-overlay"></div><div class="offer-link-modal-content">';
        html += '<span class="close-offer-link-modal">&times;</span><h2>' + title + '</h2>';
        html += '<div class="offer-link-content">' + value + '</div></div>';
        div.innerHTML = html;
        div.querySelectorAll('.offer-link-content a').forEach(function(a){ a.setAttribute('target', '_blank'); });
        div.querySelector('.offer-link-modal-overlay').addEventListener('click', function(){ closeModal(div); });
        div.querySelector('.close-offer-link-modal').addEventListener('click', function(){ closeModal(div); });
        document.body.appendChild(div);
        function closeModal(div){ div.parentElement.removeChild(div); }
    }
}
