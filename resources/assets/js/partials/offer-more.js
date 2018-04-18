function offerMoreInit(id, text){
    let box = document.getElementById(id);
    if (!box) return false;
    const MIN_LENGTH_OF_TAG = 3;
    if (!text) text = {};
    textDefault(text);

    /* creating box */
    let html = '<p class="tag-buttons"><em>' + text.hashButtons + ':</em><br><span class="buttons"></span></p>';
    html += '<p><strong>' + text.title + '</strong></p>';
    html += '<p class="label-items"><span class="tag-label">' + text.tagPlaceholder + ':</span> ';
    html += text.titlePlaceholder + '<span class="buttons-label">' + text.buttonsPlaceholder + '</span></p>';
    html += '<div id="more_items"></div>';
    html += '<p class="input-example pull-left">' + text.tagMinLength + ': ' + MIN_LENGTH_OF_TAG + '<br>';
    html += text.tagExample + ':<br>&nbsp;&nbsp;&nbsp;&nbsp;<em>#promo</em>';
    html += '<br>&nbsp;&nbsp;&nbsp;&nbsp;<em>#hot_offer</em><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>#blackFriday</em></p>';
    html += '<p class="text-right"><span class="btn btn-xs btn-nau btn-add-item">' + text.addButton + '</span></p>';
    box.innerHTML = html;
    let moreItems = box.querySelector('#more_items');
    box.querySelector('.btn-add-item').addEventListener('click', function(){ addItem(); });
    box.addEventListener('om.changeLink', function(){ tagButtons(); });
    box.addEventListener('om.removeLink', function(){ tagButtons(); });
    getPlaceLinks();

    function textDefault(text){
        let def = {
            hashButtons: 'You can use next tags for create links to additional information',
            title: 'More information',
            addButton: 'Add item',
            tagPlaceholder: 'Tag',
            titlePlaceholder: 'Text for link',
            buttonsPlaceholder: 'save / edit',
            tagMinLength: 'Min. length of tag',
            tagExample: 'Examples for tags',
            btnSave: 'Save',
            btnSaveTitle: 'Save additional information',
            btnEdit: 'Edit',
            btnEditTitle: 'Edit additional information',
            btnRemove: 'Remove',
            btnRemoveTitle: 'Remove additional information',
            btnClose: 'Close',
            btnCloseTitle: 'Close editor',
            confirmRemove: 'Are you sure to remove this item?',
            descriptionSize: 'Size',
            removeConfirm: 'Are you sure to remove this link?'
        };
        for (let key in def) if (!text[key]) text[key] = def[key];
    }

    function addItem(json){
        let newItem = document.createElement('div');
        newItem.setAttribute('class', 'more-item form-group');
        if (json) {
            newItem.classList.add('can-edit');
            newItem.dataset.id = json.id;
            newItem.dataset.tag = json.tag;
            newItem.dataset.title = json.title;
        } else json = {};

        let tagValue = ' value="' + (json.tag ? json.tag : '') + '"';
        let titleValue = ' value="' + (json.title ? json.title : '') + '"';
        let descrValue = ' value="' + (json.description ? encodeURIComponent(json.description) : '') + '"';
        let descrSize = json.description ? json.description.length : 0;

        let html = '<label class="tag control-text"><input type="text"' + tagValue + '></label>';
        html += '<label class="title control-text"><input type="text"' + titleValue + '></label>';
        html += '<div class="more-description clearfix"></div>';
        html += '<input class="content" type="hidden"' + descrValue + '>';
        html += '<span class="btn btn-xs btn-save-item btn-danger" title="' + text.btnSaveTitle + '">' + text.btnSave + '</span>';
        html += '<span class="btn btn-xs btn-edit-item" title="' + text.btnEditTitle + '">' + text.btnEdit + '</span>';
        html += '<span class="content-length">' + text.descriptionSize + ': ' + descrSize + '</span>';
        newItem.innerHTML = html;

        let tagInput = newItem.querySelector('.tag input');
        let titleInput = newItem.querySelector('.title input');
        tagInput.addEventListener('input', function(){
            onTagChange(this);
            for (let i = 1; i < moreItems.children.length; i++) {
                let currentInput = moreItems.children[i].querySelector('.tag input');
                let double = false;
                for (let j = 0; j < i; j++) if (currentInput.value === moreItems.children[j].querySelector('.tag input').value) double = true;
                moreItems.children[i].classList[double ? 'add' : 'remove']('tag-error');
            }
        });
        tagInput.addEventListener('input', checkEditInputs);
        titleInput.addEventListener('input', checkEditInputs);
        newItem.querySelector('.btn-save-item').addEventListener('click', function(){ saveItem(this.parentElement); });
        newItem.querySelector('.btn-edit-item').addEventListener('click', function(){ editItem(this.parentElement); });
        moreItems.appendChild(newItem);
        if (!newItem.dataset.id) createEditorBox(newItem);
        $(newItem).slideDown(); /* jQuery */
        $(box).find('.label-items').add('.input-example').slideDown(); /* jquery */

        function onTagChange(input){
            let val = input.value.trim().replace(/\s/g, '_').replace(/[^A-Za-z0-9_]/g, '');
            while (val.length > 0 && !isNaN(+val[0])) val = val.substr(1);
            input.value = val;
        }
        function checkEditInputs(){
            if (tagInput.value !== newItem.dataset.tag || titleInput.value !== newItem.dataset.title) {
                newItem.classList.remove('can-edit');
                newItem.classList.add('not-saved');
            } else {
                newItem.classList.add('can-edit');
                newItem.classList.remove('not-saved');
            }
        }

    }

    function saveItem(item){
        let tagInput = item.querySelector('.tag input');
        let titleInput = item.querySelector('.title input');
        let descrInput = item.querySelector('input.content');
        let editorInput = item.querySelector('.note-editable');

        if (item.classList.contains('tag-error')) { tagInput.focus(); return false; }
        if (tagInput.value.length < MIN_LENGTH_OF_TAG) { tagInput.focus(); return false; }
        if (titleInput.value.length < 1) { titleInput.focus(); return false; }
        if (editorInput) {
            editorInput.innerHTML = editorInput.innerHTML.trim();
            descrInput.value = encodeURIComponent(editorInput.innerHTML);
            if (editorInput.innerText.length < 2) {
                item.querySelector('.note-editor.note-frame').classList.add('value-empty');
                editorInput.focus();
                return false;
            }
        }

        item.classList.remove('not-saved');
        item.classList.remove('can-edit');
        item.classList.add('wait');
        let url = box.dataset.url;
        let token = box.dataset.token;
        let formData = {
            _token: token,
            tag: tagInput.value,
            title: titleInput.value,
            description: decodeURIComponent(descrInput.value)
        };
        if (item.dataset.id) {
            url += '/' + item.dataset.id;
            formData._method = 'PUT';
        }
        console.dir(formData);

        let xhr = new XMLHttpRequest();
        xhr.responseType = 'json';
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                item.classList.remove('wait');
                if (xhr.status === 401) UnAuthorized();
                else if (xhr.status === 0) AdBlockNotification();
                else if (xhr.status === 200) {
                    if (!editorInput) item.classList.add('can-edit');
                    if (editorInput) item.querySelector('.btn-close-item').style.display = '';
                    item.dataset.id = xhr.response.id;
                    box.dispatchEvent(new Event('om.changeLink'));
                } else {
                    alert('Something wrong.');
                    item.classList.add('not-saved');
                }
                console.log('Response:');
                console.dir(xhr);
            }
        };
        xhr.open('POST', url, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', token);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.send(JSON.stringify(formData));
    }


    function editItem(item){
        createEditorBox(item);
    }

    function removeItem(item){
        if (!confirm(text.removeConfirm)) return false;
        if (!item.dataset.id) {
            $(item).slideUp(function(){ item.parentElement.removeChild(item); });
        } else {
            let notSaved = item.classList.contains('not-saved');
            item.classList.remove('not-saved');
            item.classList.remove('can-edit');
            item.classList.add('wait');
            let url = box.dataset.url + '/' + item.dataset.id;
            let token = box.dataset.token;
            let formData = {
                _token: token,
                _method: 'DELETE'
            };
            let xhr = new XMLHttpRequest();
            xhr.responseType = 'json';
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    item.classList.remove('wait');
                    if (xhr.status === 401) UnAuthorized();
                    else if (xhr.status === 0) AdBlockNotification();
                    else if (xhr.status === 204) {
                        $(item).slideUp(function(){
                            item.parentElement.removeChild(item);
                            box.dispatchEvent(new Event('om.removeLink'));
                        });
                    } else if (xhr.status === 400) {
                        alert(xhr.response.message);
                        if (notSaved) item.classList.add('not-saved');
                    } else {
                        alert('Something wrong.');
                        if (notSaved) item.classList.add('not-saved');
                    }
                    console.log('Response:');
                    console.dir(xhr);
                }
            };
            xhr.open('POST', url, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', token);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('Content-type', 'application/json');
            xhr.send(JSON.stringify(formData));
        }
    }

    function tagButtons(){
        let html = '';
        for (let i = 0; i < moreItems.children.length; i++) {
            let tagValue = moreItems.children[i].querySelector('.tag input').value;
            if (tagValue) html += '<span class="btn btn-xs">#' + tagValue + '</span>';
        }
        let buttonsWrap = box.querySelector('.tag-buttons');
        let buttonsBox = buttonsWrap.querySelector('.buttons');
        buttonsBox.innerHTML = html;
        $(buttonsWrap)['slide' + (html ? 'Down' : 'Up')]();
        let buttons = buttonsBox.querySelectorAll('.btn');
        buttons.forEach(function(tagButton){
            tagButton.addEventListener('click', function(){
                let textArea = document.getElementsByName('description')[0];
                let val = textArea.value;
                let selStart = textArea.selectionStart;
                let spaceBefore = (selStart === 0 || val[selStart - 1] === ' ') ? '' : ' ';
                let spaceAfter = (selStart === val.length || val[selStart] === ' ') ? '' : ' ';
                val = val.substring(0, selStart) + spaceBefore + this.innerText + spaceAfter + val.substring(selStart);
                textArea.value = val;
                textArea.focus();
                selStart += this.innerText.length + spaceBefore.length + 1;
                textArea.setSelectionRange(selStart, selStart);
                textArea.dispatchEvent(new Event('keyup'));
            });
        });
    }

    function createEditorBox(item){
        item.classList.remove('can-edit');
        let editorBox = item.querySelector('.more-description');
        editorBox.style.display = 'none';
        let content = item.querySelector('.content').value;
        let html = '<button type="button" class="btn btn-xs btn-remove-item" title="' + text.btnRemoveTitle+ '">' + text.btnRemove + '</button>';
        html += '<button type="button" class="btn btn-xs btn-close-item" title="' + text.btnCloseTitle + '">' + text.btnClose + '</button>';
        html += '<div class="summernote">' + decodeURIComponent(content) + '</div>';
        editorBox.innerHTML = html;
        $(editorBox).find('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol']],
                ['link', ['link']],
                ['edit', ['undo', 'redo']]
            ]
        }); /* jQuery */
        let btnClose = item.querySelector('.btn-close-item');
        let noteEditable = item.querySelector('.note-editable');
        noteEditable.addEventListener('input', editorChanged);
        noteEditable.addEventListener('keyup', editorChanged);
        btnClose.addEventListener('click', function(){
            $(editorBox).slideUp(function(){
                item.classList.add('can-edit');
                editorBox.innerHTML = '';
            });
        });
        item.querySelector('.btn-remove-item').addEventListener('click', function(){ removeItem(item); });
        $(editorBox).slideDown();

        function editorChanged(){
            btnClose.style.display = 'none';
            item.classList.add('not-saved');
            item.querySelector('.note-editor.note-frame').classList.remove('value-empty');
            item.querySelector('.content-length').innerText = text.descriptionSize + ': ' + this.innerText.length;
        }
    }

    function getPlaceLinks(){
        let token = box.dataset.token;
        let xhr = new XMLHttpRequest();
        xhr.responseType = 'json';
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 401) UnAuthorized();
                else if (xhr.status === 0) AdBlockNotification();
                else if (xhr.status === 200) {
                    console.dir(xhr.response.data);
                    xhr.response.data.forEach(function(json){ addItem(json); });
                    box.dispatchEvent(new Event('om.changeLink'));
                }
            }
        };
        xhr.open('GET', box.dataset.url, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', token);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.send(JSON.stringify({ _token: token }));
    }
}
