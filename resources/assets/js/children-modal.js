(function(){

    let Children = {
        add_button:         document.getElementById('add_children'),
        modal_add_button:   document.getElementById('add_selected_children'),
        form:               document.getElementById('search_children'),
        modal_dialog:       document.getElementById('add_children_list'),
        modal_body:         document.querySelector('#add_children_list .modal-body'),
        wrap:               document.getElementsByClassName('children-wrap')[0],
        response:           null,
        border_row_color:   document.querySelector('.table-striped-nau tr td').style.borderBottomColor
    };

    Children.waiting = function( action ) {
        let img = Children.modal_dialog.querySelector('img[src*="loading"]');
        let table = Children.modal_dialog.getElementsByTagName('tbody')[0];
        let border;
        switch ( action ) {
            case 'stop' : {
                img.style.opacity = '0';
                img.style.height = '0';
                table.style.opacity = '1';
                table.style.visibility = 'visible';
                border = Children.border_row_color;
                break;
            }
            case 'start': {
                setTimeout(function(){
                    img.style.opacity = '1';
                    img.style.height = 'auto';
                }, 300);
                table.style.opacity = '0';
                table.style.visibility = 'collapse';
                border = 'transparent';
                break;
            }
        }

        document.querySelectorAll('.table-striped-nau tr:not(:last-child) td').forEach(function(el){
            el.style.borderBottomColor = border;
        });
    };

    Children.get_selected_users = function( children ) {
        let arr = [];
        children.forEach( function( current ) {
            arr.push( current.value );
        });

        return arr;
    };

    Children.setParams = function( url ) {
        Children.request    = [];
        Children.get_params = [];

        let params = [
            'availableForUser=' + document.getElementById('editable_user_id').innerText.trim()
        ];
        let role = document.getElementById('role');
        if ( role ) {
            Children.request['role'] = role.options[role.selectedIndex].value;
            role = 'roles.name:' + role.options[role.selectedIndex].value;
        } else {
            role = 'roles.name:advertiser';
        }

        let searchStr = document.getElementById('search_field').value;
        let searchParams = '';
        if ( searchStr ) {
            Children.request['string'] = searchStr;
            searchParams = 'email:' + searchStr
                + ';name:' + searchStr
                + ';phone:' + searchStr
                + ';place.name:' + searchStr
                + ';';
        }

        params.push('search=' + encodeURIComponent( searchParams + role ));
        params.push('searchJoin=or');

        for (let i=0; i<params.length; i++) {
            let sign = (i === 0 && url.indexOf('?') === -1) ? '?' : '&';
            Children.get_params += sign + params[i];
        }
    };

    Children.render = function( current_children ) {
        let table = Children.modal_dialog.getElementsByTagName('tbody');
        let body = '';
        let data = Children.response.data || [];

        if (data) {
            for (i in data) {
                let child = data[i];

                if (current_children.includes(child['id'])) {
                    continue;
                }

                let email = child['email'] ? child['email'] : '';
                let phone = child['phone'] ? child['phone'] : '';

                let row = [
                    '<input type="checkbox" ' +
                    'class="children-list" ' +
                    'name="child_ids[]" ' +
                    'value="' + child['id'] +
                    '">',
                    child['name'] ? child['name'] : '-',
                    (email && phone) ? email + ', ' + phone : email + phone,
                    (child['place'] && child['place']['name']) ? child['place']['name'] : '-'
                ];

                body += '<tr><td>' + row.join('</td><td>') + '</td></tr>';
            }
        }

        table[0].innerHTML = body;

        Children.remove_pagination();
        Children.modal_body.innerHTML += Children.pagination(Children.response);
        Children.render_search_fields();
    };

    Children.render_search_fields = function() {
        let role = document.getElementById('role');
        if ( role )
            document.querySelectorAll('#role option[selected]').forEach(function(el){
                el.removeAttribute('selected');
            });
        if( Children.request['role'] ) {
            document.querySelector('#role option[value="' + Children.request['role'] + '"]')
                .setAttribute('selected','selected');
        }
        if ( Children.request['string'] ) {
            document.getElementById('search_field').value = Children.request['string'];
        }
    };

    Children.load_callback = function( response, additional_callback ) {
        let current_children = Children.get_selected_users( document.querySelectorAll('.added-children') );

        if ( response ) {
            Children.response = JSON.parse(response);
            Children.render(current_children);
            Children.waiting('stop');
        }

        if (additional_callback) {
            additional_callback();
        }
    };

    Children.load = function( url, additional_callback ) {
        let xhr = new XMLHttpRequest();
        if ( ! url )
            url = Children.form.getAttribute('action');

        Children.setParams( url );

        xhr.open( "GET", url + Children.get_params, true );
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                switch (xhr.status) {
                    case 200 : Children.load_callback( xhr.response, additional_callback );
                        break;
                    case 401 : UnAuthorized();
                        break;
                    case 500 :
                    default  : {
                        try {
                            let responseObj = JSON.parse(xhr.response);
                            if (responseObj.error && responseObj.message) Children.addError('Error: ' + responseObj.message);
                        } catch (e) {
                            Children.addError('Something went wrong. Please try again.');
                        }
                    }
                }
            }
        };
        xhr.send();
    };

    Children.add = function( items ) {
        let children = Children.response.data || [];
        let userBlocks = '';
        items.forEach(function(id){
            children.forEach(function(val){
                if (val.id == id) {
                    let contacts = val.email ? val.email : val.phone;
                    userBlocks += '<p><input type="hidden" class="added-children" name="child_ids[]" value="' + id + '">';
                    userBlocks += '<strong>' + val.name + ' (' + contacts + ')</strong>';
                    userBlocks += ' <button type="button" class="close rm_child">×</button></p>';
                    return;
                }
            });
        });

        Children.wrap.innerHTML += userBlocks;
    };

    Children.addError = function(text) {
        if ( text )
            Children.modal_body.innerHTML = '<p class="error">' + text + '</p>'
                + Children.modal_body.innerHTML;
    };

    Children.removeError = function() {
        let errorBlock = Children.modal_dialog.querySelector('.error');
        if ( errorBlock ) {
            Children.modal_body.removeChild( errorBlock );
        }
    };

    Children.pagination = function ( opt ) {
        if ( opt.last_page < 2 ) return '';

        let block     = '<p class="pagenavy" id="table_pager">',
            dots_item = '<span class="dots">...</span>',
            cntBefore = false,
            cntAfter  = false;

        if ( opt.prev_page_url ) block += '<a href="' + opt.prev_page_url + '" class="prev"></a>';

        for (i=1; i <= opt.last_page; i++) {
            if (i > 2 && i < opt.current_page - 2) {
                if (!cntBefore) {
                    block += dots_item;
                    cntBefore = true;
                }
                continue;
            }

            if (i > opt.current_page + 2 && i < opt.last_page - 1) {
                if (!cntAfter) {
                    block += dots_item;
                    cntAfter = true;
                }
                continue;
            }

            if ( opt.current_page === i ) {
                block += '<span class="current">' + i + '</span>';
                continue;
            }

            block += '<a href="' + opt.path + '?page=' + i + '">' + i + '</a>';
        }

        if ( opt.next_page_url ) block += '<a href="' + opt.next_page_url + '" class="next"></a>';

        return block + '</p>';
    };

    Children.remove_pagination = function() {
        if ( document.getElementById('table_pager') )
            Children.modal_body.removeChild( document.getElementById('table_pager') );
    };

    Children.has_children = function(block)
    {
        if (!block) return null;
        return block.querySelectorAll('.added-children').length > 0;
    };

    Children.has_available_children = function() {
        let current_childrenIds  = Children.get_selected_users( document.querySelectorAll('.added-children') );
        let availableChildrenIds = Children.response.data.map(function(item) {
            return item.id;
        });

        availableChildrenIds = availableChildrenIds.filter(function(item){
            return current_childrenIds.indexOf(item) === -1;
        });

        return availableChildrenIds.length;
    };

    Children.show_hide_add_children_btn = function() {
        if( Children.has_available_children() || document.getElementById('table_pager')
            || Children.request['string']) {
            Children.add_button.style.display = 'block';
        } else {
            Children.add_button.style.display = 'none';
        }
    };

    // EVENT LISTENERS

    // OPEN dialog
    Children.add_button.addEventListener('click', function() {
        if ( ! Children.response ) {
            Children.removeError();
            Children.load();
        } else {
            let current_children = Children.get_selected_users( document.querySelectorAll('.added-children') );
            Children.render( current_children );
        }
    });

    // ADD selected children
    Children.modal_add_button.addEventListener('click', function() {
        let selected_inputs = document.querySelectorAll( '.children-list:checked' );
        let selected_users = Children.get_selected_users( selected_inputs );

        selected_inputs.forEach(function(el){
            el.removeAttribute('checked');
        });
        Children.add( selected_users );
        Children.modal_dialog.getElementsByClassName('close')[0].click();

        if (true === Children.has_children( Children.wrap )) {
            Children.wrap.classList.add('box-style');
        }

        Children.show_hide_add_children_btn();
    });

    // REMOVE child
    Children.wrap.addEventListener('click', function(e){
        if ( e.target.classList.contains('rm_child')
            && e.target.parentNode.tagName == 'P' ) {
            if ( confirm('Do you really want to remove the child user?') ) {
                let child  = e.target.parentNode;
                let parent = child.parentNode;
                parent.removeChild( child );

                if ( false === Children.has_children( parent ) ) {
                    parent.classList.remove('box-style');
                }

                Children.show_hide_add_children_btn();
            }
        }
    });

    // SEARCH click
    Children.modal_dialog.addEventListener('submit',function(e) {
        e.preventDefault();
        Children.waiting('start');
        Children.removeError();
        Children.load();
    });

    // PAGINATION click
    Children.modal_dialog.addEventListener('click',function(e) {
        if ( e.target.tagName == 'A'
            && e.target.parentNode.classList.contains('pagenavy') ) {

            e.preventDefault();
            Children.waiting('start');
            Children.removeError();
            Children.load( e.target.getAttribute('href') );
        }
    });

    // RUNNING
    Children.load(null, Children.show_hide_add_children_btn);
})();