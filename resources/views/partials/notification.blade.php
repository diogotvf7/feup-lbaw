<div id="notification-{{$notification->id}}" class="d-flex flex-row text-one-line">
    <p class="text-secondary me-1">{{ \Carbon\Carbon::parse($notification->date)->diffForHumans() }}</p>

    <div class="d-flex flex-row text-one-line">
        @if ($notification->type === "ANSWER")
        <a href="{{route('user.profile', $notification->answer->user->id)}}" class="text-decoration-none" style="min-width: fit-content;"> {{ $notification->answer->user->username }}</a>
        <p>&NonBreakingSpace; answered &NonBreakingSpace;</p>
        <p class="small-screen-hide"> your &NonBreakingSpace;</p>
        <a href="{{route('question.show', $notification->answer->question->id)}}" class="text-decoration-none text-one-line" style="max-width: 30dvw;"> {{ $notification->answer->question->title }}</a>
        <p class="small-screen-hide">&NonBreakingSpace; question</p>
        @endif

        @if ($notification->type === "UPVOTE")
        <a href="{{route('user.profile', $notification->upvote->user->id)}}" class="text-decoration-none" style="min-width: fit-content;"> {{ $notification->upvote->user->username }}</a>
        <p>&NonBreakingSpace; upvoted &NonBreakingSpace;</p>
        <p class="small-screen-hide">your &NonBreakingSpace;</p>
        @if ($notification->upvote->type === "QUESTION")
        <a href="{{route('question.show', $notification->upvote->question->id)}}" class="text-decoration-none text-one-line" style="max-width: 30dvw;"> {{ $notification->upvote->question->title }}</a>
        <p class="small-screen-hide">&NonBreakingSpace; question</p>
        @elseif ($notification->upvote->type === "ANSWER")
        <a href="{{route('question.show', $notification->upvote->answer->question->id)}}" class="text-decoration-none text-one-line" style="max-width: 30dvw;">{{ $notification->upvote->answer->updatedVersion->body }}</a>
        <p class="small-screen-hide">&NonBreakingSpace; answer</p>
        @else
        <a href="{{route('question.show', $notification->upvote->comment->question->id)}}" class="text-decoration-none text-one-line" style="max-width: 30dvw;">{{ $notification->upvote->comment->body }}</a>
        <p class="small-screen-hide">&NonBreakingSpace; comment</p>
        @endif
        @endif
    </div>
</div>