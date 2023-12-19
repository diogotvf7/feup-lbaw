<article id="answer-{{ $answer->id }}" class="answer d-flex gap-3">
    <div class="d-flex flex-column py-3 gap-2 align-items-center">
    @if (auth()->check() && auth()->user()->id !== $answer->user->id)
        <div class="answer-interactions d-flex flex-column align-items-center" style="width: 3em;" data-id="{{ $answer->id }}">
            @if (auth()->user()->id !== $answer->user->id)
                <button class="vote-button upvote {{ $vote === 'upvote' ? 'on' : 'off' }}"><i class="bi bi-caret-up-fill"></i></button>
                <p class="vote-count px-4 mb-0">{{ $answer->vote_balance }}</p>
                <button class="vote-button downvote {{ $vote === 'downvote' ? 'on' : 'off' }}"><i class="bi bi-caret-down-fill"></i></button>
            @endif
        </div>
    @endif
    @if (auth()->check() && auth()->user()->id === $answer->question->user->id)
    <form method="POST" action="{{ route('question/correctanswer') }}">
        {{ csrf_field() }}
        @method('PATCH')
        <input type="hidden" name="question_id" value="{{ $answer->question->id }}">
        <input type="hidden" name="answer_id" value="{{ $answer->id }}">
        <input type="hidden" name="mark_or_remove" value="{{ $answer->id === $answer->question->correctAnswer->id ? 'remove' : 'mark' }}">
        <button class="correct-answer {{ $answer->id === $answer->question->correctAnswer->id ? 'on' : 'off' }}" type="submit"><i class="bi bi-check-lg"></i></button>
    </form>
    @else
        @if ($answer->id === $answer->question->correctAnswer->id)
        <i class="correct-answer-visitor bi bi-check-lg"></i>
        @endif
    @endif
    </div>
    <div class="flex-grow-1 pt-3">
        <form method="POST" action="{{ route('answer/edit') }}">
            {{ csrf_field() }}
            @method('PATCH')
            <input type="hidden" name="answer_id" value="{{ $answer->id }}">
            <textarea name="body" class="answer-input form-control form-control-plaintext" minlength="20" maxlength="30000" readonly>{{ $answer->updatedVersion->body }}</textarea>
            @if ($errors->has('body'))
                <span class="text-danger">
                    {{ $errors->first('body') }}
                </span>
            @endif
            <div>
                <button class="cancel-edit-answer btn btn-secondary btn-sm mt-2 d-none" type="button">Cancel</button>
                <button class="submit-edit-answer btn btn-primary btn-sm mt-2 d-none submit-edit" type="submit">Submit</button>
            </div>
        </form>
        <div class="mx-4" id="comments-container">
            <div class="d-flex justify-content-between gap-5 py-2">
                <h5 class="m-0">
                    {{ $answer->comments->count() }}
                    @if ($answer->comments->count() != 1)
                    comments
                    @else
                    comment
                    @endif
                </h5>
                <div class="d-flex gap-5">
                    <p class="pt-1">
                        Answered {{ \Carbon\Carbon::parse($answer->firstVersion->date)->diffForHumans() }} by 
                        @if(auth()->check())
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
            @foreach ($answer->comments as $comment)
                @include('partials.comment', ['hidden' => $loop->index > 2])
            @endforeach
            <div class="d-flex">
                @if (sizeof($answer->comments) > 3)
                <button class="show-comments btn btn-link btn-sm text-decoration-none">show more comments</button>
                @endif
                @if (Auth()->check())
                <button class="show-comment-input btn btn-link btn-sm text-decoration-none">comment</button>
                @endif
            </div>
            @if (Auth()->check())
            <form id="comment-form" class="d-flex gap-3 align-items-end my-2 d-none" method="POST" action="{{ route('comment/create') }}">
                {{ csrf_field() }}
                <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                <textarea id="comment-input" name="body" class="form-control" placeholder="Write your comment here..." maxlength="30000" rows="1"></textarea>
                <button class="btn btn-primary" type="submit">Submit</button>
                <button class="cancel-comment btn btn-secondary" type="button">Cancel</button>
            </form>
            @endif
        </div>
    </div>
</article>