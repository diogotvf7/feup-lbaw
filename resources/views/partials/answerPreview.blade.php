<article class="d-flex">
    <div class="d-flex flex-column justify-content-center align-content-end text-secondary me-3 text-nowrap text-end">
        <span>{{ $answer->vote_balance }} votes</span>
    </div>

    <div class="flex-grow-1">
        <a href="{{route('question.show', $answer->question->id)}}" class="text-decoration-none">{{ $answer->question->title }}</a>
        <p class="px-3">{{ $answer->updatedVersion->body }}</p>
    </div>

    <div class="text-nowrap d-flex flex-column justify-content-end align-content-end me-5">
        <div class="text-secondary">
            @if (Auth::check())
                <a href="{{ route('user.profile', $answer->user->id) }}" class="text-decoration-none">{{ $answer->user->name }}</a>
            @else
                <span>{{ $answer->user->name }}</span>
            @endif
            answered {{ \Carbon\Carbon::parse($answer->firstVersion->date)->diffForHumans() }}
        </div>
    </div>
</article>