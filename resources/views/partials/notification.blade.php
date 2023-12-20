<div id="notification-{{$notification->id}}" class="notification d-flex flex-row text-one-line my-1" data-id="{{$notification->id}}">
    <a href="javascript:void(0)" aria-label="Close" class="dismiss-notification btn-close-white btn-close me-1"></a>

    <p class="text-secondary me-1">{{ \Carbon\Carbon::parse($notification->date)->diffForHumans() }}</p>

    <div class="d-flex flex-row text-one-line">
        @if ($notification->type === "ANSWER")
        <a href="$notification->answer->user ? {{route('user.profile', $notification->answer->user->id)}} : ''" class="text-decoration-none" style="min-width: fit-content;"> {{ $notification->answer->user->username }}</a>
        <p>&NonBreakingSpace; answered &NonBreakingSpace;</p>
        <p class="small-screen-hide"> your &NonBreakingSpace;</p>
        <a href="{{route('question.show', $notification->answer->question->id)}}" class="text-decoration-none text-one-line" style="max-width: 30dvw;"> {{ $notification->answer->question->title }}</a>
        <p class="small-screen-hide">&NonBreakingSpace; question</p>
        @elseif ($notification->type === "UPVOTE")
        <a href="$notification->upvote->user ? {{route('user.profile', $notification->upvote->user->id)}} : ''" class="text-decoration-none" style="min-width: fit-content;"> {{ $notification->upvote->user->username }}</a>
        <p>&NonBreakingSpace; upvoted &NonBreakingSpace;</p>
        <p class="small-screen-hide">your &NonBreakingSpace;</p>
        @if ($notification->upvote->type === "QUESTION")
        <a href="{{route('question.show', $notification->upvote->question->id)}}" class="text-decoration-none text-one-line" style="max-width: 30dvw;"> {{ $notification->upvote->question->title }}</a>
        <p class="small-screen-hide">&NonBreakingSpace; question</p>
        @elseif ($notification->upvote->type === "ANSWER")
        <p class="small-screen-hide"> answer on &NonBreakingSpace;</p>
        <a href="{{route('question.show', $notification->upvote->answer->question->id)}}" class="text-decoration-none text-one-line" style="max-width: 30dvw;">{{ $notification->upvote->answer->question->title }}</a>
        @endif
        @elseif ($notification->type === "COMMENT")
        <a href="$notification->comment->user ? {{route('user.profile', $notification->comment->user->id)}} : ''" class="text-decoration-none" style="min-width: fit-content;"> {{ $notification->comment->user->username }}</a>
        <p>&NonBreakingSpace;commented&NonBreakingSpace;on&NonBreakingSpace;</p>
        <p class="small-screen-hide">your&NonBreakingSpace;</p>
        @if($notification->comment->type === "QUESTION")
        <a href="{{route('question.show', $notification->comment->question->id)}}" class="text-decoration-none text-one-line" style="max-width: 30dvw;"> {{ $notification->comment->question->title }}</a>
        <p class="small-screen-hide">&NonBreakingSpace; question</p>
        @elseif($notification->comment->type === "ANSWER")
        <p class="small-screen-hide">answer&NonBreakingSpace;on&NonBreakingSpace;</p>
        <a href="{{route('question.show', $notification->comment->answer->question->id)}}" class="text-decoration-none text-one-line" style="max-width: 30dvw;">{{ $notification->comment->answer->question->title }}</a>
        @endif
        @endif
    </div>
</div>