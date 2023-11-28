<article>
    <div class="d-flex">
        <div class="d-flex flex-column justify-content-center align-content-end text-secondary me-3 text-nowrap text-end">
            <span>{{ $question->voteBalance() }} votes</span>
            <span>{{ $question->answers->count() }} answers</span>
        </div>

        <div class="flex-grow-1">
            <a href="{{route('question.show', $question->id)}}" class="text-decoration-none">{{ $question->title }}</a>
            <p class="preview-body px-3 text-wrap text-break">{{ $question->updatedVersion->body }}</p>
        </div>
    </div>

    <div class="text-secondary text-nowrap d-flex justify-content-end align-items-end gap-2 me-5">
        <div class="d-flex gap-1">
            @foreach ($question->tags as $tag)
                <p class="badge badge-primary bg-primary text-decoration-none m-0">{{$tag->name}}</p>
            @endforeach
        </div>
        <div>
        @if (Auth::check())
            <a href="{{ route('user.profile', $question->user->id) }}" class="text-decoration-none">{{ $question->user->name }}</a>
        @else
            <span>{{ $question->user->name }}</span>
        @endif
        asked {{ \Carbon\Carbon::parse($question->firstVersion->date)->diffForHumans() }}
        </div>
    </div>
</article>