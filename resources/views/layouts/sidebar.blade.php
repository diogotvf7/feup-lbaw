<nav class="sidebar position-relative d-flex flex-column align-items-stretch" style="min-width: 250px; max-width: 250px;">
    <ul class="p-0">
        <a href="{{ route('questions.top') }}" class="text-decoration-none">
            <li class="py-3 px-5 sidebar-element">
                Top Questions
            </li>
        </a>
        <a href="{{ route('questions') }}" class="text-decoration-none">
            <li class="py-3 px-5 sidebar-element">
                Recent Questions
            </li>
        </a>
        @if (Auth::check())
        <a href="{{ route('questions.followed') }}" class="text-decoration-none">
            <li class="py-3 px-5 sidebar-element">
                Followed Questions
            </li>
        </a>

        @endif
        <hr class="m-0">
        <a href="{{ route('tags') }}" class="text-decoration-none">
            <li class="py-3 px-5 sidebar-element">
                Tags
            </li>
        </a>

    </ul>
</nav>