<?php
$numQuestions = $tag->questions->count();
$numUsers = $tag->usersThatFollow->count();
?>

<article class="d-flex flex-column justify-content-between tag-preview p-3 m-2 border border-primary-subtle rounded" style="height: fit-content; width: 15dvw;">
    <a href="/questions/tag/1" class="badge bg-primary text-white text-decoration-none mb-3">{{$tag->name}}</a>
    <p class="">{{$tag->description}}</p>
    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row me-2">
            <p class="">{{$numQuestions}}&NonBreakingSpace;</p>
            <p> @if($numQuestions > 1)
                questions
                @else
                question
                @endif</p>
        </div>
        <div class="d-flex flex-row">
            <p>{{$numUsers}}&NonBreakingSpace;</p>
            <p> @if($numUsers > 1)
                followers
                @else
                follower
                @endif</p>
        </div>
    </div>
</article>