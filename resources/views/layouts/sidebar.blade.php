<?php
    use Illuminate\Support\Facades\Request;

    $url = Request::url();
    
    $request = Request::all();

    $hasParams = sizeof($request) > 0;

    $no_answers = $request['no-answers'] ?? null;
    $no_accepted_answers = $request['no-accepted-answers'] ?? null;

?>

<nav class="sidebar position-relative d-flex flex-column align-items-stretch" style="min-width: 250px; max-width: 250px;">
    <div class="px-3 pt-3 pb-5 overflow-y-scroll">
        <ul class="p-0 m-0">
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
        @if ((Request::is('questions') || Request::is('questions/*') && !(Request::is('questions/create'))))
        <hr class="m-0">
        <div id="filters-accordion" class="accordion">
            <div>
                <h2 class="accordion-header">
                    <button class="accordion-button {{ $hasParams ? '' : 'collapsed' }} px-1" type="button" data-bs-toggle="collapse" data-bs-target="#filters" aria-expanded="{{ $hasParams }}" aria-controls="filters">
                        <i class="bi bi-filter me-1"></i> Filters
                    </button>
                </h2>
                <div id="filters" class="accordion-collapse collapse {{ $hasParams ? 'show' : '' }}" aria-labelledby="filters" data-bs-parent="#filters-accordion">
                    <form class="accordion-body p-0" action="{{ $url }}" method="GET">

                        <div class="form-group">
                            <label for="tags" class="form-label">Tags</label>
                            <input id="tag-input" type="text" name="tags" class="form-control">
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="no-answers" {{ $no_answers ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexSwitchCheckChecked">No answers</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="no-accepted-answers" {{ $no_accepted_answers ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexSwitchCheckChecked">No accepted answers</label>
                            </div>
                        </div>

                        <div class="d-grid my-3">
                            <button type="submit" class="btn btn-primary" aria-label="Apply Filters">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</nav>