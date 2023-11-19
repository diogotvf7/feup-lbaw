<div class="px-4 pt-2 pb-2">
    <div class="py-2 card border-secondary mb-3">
    
    <form class="d-flex px-4 py-4" method="POST" action="{{ route('add-answer') }}">
        {{ csrf_field() }}
        <input type="hidden" name="question_id" value="{{ $question->id }}">
        <input class="form-control me-sm-2 mw-80" type="text" name="body" placeholder="Add an answer..." required>
        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Submit</button>
    </form>

    <section id="answers" class="px-4">
        @foreach($question->answers as $answer)
            <div class="card border-primary mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">{{ $answer->user->username }} | {{ \Carbon\Carbon::parse($answer->body->date)->diffForHumans() }}
                @if(auth()->check() && $answer->user->id === auth()->user()->id)
                <div class="d-flex">    
                    <form class="px-2" method="POST">
                        {{ csrf_field() }}
                        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Edit</button>
                    </form>
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
                        <p class="card-text">{{ $answer->body->body }}</p>
                        <!--
                        <p class="card-text">
                            {{ $answer->comments->count() }}
                            @if($answer->comments->count() === 1)
                                comment
                            @else
                                comments
                            @endif
                        </p>
                        -->
                    </div>
            </div>
        @endforeach
    </section>
    </div>  
</div>