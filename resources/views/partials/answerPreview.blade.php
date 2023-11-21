<article class="answer-prev card border-primary my-4" data-id="{{$answer->id}}">
    <a href="/answers/{{ $answer->id }}">
        <header class="card-header d-flex  justify-content-between">
            <h5>{{$answer->question->title}}</h5>
            <h5>{{$answer->user->username ?? 'User Removed'}}</h5>
        </header>
        <div class="card-body">
            <div class="votes">
                {{$answer->voteBalance()}}
            </div>
            <p class="card-text">
                {{$answer->updatedVersion->body}}
            </p>
        </div>

        <div class="card-footer text-muted">
            {{ \Carbon\Carbon::parse($answer->firstVersion->date)->diffForHumans() }}
        </div>
    </a>
</article>