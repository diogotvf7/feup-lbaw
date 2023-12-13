<article class="d-flex">

    <div class="flex-grow-1 d-flex flex-column justify-content-between align-items-stretch gap-2 me-5">
        @if ($comment->type === 'QUESTION')
        <div class="flex-grow-1">
            <a href="{{route('question.show',$comment->question_id)}}" class="text-decoration-none text-wrap text-break">{{ $comment->question->title }}</a>
            <p class="preview-body text-wrap text-break">{{ $comment->body}}</p>
        </div>
        @else
        <div class="flex-grow-1">
            <a href="{{route('question.show',$comment->answer->question->id)}}#answer-{{$comment->answer_id}}" class="text-decoration-none text-wrap text-break">{{ $comment->answer->question->title }}</a>
            <p class="preview-body text-wrap text-break">{{ $comment->body}}</p>
        </div>
        @endif
        <div class="d-flex justify-content-end mx-3">
            <div class="align-self-end text-secondary text-wrap text-break">
                @if (Auth::check())
                    <a href="{{ route('user.profile', $comment->user->id) }}" class="text-decoration-none">{{ $comment->user->name }}</a>
                @else
                    <span>{{ $question->user->name }}</span>
                @endif
                {{\Carbon\Carbon::parse($comment->date)->diffForHumans()}}
            </div>
        </div>
    </div>
</article>