<h1>{{ __('users.titles.advertiser_list') }}</h1>
<div id="admin-users-search">
    <div class="m-b-10">
        <p>
            <label for="search_fields">
                <b>{{ __('users.titles.users_search') }}</b>
            </label>
        </p>

        <input type="text" name="search_fields" id="search_fields" value="" style="width: 400px;" tabindex="1" />

        <form method="get" action="{{ route('users.index') }}" id="search-form" style="display: inline-block;">
            <input type="hidden" name="search" id="search-field" value="">
            <input type="hidden" name="searchJoin" id="search_join" value="">
            <button type="submit" class="btn m-l-10">{{ __('buttons.search') }}</button>
        </form>
    </div>
</div>