@extends((($includeAll) ? 'layouts.app' : 'layouts.plain' ))

@section('content')
<section>
    <section id="search-results" class="p-5">
        <h2 class="text-primary-emphasis">Search results for '<strong>{{$query}}</strong>'</h2>
        <div class="d-flex flex-row">
            <div class="accordion mt-3 rounded border border-primary me-5" id="accordionSearch" style="width: 75dvw;">
                <div class="accordion-item list-group accordion-header d-flex justify-content-between align-items-center" id="headingOne">
                    <div class="list-group-item accordion-button d-flex flex-row justify-content-between align-items-center">
                        <p class="my-0">Questions</p>
                        <span class="mx-3 badge bg-primary rounded-pill">{{count($questions)}}</span>
</div>
                    <div id="collapseOne" class="w-100 accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                            @if (count($questions) !== 0)
                            @foreach ($questions as $question)
                            @include('partials.questionPreview', ['question' => $question])
                            @if (!$loop->last)
                            <hr>
                            @endif
                            @endforeach
                            @else
                            <p class="m-0">No questions found</p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <div class="vr"></div>
            <div class="ms-5">
                @if (count($tags) !== 0)
                @foreach ($tags as $tag)
                @include('partials.tagPreview', ['tag' => $tag])
                @endforeach
                @else
                <p class="m-0">No tags found</p>
                @endif
            </div>
        </div>
    </section>

</section>
@endsection