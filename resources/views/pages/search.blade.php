@extends((($includeAll) ? 'layouts.app' : 'layouts.plain' ))

@section('content')
<div id="search-page" class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <section class="scroll-container overflow-y-scroll w-100 p-3">
        <header class="d-flex justify-content-between align-items-center p-3">
            <h1 class="text-primary-emphasis">Search results for '<strong>{{$query}}</strong>'</h1>
            <a href="/questions/create" class="btn btn-primary">Ask Question</a>
        </header>

        <ul class="nav nav-tabs d-flex mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#questions" aria-selected=true role="tab">Questions</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#tags" aria-selected="false" role="tab" tabindex="-1">Tags</a>
            </li>
        </ul>

        <div id="myTabContent" class="tab-content">
            <div id="questions" class="tab-pane fade active show w-100" role="tabpanel">
                @if (count($questions) !== 0)
                    @foreach ($questions as $question)
                        @include('partials.questionPreview', ['question' => $question])
                        @if (!$loop->last)
                            <hr>
                        @endif
                    @endforeach
                @else
                    <p class="text-center">No questions found</p>
                @endif
            </div>

            <div id="tags" class="tab-pane fade w-100" role="tabpanel">
                @if (count($tags) !== 0)
                @foreach ($tags as $tag)
                @include('partials.tagPreview', ['tag' => $tag])
                @endforeach
                @else
                <p class="text-center">No tags found</p>
                @endif
            </div>
        </div>
    </section>
</div>
@if (session('success')) 
    <div class="alert alert-dismissible alert-success position-absolute bottom-0 end-0 m-5">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ session('success')[0] }}</strong> 
        @if (isset(session('success')[1]))
            <a href="{{ session('success')[1] }}" class="alert-link">Check it here</a>.
        @endif
    </div>
@endif
<button type="button" class="btn btn-primary rounded" id="back-top">
    <i class="bi bi-arrow-up"></i>
</button>
@endsection 