function offerMoreInit(id, text, json){
    if (!text) text = {};
    textDefault(text);

    let box = document.getElementById(id);
    let html = '<p class="tag-buttons"><em>' + text.hashButtons + ':</em><br><span class="buttons"></span></p>';
    html += '<p><strong>' + text.title + '</strong></p>';
    html += '<p class="label-items"><span class="tag-label">' + text.tagPlaceholder + ':</span> ';
    html += text.titlePlaceholder + '<span class="buttons-label">' + text.buttonsPlaceholder + '</span></p>';
    html += '<div id="more_items"></div>';
    html += '<p class="input-example pull-left">' + text.tagExample + ':<br>&nbsp;&nbsp;&nbsp;&nbsp;<em>#promo</em>';
    html += '<br>&nbsp;&nbsp;&nbsp;&nbsp;<em>#hot_offer</em><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>#blackFriday</em></p>';
    html += '<p class="text-right"><span class="btn btn-xs btn-nau btn-add-item">' + text.addButton + '</span></p>';
    box.innerHTML = html;
    let moreItems = box.querySelector('#more_items');
    let addButton = box.querySelector('.btn-add-item');
    addButton.addEventListener('click', function(){ addItem(); });

    box.addEventListener('changeMoreTag', function(){ tagButtons(); });
    box.addEventListener('removeMoreItem', function(){ tagButtons(); });

    function textDefault(text){
        let def = {
            hashButtons: 'You can use next tags for create links to additional information',
            title: 'More information',
            addButton: 'Add item',
            tagPlaceholder: 'Tag',
            titlePlaceholder: 'Text for link',
            buttonsPlaceholder: 'edit / remove',
            tagExample: 'Examples for tags',
            btnEditTitle: 'Edit additional information',
            btnRemoveTitle: 'Remove additional information',
            confirmRemove: 'Are you sure to remove this item?',
            contentSize: 'Size'
        };
        for (let key in def) if (!text[key]) text[key] = def[key];
    }

    function addItem(json){
        let newItem = document.createElement('div');
        newItem.setAttribute('class', 'more-item form-group');

        let tagValue = ' value="' + (json && json.tag ? json.tag : '') + '"';
        let titleValue = ' value="' + (json && json.title ? json.title : '') + '"';
        let contentValue = ' value="' + (json && json.content ? json.content : '') + '"';
        let iconEdit = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>';
        let iconRemove = '<i class="fa fa-times-circle" aria-hidden="true"></i>';

        let html = '<label class="tag control-text"><input type="text"' + tagValue + '></span></label>';
        html += '<label class="title control-text"><input type="text"' + titleValue + '></label>';
        html += '<input class="content" type="hidden"' + contentValue + '>';
        html += '<span class="btn btn-xs btn-edit-item" title="' + text.btnEditTitle + '">' + iconEdit + '</span>';
        html += '<span class="btn btn-xs btn-remove-item" title="' + text.btnRemoveTitle + '">' + iconRemove + '</span>';
        html += '<span class="content-length">' + text.contentSize + ': 0</span>';
        newItem.innerHTML = html;

        newItem.querySelector('.tag input').addEventListener('change', function(){
            let val = this.value.trim().replace(/\s/g, '_').replace(/[^A-Za-z0-9_]/g, '');
            while (val.length > 0 && !isNaN(+val[0])) val = val.substr(1);
            this.value = val;
            for (let i = 1; i < moreItems.children.length; i++) {
                let currentInput = moreItems.children[i].querySelector('.tag input');
                let double = false;
                for (let j = 0; j < i; j++) if (currentInput.value === moreItems.children[j].querySelector('.tag input').value) double = true;
                currentInput.style.color = double ? 'red' : '';
                currentInput.style.fontWeight = double ? 'bold' : '';
            }
            box.dispatchEvent(new Event('changeMoreTag'));
        });
        newItem.querySelector('.btn-edit-item').addEventListener('click', function(){ editItem(this.parentElement); });
        newItem.querySelector('.btn-remove-item').addEventListener('click', function(){ removeItem(this.parentElement); });
        moreItems.appendChild(newItem);
        $(newItem).slideDown(); /* jQuery */
        //box.classList.add('has-items');
        $(box).find('.label-items').add('.input-example').slideDown(); /* jquery */
        box.dispatchEvent(new Event('newMoreItem'));
    }

    function removeItem(item){
        if (confirm(text.confirmRemove)) {
            $(item).slideUp(function(){
                item.parentElement.removeChild(item);
                if (moreItems.children.length === 0) {
                    $(box).find('.label-items').add('.input-example').slideUp(function(){
                        //box.classList.remove('has-items');
                    });
                }
                box.dispatchEvent(new Event('removeMoreItem'));
            });
        }
    }

    function editItem(item){
        createEditorModal(item);
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
        //buttonsWrap.style.display = html ? 'block' : '';
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
    function createEditorModal(item){
        let editorModal = document.getElementById('editorMoreModal');
        if (editorModal) editorModal.parentElement.removeChild(editorModal);
        editorModal = document.createElement('div');
        editorModal.setAttribute('id', 'editorMoreModal');
        editorModal.setAttribute('class', 'nobs-modal');
        editorModal.setAttribute('role', 'dialog');
        let tagName = item.querySelector('.tag input').value;
        let title = item.querySelector('.title input').value;
        let content = item.querySelector('.content').value;
        let html = '<div class="nobs-modal-content">';
        html += '<span class="close-modal">&times;</span>';
        html += '<h4>#' + (tagName ? tagName : '&lt;not assigned&gt;') + ', &nbsp;&nbsp; ' + (title ? title : '&lt;not assigned&gt;');
        html += '</h4><div class="summernote">' + content + '</div>';
        html += '<p class="text-right"><button type="button" class="btn btn-nau btn-save-more">Save changes</button></p></div>';
        editorModal.innerHTML = html;
        document.body.appendChild(editorModal);
        editorModal.classList.add('shown');
        $(editorModal).find('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol']],
                ['link', ['link']],
                ['edit', ['undo', 'redo']]
            ]
        }); /* jQuery */

        editorModal.addEventListener('click', function(e){
            if (e.target.getAttribute('id') === 'editorMoreModal') destroyEditorModal();
        });
        editorModal.querySelector('.btn-save-more').addEventListener('click', function(){
            let content = editorModal.querySelector('.note-editable').innerHTML;
            item.querySelector('.content').value = content;
            item.querySelector('.content-length').innerText = text.contentSize + ': ' + content.length;
            destroyEditorModal();
        });
        editorModal.querySelector('.close-modal').addEventListener('click', destroyEditorModal);

        function destroyEditorModal(){
            $(editorModal).find('.summernote').summernote('destroy'); /* jQuery */
            editorModal.parentElement.removeChild(editorModal);
        }
    }
}