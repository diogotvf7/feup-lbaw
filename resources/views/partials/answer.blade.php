<article class="answer d-flex gap-3">
    <div class="answer-interactions d-flex flex-column align-items-center py-3" data-id="{{ $answer->id }}">
        <button class="vote-button upvote {{ $vote === 'upvote' ? 'on' : 'off' }}"><i class="bi bi-caret-up-fill"></i></button>
        <p class="vote-count px-4 mb-0">{{ $answer->vote_balance }}</p>
        <button class="vote-button downvote {{ $vote === 'downvote' ? 'on' : 'off' }}"><i class="bi bi-caret-down-fill"></i></button>
    </div>
    <div class="flex-grow-1 pt-3">
        <form method="POST" action="{{ route('answer/edit') }}">
            {{ csrf_field() }}
            @method('PATCH')
            <input type="hidden" name="answer_id" value="{{ $answer->id }}">
            <textarea name="body" class="answer-input form-control form-control-plaintext" minlength="20" maxlength="30000" readonly>{{ $answer->updatedVersion->body }}</textarea>
            <div>
                <button class="cancel-edit-answer btn btn-secondary btn-sm mt-2 d-none" type="button">Cancel</button>
                <button class="submit-edit-answer btn btn-primary btn-sm mt-2 d-none submit-edit" type="submit">Submit</button>
            </div>
        </form>
        <div class="d-flex justify-content-end gap-5 align-content-end py-2">
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
                    <button class="edit-answer btn btn-secondary btn-sm my-2 my-sm-0">Edit</button>
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