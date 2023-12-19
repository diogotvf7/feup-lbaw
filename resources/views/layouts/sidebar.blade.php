<nav class="sidebar position-relative d-flex flex-column align-items-stretch" style="min-width: 250px; max-width: 250px;">
    <div class="px-3 pt-3">
        <ul class="p-0">
            <li class="py-3 px-1 sidebar-element">
                <a href="{{ route('questions.top') }}" class="text-decoration-none">
                    Top Questions
                </a>
            </li>

            <li class="py-3 px-1 sidebar-element">
                <a href="{{ route('questions') }}" class="text-decoration-none">
                    Recent Questions
                </a>
            </li>

            @if (Auth::check())
            <li class="py-3 px-1 sidebar-element">
                <a href="{{ route('questions.followed') }}" class="text-decoration-none">
                    Followed Questions
                </a>
            </li>
            @endif

            <hr class="m-0">

            <li class="py-3 px-1 sidebar-element">
                <a href="{{ route('tags') }}" class="text-decoration-none">
                    Tags
                </a>
            </li>

            <hr class="m-0">

            <li class="py-3 px-1 sidebar-element">
                <a href="{{ route('info') }}" class="text-decoration-none">
                    Info
                </a>
            </li>
        </ul>
        <div id="filters" class="accordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed px-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Filters
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                    <form class="accordion-body" action="{{ route('questions') }}" method="GET">

                        <div class="form-group">                    
                            <label for="filter" class="form-label mt-4">Tags</label>
                            <div id="top-tags" class="d-flex flex-wrap">
                            </div>
                        </div>

                        <div class="">
                            <input type="checkbox">
                        </div>

                        <div class="d-grid mb-0">
                            <button type="submit" class="btn btn-primary" aria-label="Apply Filters">
                                <i class="bi bi-filter"></i> Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>