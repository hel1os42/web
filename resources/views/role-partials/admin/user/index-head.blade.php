<h1>Users list</h1>
<div id="admin-users-search">
    <div class="m-b-20">
        <p>
            <label for="search_fields">
                <b>Search by user (name, email, phone) or place (name, description):</b>
            </label>
        </p>

        <input type="text" name="search_fields" id="search_fields" value="" style="width: 400px;" />

        <label>
            <select name="role" id="role"
                    style="padding: 1px 0px; width: 199px;  border: 1px solid darkgrey; height: 30px;">
                <option value="" selected>All roles</option>
                <option value="admin">Admin</option>
                <option value="agent">Agent</option>
                <option value="chief_advertiser">Chief advertiser</option>
                <option value="advertiser">Advertiser</option>
                <option value="user">User</option>
            </select>
        </label>

        <form method="get" action="{{ route('users.index') }}" id="search-form" style="display: inline-block;">
            <input type="hidden" name="search" id="search-field" value="">
            <input type="hidden" name="searchJoin" value="and">
            <button type="submit" class="btn m-l-10">Search</button>
        </form>
    </div>
</div>

<div class="clearfix">
    <a href="{{ route('users.create') }}" style="float:right" class="btn">+ Add new user</a>
</div>
