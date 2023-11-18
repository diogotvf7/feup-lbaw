<div class="px-4 pt-4 pb-2 mb-4 border-bottom border-3 border-secondary">    
<section id="question" class="py-2 card border-secondary mb-3" >
    <div class="card-header d-flex justify-content-between align-items-center px-4 py-0">
        <h1>{{ $question->title }}</h1>
        <p>
        @foreach($question->tags as $tag)
            {{ $tag->name }}
            @if (!$loop->last)
                ,
            @endif
        @endforeach
        | {{ \Carbon\Carbon::parse($question->body->date)->diffForHumans() }}
    </p>
        <p>Made by {{ $question->user->username }}</p>
    </div>

    <div class="px-4 pt-2">
        <p class="py-0 my-0">{{ $question->body->body }}</p>
    </div>

    <div class="d-flex pt-2">
        <p class="px-4">{{ $question->answers->count() }} 
            @if($question->answers->count() === 1)
                answer
            @else
                answers
            @endif
        </p>
        <p class="px-4">{{ $question->votes->count() }} 
            @if($question->votes->count() === 1)
                vote
            @else
                votes
            @endif
        </p>
    </div>
</section>
</div>