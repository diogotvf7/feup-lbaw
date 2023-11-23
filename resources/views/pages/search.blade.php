@extends((($includeAll) ? 'layouts.app' : 'layouts.plain' ))

@section('content')
<section>
    <section id="search-results" class="p-5">
        <h2 class="text-primary-emphasis">Search results for '<strong>{{$query}}</strong>'</h2>


<section>
    <section id="search-results" class="p-5">
        <h2 class="text-primary-emphasis">Search results for '<strong>{{$query}}</strong>'</h2>
        <div class="accordion mt-3 rounded border border-primary" id="accordionSearch">
            <div class="accordion-item list-group accordion-header d-flex justify-content-between align-items-center" id="headingOne">
                <button class="list-group-item accordion-button d-flex flex-row justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <p class="my-0">Questions</p>
                    <span class="mx-3 badge bg-primary rounded-pill">{{count($questions)}}</span>
                </button>
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
    </section>
</section>
@endsection