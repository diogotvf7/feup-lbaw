<div class="px-4 pt-2 pb-2">
    <div class="py-2 card border-secondary mb-3">
    
    @if(auth()->check() && $question->user->id !== auth()->user()->id)
        <form class="d-flex px-4 pt-4" method="POST" action="{{ route('add-answer') }}">
            {{ csrf_field() }}
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input class="form-control me-sm-2 mw-80" type="text" name="body" placeholder="Add an answer..." required>
            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Submit</button>
        </form>
    @endif

    <section id="answers" class="px-4 pt-4">
    @foreach($question->answers as $answer)
    <div class="card border-primary mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            {{ $answer->user->username }} | {{ \Carbon\Carbon::parse($answer->body->date)->diffForHumans() }}
            @if(auth()->check() && $answer->user->id === auth()->user()->id)
            <div class="d-flex">
                <button class="btn btn-secondary my-2 my-sm-0 edit-answer">Edit</button>
                <button class="btn btn-secondary my-2 my-sm-0 stop-editing d-none">Stop Editing</button>
                <form class="px-2" method="POST" action="{{ route('destroy-answer') }}">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Delete</button>
                </form>
            </div>
            @endif
        </div>
        <div class="card-body">
            <p class="card-text">
                <span class="answer-body">{{ $answer->body->body }}</span>
                <form method="POST" action="{{ route('update-answer') }}">
                    {{ csrf_field() }}
                    @method('POST')
                    <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                    <input type="text" name="body" class="form-control edit-input d-none" value="{{ $answer->body->body }}">
                    <button class="btn btn-primary mt-2 d-none submit-edit" type="submit">Submit</button>
                </form>
            </p>
        </div>
    </div>
    @endforeach
    </section>
    </div>  
</div>