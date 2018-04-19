<h1>Users list</h1>
<a href="{{ route('users.create') }}" style="float:right" class="btn">+ Add new user</a>
<div id="admin-users-search">
    <div>
        <p>By user:&nbsp;&nbsp;
            <input type="text" name="name" id="name" value="" placeholder="name">
            <input type="text" name="email" id="email" value="" placeholder="email">
            <input type="text" name="phone" id="phone" value="" placeholder="phone">
            <select name="role" id="role"
                    style="padding: 1px 0px; width: 199px;  border: 1px solid darkgrey; height: 28px;">
                <option value="" selected>All roles</option>
                <option value="chief_advertiser">Chief advertiser</option>
                <option value="advertiser">Advertiser</option>
            </select>
        </p>

    </div>
    <div>
        <p>By place:&nbsp;
            <input type="text" name="place.name" id="place-name" value="" placeholder="name">
            <input type="text" name="place.description" id="place-description" value="" placeholder="description">
        </p>
    </div>

    <form method="get" action="{{ route('users.index') }}" id="search-form" style="display: inline-block;">
        <input type="hidden" name="search" id="search-field" value="">
        <input type="hidden" name="searchJoin" value="and">
        <button type="submit" class="btn">Search</button>
    </form>
</div>
