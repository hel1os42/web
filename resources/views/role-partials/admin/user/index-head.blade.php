<h1>{{ __('users.titles.users_list') }}</h1>
<div id="admin-users-search">
    <div class="m-b-10">
        <p>
            <label for="search_fields">
                <b>{{ __('users.titles.users_search') }}</b>
            </label>
        </p>

        <input type="text" name="search_fields" id="search_fields" value="" style="width: 400px;" tabindex="1" />

        <label>
            <select name="role" id="role" style="padding: 1px 0px; width: 199px;  border: 1px solid darkgrey; height: 30px;" tabindex="2">
                <option value="" selected>{{ __('words.all_roles') }}</option>
                <option value="admin">{{ __('words.admin') }}</option>
                <option value="agent">{{ __('words.agent') }}</option>
                <option value="chief_advertiser">{{ __('words.chief_advertiser') }}</option>
                <option value="advertiser">{{ __('words.advertiser') }}</option>
                <option value="user">{{ __('words.user') }}</option>
            </select>
        </label>

        <form method="get" action="{{ route('users.index') }}" id="search-form" style="display: inline-block;">
            <input type="hidden" name="search" id="search-field" value="">
            <input type="hidden" name="whereFilters" id="where-filter-field" value="">
            <input type="hidden" name="searchJoin" id="search_join" value="">
            <button type="submit" class="btn m-l-10">{{ __('buttons.search') }}</button>
        </form>
    </div>
</div>

<div class="clearfix">
    <a href="{{ route('users.create') }}" style="float:right" class="btn">{{ __('buttons.add_new_user') }}</a>
</div>
