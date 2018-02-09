<h1>Advertiser list</h1>
<div id="admin-users-search">
    <label for="email">By email:</label>
    <input type="text" name="email" id="email" value="">

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
        }
    })();
</script>