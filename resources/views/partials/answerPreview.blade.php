<article class="d-flex">
    <div class="d-flex flex-column justify-content-center align-content-end text-secondary me-3 text-nowrap text-end">
        <span>{{ $answer->vote_balance }} votes</span>
    </div>

    <div class="flex-grow-1 d-flex flex-column justify-content-between align-items-stretch gap-2 me-5">
        <div class="flex-grow-1">
            <a href="{{route('question.show', $answer->question->id)}}" class="text-decoration-none text-wrap text-break">{{ $answer->question->title }}</a>
            <p class="preview-body text-wrap text-break">{{ $answer->updatedVersion->body }}</p>
        </div>
        <div class="d-flex justify-content-end mx-3">
            <div class="align-self-end text-secondary text-wrap text-break d-flex flex-row align-items-center">
                @include('partials.userPreview', ['user' => $answer->user])
                <p class="m-0">
                    &NonBreakingSpace;answered {{ \Carbon\Carbon::parse($answer->firstVersion->date)->diffForHumans() }}
                    @if ($answer->firstVersion->date != $answer->updatedVersion->date)
                    , updated {{ \Carbon\Carbon::parse($answer->updatedVersion->date)->diffForHumans() }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</article>