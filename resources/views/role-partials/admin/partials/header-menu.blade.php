<li><a href="{{ route('home') }}">Dashboard</a></li>
<li>
    <a href="{{ route('users.index', ['orderBy' => 'updated_at', 'sortedBy' => 'desc']) }}">Users</a>
</li>
<li>
    <a href="{{ route('categories.index') }}">Categories & retail types</a>
</li>
<li>
    <a href="{{ route('tags.index') }}">Tags</a>
</li>