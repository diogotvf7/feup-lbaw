<article class="d-flex">
    <div class="d-flex flex-column justify-content-center align-content-end text-secondary me-3 text-nowrap text-end">
        <span>{{ $question->voteBalance() }} votes</span>
        <span>{{ $question->answers->count() }} answers</span>
    </div>

    <div class="flex-grow-1 d-flex flex-column justify-content-between align-items-stretch gap-2 me-5">
        <div class="flex-grow-1">
            <a href="{{route('question.show', $question->id)}}" class="text-decoration-none text-wrap text-break"><h6>{{ $question->title }}</h6></a>
            <p class="preview-body text-wrap text-break">{{ $question->updatedVersion->body }}</p>

            <div class="d-flex flex-wrap justify-content-between">
                <div class="d-flex gap-1">
                    @foreach ($question->tags as $tag)
                    @if($tag->approved)
                    <a href="{{ route('tag.show', $tag->id) }}" class="badge bg-primary text-decoration-none my-auto">{{$tag->name}}</a>
                    @endif
                    @endforeach
                </div>
                <div class="text-secondary text-wrap d-flex flex-row align-items-center d-flex flex-row align-content-center" style="width:max-content;">
                    @include('partials.userPreview', ['user' => $question->user])
                    <p class="m-0">&NonBreakingSpace;
                    asked&NonBreakingSpace;{{ \Carbon\Carbon::parse($question->firstVersion->date)->diffForHumans() }}
                    @if ($question->firstVersion->date != $question->updatedVersion->date)
                    ,&NonBreakingSpace;updated&NonBreakingSpace;{{ \Carbon\Carbon::parse($question->updatedVersion->date)->diffForHumans() }}
                    @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</article>