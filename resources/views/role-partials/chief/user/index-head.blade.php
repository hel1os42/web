<h1>Advertiser list</h1>
<div id="admin-users-search">
    <div>
        <p>By user:&nbsp;&nbsp;
            <input type="text" name="name" id="name" value="" placeholder="name">
            <input type="text" name="email" id="email" value="" placeholder="email">
            <input type="text" name="phone" id="phone" value="" placeholder="phone">
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
