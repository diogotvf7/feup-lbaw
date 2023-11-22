<article class="question-prev card border-primary my-4" data-id="{{$question->id}}">
    <a href="/questions/{{ $question->id }}">
        <header class="card-header">
            <h5>{{$question->user->username ?? 'User Removed'}}</h5>
        </header>
        <div class="card-body">
            <h4 class="card-title">
                {{$question->title}}
            </h4>
            <div class="votes">
                {{$question->voteBalance()}}
            </div>
            <p class="card-text">
                {{$question->updatedVersion->body}}
            </p>
        </div>

        <div class="card-footer text-muted">
            {{ \Carbon\Carbon::parse($question->firstVersion->date)->diffForHumans() }}
        </div>
    </a>
</article> 
