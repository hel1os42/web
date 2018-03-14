<h1>Advertiser list</h1>
<div id="admin-users-search">
    <label for="email">By email:</label>
    <input type="text" name="email" id="email" value="">
    <label for="place">By place:</label>
    <input type="text" name="place" id="place" value="">

    <form method="get" action="{{ route('users.index') }}" id="search-form" style="display: inline-block;">
        <input type="hidden" name="search" id="search-field" value="">
        <input type="hidden" name="searchJoin" value="and">
        <button type="submit" class="btn">Search</button>
    </form>
</div>
