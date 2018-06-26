(function() {
    window.nau.children = new Children_list();
    window.nau.children.run();

    function Children_list() {
        const PARENT_ROLES_NAMES = ['agent', 'chief_advertiser'];

        let self                = this;
        let main_wrap           = document.getElementById('children');
        let form_delete         = document.getElementById('children_deleting');
        let pagination          = document.getElementById('children_pagination');
        let children_url_params = 'with=parents:id,name,email;parents.roles:name';
        let editableUserId      = location.pathname.split('/')[2];
        let rolesSequence       = window.nau.roles;
        let editable_user_role  = form_delete.dataset.userRoles.split(',')[0];
        let intermediate_roles  = rolesSequence.slice(rolesSequence.indexOf(editable_user_role) + 1),
            messages_page       = document.querySelector('#children .messages');

        let event_children_loaded = new Event('event_children_loaded');

        this.get_intermediate_parents = function(parents) {
            return parents.filter(function(parent) {

                let able_roles = parent.roles.filter(function(role) {
                    return intermediate_roles.indexOf(role.name) !== -1;
                });

                return parent.id !== editableUserId && able_roles.length;
            })
                .map(function(parent) {
                    return {
                        id    : parent.id,
                        name  : parent.name,
                        email : parent.email,
                    }
                });
        };

        this.waiting = function(is_show) {
            let table_wrap_classes = main_wrap.querySelector('.table_wrap').classList;
            if (is_show) {
                table_wrap_classes.add('disabled');
            } else {
                table_wrap_classes.remove('disabled');
            }
        };

        this.render = function() {
            let body = '';
            let data = self.response.data || [];
            let input_tmpl = '<input type="hidden" class="child" name="children_ids[]" value="%value%"'
                + ' data-roles="%roles%" data-parents="%parents%" >';

            data.forEach(function(child) {
                let roles = (child.roles && child.roles.length) ? child.roles : [];
                roles = roles.map(function($role) {
                    return $role.name;
                }).join(', ');

                let parents = self.get_intermediate_parents(child.parents);
                if (parents.length) {
                    parents = encodeURI(JSON.stringify(parents));
                }

                let row = [
                    input_tmpl.replace('%value%', child['id']).replace('%roles%', roles).replace('%parents%', parents) +
                    (child['name'] ? child['name'] : '-'),
                    child['email'] ? child['email'] : '-',
                    child['phone'] ? child['phone'] : '-',
                    roles,
                    '<button type="button" class="close rm_child">×</button>',
                ];

                body += '<tr><td>' + row.join('</td><td>') + '</td></tr>';
            });

            main_wrap.getElementsByTagName('tbody')[0].innerHTML = body;
            pagenavyCompactAjax(self.response, pagination);
            main_wrap.querySelector('.total span').innerText = self.response.total;
        };

        this.load_callback = function( response ) {
            self.waiting(false);
            if ( response ) {
                self.response = JSON.parse(response);
                self.render();
                document.getElementById('children_list').dispatchEvent(event_children_loaded);
            }
        };

        this.load = function( url ) {
            let xhr = new XMLHttpRequest();
            url = url ? url + '&' : form_delete.action + '?';

            xhr.open( "GET", url + children_url_params );
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.onreadystatechange = function() {
                self.ajax_callback(this, 200, self.load_callback);
            };

            self.waiting(true);
            xhr.send();
        };

        this.ajax_callback = function(xhr, success_code, callback) {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                switch (xhr.status) {
                    case success_code : callback( xhr.response );
                        break;
                    case 401 : UnAuthorized();
                        break;
                    case 500 :
                    default  : {
                        try {
                            let responseObj = JSON.parse(xhr.response);
                            if (responseObj.error && responseObj.message)
                                messages('add', 'error', 'Error: ' + responseObj.message, messages_page);
                        } catch (e) {
                            messages('add', 'error', nau_lang.an_error, messages_page);
                        }
                    }
                }
            }
        };

        this.remove_callback = function() {
            messages('add', 'success', 'Child was detached.', messages_page);
            self.waiting(false);
            self.load();
        };

        this.delete = function(child_id) {
            let formData = new FormData(form_delete);
            let xhr      = new XMLHttpRequest();

            if (child_id) {
                formData.set('children_ids[]', child_id);
            }

            xhr.open("POST", form_delete.action);
            xhr.onreadystatechange = function() {
                self.ajax_callback(this, 205, self.remove_callback);
            };
            xhr.send(formData);
            self.waiting(true);
        };

        this.run = function() {
            self.load(null, function() {
                document.getElementById('children_list').dispatchEvent(event_children_loaded);
            });
        };

        // EVENTS Listeners

        // EVENTS Listener on SAVING new children to reload list
        document.getElementById('children_list').addEventListener('event_children_saving', function() {
            self.waiting(true);
        });
        document.getElementById('children_list').addEventListener('event_children_saved', function() {
            self.waiting(false);
            messages('add', 'success', 'New children were attached.', messages_page);
            self.load();
        });

        // REMOVE child
        form_delete.addEventListener('click', function(e){
            if ( e.target.classList.contains('rm_child')
                && e.target.parentNode.tagName == 'TD' ) {

                let child       = e.target.parentNode.parentNode;
                let question    = nau_lang.adding_children.confirmation;
                let child_input = child.querySelector('.child');
                let roles       = child_input.dataset.roles;
                let parents     = child_input.dataset.parents;

                if (parents) {
                    parents  = JSON.parse(decodeURI(parents));
                    parents  = parents.map(function(parent) {
                        return parent.name + ' (%email%)'.replace('%email%', parent.email);
                    }).join(', ');
                    question = nau_lang.adding_children.reject_remove;
                    alert(question + parents);
                    return;
                }

                if (roles && roles.length) {
                    let present_parent_roles = PARENT_ROLES_NAMES.filter(function(role) {
                        return roles.indexOf(role) > -1;
                    });

                    if (present_parent_roles.length) {
                        question = nau_lang.adding_children.confirmation_with_grandchildren;
                    }
                }

                if ( confirm(question) ) {
                    self.delete(child_input.value);
                }
            }
        });

        // PAGINATION click
        pagination.addEventListener('click', function(e) {
            if ( e.target.tagName == 'A' ) {
                e.preventDefault();
                self.load(e.target.getAttribute('href'));
            }
        });
    }

})();