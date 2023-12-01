<nav class="sidebar position-relative d-flex flex-column align-items-stretch" style="min-width: 250px; max-width: 250px;">
    <ul class="p-0">
        <li class="py-3 px-5 sidebar-element">
            <a href="{{ route('questions.top') }}" class="nav-link">Top Questions</a>
        </li>
        <li class="py-3 px-5 sidebar-element">
            <a href="{{ route('questions') }}" class="nav-link">Recent Questions</a>
        </li>
        @if (Auth::check())
            <li class="py-3 px-5 sidebar-element">
                <a href="{{ route('questions.followed') }}" class="nav-link">Followed Questions</a>
            </li>
        @endif
        <hr class="m-0">
        <li class="py-3 px-5 sidebar-element">
            <a href="{{ route('tags') }}" class="nav-link">Tags</a>
        </li>
        @if (Auth::check())
            <hr class="m-0">
            <li class="py-3 px-5 sidebar-element">
                <a href="{{ route('tag.create') }}" class="nav-link">Create a Tag</a>
            </li>
        @endif
    </ul>
</nav>