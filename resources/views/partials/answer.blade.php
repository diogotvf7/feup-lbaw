<article class="d-flex gap-3">
    <div class="d-flex flex-column align-items-center py-3">
        <button class="question-button"><i class="bi bi-caret-up-fill"></i></button>
        <p class="px-4 mb-0">{{ $answer->voteBalance() }}</p>
        <button class="question-button"><i class="bi bi-caret-down-fill"></i></button>
    </div>
    <div class="flex-grow-1 pt-3">
        <form method="POST" action="{{ route('answer/edit') }}">
            {{ csrf_field() }}
            @method('PATCH')
            <input type="hidden" name="answer_id" value="{{ $answer->id }}">
            <textarea id="answer-input" name="body" class="form-control form-control-plaintext" readonly>{{ $answer->updatedVersion->body }}</textarea>
            <div>
                <button id="cancel-edit-answer" class="btn btn-secondary btn-sm mt-2 d-none" type="button">Cancel</button>
                <button id="submit-edit-answer" class="btn btn-primary btn-sm mt-2 d-none submit-edit" type="submit">Submit</button>
            </div>
        </form>
        <div class="d-flex justify-content-end gap-5 align-content-end py-2">
            <p class="m-0">
                Asked {{ \Carbon\Carbon::parse($question->firstVersion->date)->diffForHumans() }} by 
                @if(auth()->check() && ($question->user->id === auth()->user()->id || Auth::user()->type === "Admin"))
                <a class="text-decoration-none" href="/users/{{ $question->user->id }}">{{ $question->user->username }}</a>
                @else
                {{ $question->user->username }}
                @endif
            </p>
            <div class="d-flex">
                @if (auth()->check())
                    @if (auth()->user()->id === $answer->user->id)
                    <button id="edit-answer" class="btn btn-secondary btn-sm my-2 my-sm-0">Edit</button>
                    @endif
                    @if (auth()->user()->id === $answer->user->id || auth()->user()->type === "Admin")
                    <form class="px-2" method="POST" action="{{ route('answer/delete') }}" onclick="return confirm('Are you sure you want to delete this answer?');">
                        {{ csrf_field() }}
                        @method('DELETE')
                        <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                        <button class="btn btn-secondary btn-sm my-2 my-sm-0" type="submit">Delete</button>
                    </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</article>