<article class="d-flex">
    <div class="d-flex flex-column justify-content-center align-content-end text-secondary me-3 text-nowrap text-end">
        <span>{{ $question->voteBalance() }} votes</span>
        <span>{{ $question->answers->count() }} answers</span>
    </div>

    <div class="flex-grow-1">
        <a href="{{route('question.show', $question->id)}}" class="text-decoration-none">{{ $question->title }}</a>
        <p class="px-3">{{ $question->updatedVersion->body }}</p>

        <div class="d-flex gap-1">
            @foreach ($question->tags as $tag)
                <p class="badge badge-primary bg-primary text-decoration-none m-0">{{$tag->name}}</p>
            @endforeach
        </div>
    </div>

    <div class="text-nowrap d-flex flex-column justify-content-end align-content-end me-5">
        <div class="text-secondary">
            @if (Auth::check())
                <a href="{{ route('user.profile', $question->user->id) }}" class="text-decoration-none">{{ $question->user->name }}</a>
            @else
                <span>{{ $question->user->name }}</span>
            @endif
            asked {{ \Carbon\Carbon::parse($question->firstVersion->date)->diffForHumans() }}
        </div>
    </div>
</article>