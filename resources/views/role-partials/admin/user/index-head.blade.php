<h1>Users list</h1>
<a href="{{route('users.create')}}" style="float:right" class="btn">+ Add new user</a>
<div id="admin-users-search">
    <label for="email">By email:</label>
    <input type="text" name="email" id="email" value="">
    <label for="role">By role:</label>
    <select name="role" id="role">
        <option value="" selected>All</option>
        <option value="admin">Admin</option>
        <option value="agent">Agent</option>
        <option value="chief_advertiser">Chief advertiser</option>
        <option value="advertiser">Advertiser</option>
        <option value="user">User</option>
    </select>

    <form method="get" action="{{ route('users.index') }}" id="search-form" style="display: inline-block;">
        <input type="hidden" name="search" id="search-field" value="">
        <input type="hidden" name="searchJoin" value="and">
        <button type="submit" class="btn">Search</button>
    </form>
</div>

<script>
    (function(){
        let searchOptions = location.search.substr(1).split('&');
        searchOptions = searchOptions.map(function(e){ return e.split('='); });
        let search = searchOptions.find(function(e){ return e[0] === 'search' });
        if (search) {
            search = search[1].split(';');
            search = search.map(function(e){ return e.split(':'); });
            let searchByEmail = search.find(function(e){ return e[0] === 'email' });
            if (searchByEmail) document.getElementById('email').value = searchByEmail[1];
            let searchByRole = search.find(function(e){ return e[0] === 'roles.name' });
            if (searchByRole) {
                let options = document.getElementById('role').children;
                for (let i = 0; i < options.length; i++) if (options.value === searchByRole[1]) options[i].selected = true;
            }
        }
    })();
</script>