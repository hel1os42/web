(function(){

    // RUNNING - autoload new children on EVENT `event_children_loaded`
    window.nau.new_children = new New_children();

    function New_children() {
        let self             = this,
            wrap             = document.getElementById('children_list'),
            add_button       = document.getElementById('add_children'),
            modal_add_button = document.getElementById('attach_selected_children'),
            form             = document.getElementById('search_children'),
            modal_dialog     = document.getElementById('add_children_list'),
            pagination_modal = document.getElementById('modal_pagination'),
            modal_body       = document.querySelector('#add_children_list .modal-body'),
            border_row_color = document.querySelector('.table-striped-nau tr td').style.borderBottomColor,
            messages_modal   = modal_body.getElementsByClassName('messages')[0];

        const PARENT_ROLES_NAMES = ['agent', 'chief_advertiser'];

        this.info = [];

        this.waiting = function( action ) {
            let img = modal_dialog.querySelector('img[src*="loading"]');
            let table = modal_dialog.getElementsByTagName('tbody')[0];
            let border;

            switch ( action ) {
                case 'stop' : {
                    img.style.opacity = '0';
                    img.style.height = '0';
                    table.style.opacity = '1';
                    table.style.visibility = 'visible';
                    border = border_row_color;
                    break;
                }
                case 'start': {
                    img.style.opacity = '1';
                    img.style.height = 'auto';
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

        this.get_selected_users = function( children ) {
            let arr = [];
            children.forEach( function( current ) {
                arr.push( current.value );
            });

            return arr;
        };

        this.setParams = function( url ) {
            self.request    = [];
            self.get_params = [];

            let params = [
                'availableForUser=' + document.getElementById('editable_user_id').innerText.trim()
            ];
            let role = document.getElementById('role');
            if ( role ) {
                self.request['role'] = role.options[role.selectedIndex].value;
                role = 'roles.name:' + role.options[role.selectedIndex].value;
            } else {
                role = 'roles.name:advertiser';
            }

            let searchStr = document.getElementById('search_field').value;
            let searchParams = '';
            if ( searchStr ) {
                self.request['string'] = searchStr;
                searchParams = 'email:' + searchStr
                    + ';name:' + searchStr
                    + ';phone:' + searchStr
                    + ';place.name:' + searchStr
                    + ';';
            }

            params.push('whereFilters=' + encodeURIComponent( role ));
            params.push('search=' + encodeURIComponent( searchParams ));
            params.push('searchJoin=or');

            for (let i=0; i<params.length; i++) {
                let sign = (i === 0 && url.indexOf('?') === -1) ? '?' : '&';
                self.get_params += sign + params[i];
            }
        };

        this.render = function() {
            self.info.push('render');
            let table = modal_dialog.getElementsByTagName('tbody');
            let body = '';
            let data = self.response.data || [];

            data.forEach(function(child) {
                let email = child['email'] ? child['email'] : '';
                let phone = child['phone'] ? child['phone'] : '';
                let roles = (child.roles && child.roles.length) ? child.roles : [];
                let input_tmpl = '<input type="checkbox" class="possible-child" name="children_ids[]" value="%id%" data-roles="%roles%">';

                roles = roles.map(function($role) {
                    return $role.name;
                }).join(',');

                let row = [
                    input_tmpl.replace('%id%', child['id']).replace('%roles%', roles),
                    child['name'] ? child['name'] : '-',
                    (email && phone) ? email + ', ' + phone : email + phone,
                    (child['place'] && child['place']['name']) ? child['place']['name'] : '-',
                ];

                body += '<tr><td>' + row.join('</td><td>') + '</td></tr>';
            });

            table[0].innerHTML = body;
            pagenavyCompactAjax(self.response, pagination_modal);
        };

        this.load_callback = function( response ) {
            self.info.push('load children callback');

            if ( response ) {
                self.response = JSON.parse(response);
                self.render();
                self.waiting('stop');
            }
        };

        this.ajax_callback = function(xhr, callback) {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                switch (xhr.status) {
                    case 200 : callback( xhr.response );
                        break;
                    case 401 : UnAuthorized();
                        break;
                    case 500 :
                    default  : {
                        try {
                            let responseObj = JSON.parse(xhr.response);
                            if (responseObj.error && responseObj.message)
                                messages('add', 'error', 'Error: ' + responseObj.message, messages_modal);
                        } catch (e) {
                            messages('add', 'error', nau_lang.an_error, messages_modal);
                        }
                    }
                }
            }
        };

        this.load = function( url ) {
            let xhr = new XMLHttpRequest();
            if ( ! url )
                url = form.getAttribute('action');

            self.setParams( url );

            xhr.open( "GET", url + self.get_params, true );
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.onreadystatechange = function() {
                self.ajax_callback(this, self.load_callback);
            };
            xhr.send();
            self.info.push('loading the possible children');
        };

        this.save_callback = function(response) {
            if ( response ) {
                let event_children_saved = new Event('event_children_saved');
                document.getElementById('children_list').dispatchEvent(event_children_saved);
            }
        };

        wrap.addEventListener('event_children_loaded', function () {
            self.info.push('EVENT - children_loaded');
            self.load();
        });

        // EVENT LISTENERS

        // OPEN dialog
        add_button.addEventListener('click', function() {
            if ( ! self.response ) {
                self.load();
            } else {
                let current_children = self.get_selected_users( document.querySelectorAll('.child') );
                self.render( current_children );
            }
        });

        // SAVE children
        modal_add_button.addEventListener('click', function() {
            let form     = document.getElementById('update_children_form');
            let formData = new FormData(form);
            let xhr      = new XMLHttpRequest();

            let event_children_saving = new Event('event_children_saving');

            xhr.open("POST", form.action);
            xhr.onreadystatechange = function() {
                self.ajax_callback(this, self.save_callback);
            };
            document.getElementById('children_list').dispatchEvent(event_children_saving);
            xhr.send(formData);
        });

        // SEARCH click
        modal_dialog.addEventListener('submit',function(e) {
            e.preventDefault();
            self.waiting('start');
            self.load();
        });

        // PAGINATION click
        modal_dialog.addEventListener('click',function(e) {
            if (e.target.tagName !== 'A') return;
            if (!e.target.parentNode.classList.contains('pagenavy')) return;
            e.preventDefault();
            self.waiting('start');
            self.load( e.target.getAttribute('href') );
        });

        modal_dialog.getElementsByTagName('tbody')[0].addEventListener('change', function(e) {
            if (e.target.tagName !== 'INPUT') return;
            if (!e.target.checked) return;

            let roles = e.target.dataset.roles;

            let present_parent_roles = PARENT_ROLES_NAMES.filter(function(role) {
                return roles.indexOf(role) > -1;
            });

            if (present_parent_roles.length) {
                question = nau_lang.adding_children.warning_grandchildren;
                alert(question);
            }
        });
    }

})();