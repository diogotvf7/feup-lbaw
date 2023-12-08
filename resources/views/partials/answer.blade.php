<article class="answer d-flex gap-3">
    @if (auth()->check())
        <div class="answer-interactions d-flex flex-column align-items-center py-3" data-id="{{ $answer->id }}">
            @if (auth()->user()->id !== $answer->user->id)
                <button class="vote-button upvote {{ $vote === 'upvote' ? 'on' : 'off' }}"><i class="bi bi-caret-up-fill"></i></button>
                <p class="vote-count px-4 mb-0">{{ $answer->vote_balance }}</p>
                <button class="vote-button downvote {{ $vote === 'downvote' ? 'on' : 'off' }}"><i class="bi bi-caret-down-fill"></i></button>
            @endif
        </div>
    @endif
    <div class="flex-grow-1 pt-3">
        <form method="POST" action="{{ route('answer/edit') }}">
            {{ csrf_field() }}
            @method('PATCH')
            <input type="hidden" name="answer_id" value="{{ $answer->id }}">
            <textarea name="body" class="answer-input form-control form-control-plaintext" minlength="20" maxlength="30000" readonly>{{ $answer->updatedVersion->body }}</textarea>
            @if ($errors->has('body'))
                <span class="error">
                    {{ $errors->first('body') }}
                </span>
            @endif
            <div>
                <button class="cancel-edit-answer btn btn-secondary btn-sm mt-2 d-none" type="button">Cancel</button>
                <button class="submit-edit-answer btn btn-primary btn-sm mt-2 d-none submit-edit" type="submit">Submit</button>
            </div>
        </form>
        <div class="d-flex justify-content-between gap-5 py-2">
            <div class="d-flex gap-3">
                <p>
                    {{ $answer->comments->count() }}
                    @if ($answer->comments->count() != 1)
                    comments
                    @else
                    comment
                    @endif
                </p>
                @if ($answer->comments->count() > 0)
                <div class="px-2">
                    <button class="show-comments btn btn-secondary btn-sm my-2 my-sm-0" data-id="{{ $answer->id }}">Show comments</button>
                </div>
                @endif
            </div>
            <div class="d-flex gap-5">
                <p class="m-0">
                    Answered {{ \Carbon\Carbon::parse($answer->firstVersion->date)->diffForHumans() }} by 
                    @if(auth()->check() && ($answer->user->id === auth()->user()->id || Auth::user()->type === "Admin"))
                    <a class="text-decoration-none" href="/users/{{ $answer->user->id }}">{{ $answer->user->username }}</a>
                    @else
                    {{ $answer->user->username }}
                    @endif
                </p>
                <div class="d-flex">
                    @if (auth()->check())
                        @if (auth()->user()->id === $answer->user->id)
                        <div class="px-2">
                            <button class="edit-answer btn btn-secondary btn-sm my-2 my-sm-0">Edit</button>
                        </div>
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
        <section id="comments-container" data-id="{{ $answer->id }}">
        </section>
    </div>
</article>