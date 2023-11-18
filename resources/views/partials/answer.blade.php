<div class="px-4 pt-2 pb-2">
    <div class="py-2 card border-secondary mb-3">
    <form class="d-flex px-4 py-4" method="POST">
        {{ csrf_field() }} 
        <input class="form-control me-sm-2 mw-80" type="text" name="answer" placeholder="Add an answer..." required>
        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Submit</button>
    </form>

    <section id="answers" class="px-4">
        @foreach($answers as $answer)
            <div class="card border-primary mb-3">
                <div class="card-header">{{ $answer->user->username }} | {{ \Carbon\Carbon::parse($answer->body->date)->diffForHumans() }}</div>
                    <div class="card-body">
                        <p class="card-text">{{ $answer->body->body }}</p>
                        <p class="card-text">
                            {{ $answer->comments->count() }}
                            @if($answer->comments->count() === 1)
                                comment
                            @else
                                comments
                            @endif
                        </p>
                    </div>
            </div>
        @endforeach
    </section>
    </div>  
</div>