<?php
    use Illuminate\Support\Facades\Request;
    use Illuminate\Support\Facades\Route;

    if (Request::is('search')) {
        $url = '/search';
    } else {
        $url = Request::url();
    }

    $request = Request::all();

    $hasParams = sizeof($request) > 0;
    
    $searchTerm = $request['searchTerm'] ?? '';

    $no_answers = $request['no-answers'] ?? null;
    $no_accepted_answers = $request['no-accepted-answers'] ?? null;
    $sort = $request['sort'] ?? null;
?>

<nav id="sidebar" class="flex-column align-items-stretch" style="min-width: 250px; max-width: 250px;">
    <div class="px-3 pt-3 pb-5 overflow-y-scroll">
        <ul class="p-0 m-0">
            <li class="py-3 px-1 sidebar-element">
                <a href="{{ route('questions.top') }}" class="text-decoration-none">
                    Top Questions
                </a>
            </li>

            <li class="py-3 px-1 sidebar-element">
                <a href="{{ route('questions') }}" class="text-decoration-none">
                    All Questions
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


            @if (Request::is('search') || Request::is('questions') || (Request::is('questions/*') && !(Route::is('question.show')) && !(Request::is('questions/create'))))        
            <hr class="m-0">
            
            <li class="sidebar-element">
                <div id="filters-accordion" class="accordion">
                    <div>
                        <h2 class="accordion-header">
                            <a class="accordion-button text-decoration-none {{ $hasParams ? '' : 'collapsed' }} px-1" type="button" data-bs-toggle="collapse" data-bs-target="#filters" aria-expanded="{{ $hasParams }}" aria-controls="filters">
                                <i class="bi bi-filter me-1"></i> Filters
                            </a>
                        </h2>
                        <div id="filters" class="accordion-collapse collapse {{ $hasParams ? 'show' : '' }}" aria-labelledby="filters" data-bs-parent="#filters-accordion">
                            <form class="accordion-body p-0" action="{{ $url }}" method="GET">

                                @if (Request::is('search'))
                                    <input type="hidden" id="search-term" name="searchTerm" value="{{ $searchTerm }}">
                                @endif
                                
                                <div class="form-group my-1">
                                    <div class="form-check">
                                        <input id="no-answers" class="form-check-input" type="checkbox" name="no-answers" {{ $no_answers ? 'checked' : '' }}>
                                        <label class="form-check-label" for="no-answers">No answers</label>
                                    </div>
                                    <div class="form-check">
                                        <input id="no-accepted-answers" class="form-check-input" type="checkbox" name="no-accepted-answers" {{ $no_accepted_answers ? 'checked' : '' }}>
                                        <label class="form-check-label" for="no-accepted-answers">No accepted answers</label>
                                    </div>
                                </div>

                                @if (!Request::is('questions/top'))    
                                <fieldset class="form-group">
                                    <p class="my-1">Sort by:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort" id="latest" value="latest" {{ $sort=="latest" ? 'checked' : '' }}>
                                        <label class="form-check-label" for="latest">
                                            Latest
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort" id="oldest" value="oldest" {{ $sort=="oldest" ? 'checked' : '' }}>
                                        <label class="form-check-label" for="oldest">
                                            Oldest
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort" id="votes" value="votes" {{ $sort=="votes" ? 'checked' : '' }}>
                                        <label class="form-check-label" for="votes">
                                            Most votes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort" id="answers" value="answers" {{ $sort=="answers" ? 'checked' : '' }}>
                                        <label class="form-check-label" for="answers">
                                            Most answers
                                        </label>
                                    </div>
                                </fieldset>
                                @endif

                                <div class="form-group">
                                    <label for="tags" class="form-label">Tags</label>
                                    <input id="tag-input" type="text" name="tags" class="form-control">
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
            </li>
            @endif
            
            <hr class="m-0">

            <li class="py-3 px-1 sidebar-element">
                <a href="{{ route('info') }}" class="text-decoration-none">
                    Info
                </a>
            </li>
        </ul>
        
    </div>
</nav>
<button id="sidebar-toggle" class="btn btn-primary m-3">
    <i class="bi bi-chevron-double-right"></i>
</button>
