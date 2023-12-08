<nav class="sidebar position-relative d-flex flex-column align-items-stretch" style="min-width: 250px; max-width: 250px;">
    <ul class="p-0">
        <li class="py-3 px-5 sidebar-element">
            <a href="{{ route('questions.top') }}">Top Questions</a>
        </li>
        <li class="py-3 px-5 sidebar-element">
            <a href="{{ route('questions') }}">Recent Questions</a>
        </li>
        @if (Auth::check())
            <li class="py-3 px-5 sidebar-element">
                <a href="{{ route('questions.followed') }}">Followed Questions</a>
            </li>
        @endif
        <hr class="m-0">
        <li class="py-3 px-5 sidebar-element">
            <a href="{{ route('tags') }}">Tags</a>
        </li>
    </ul>
</nav>