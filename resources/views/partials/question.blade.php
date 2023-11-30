<section class="">
    <header class="d-flex justify-content-between align-items-center">
        <hgroup>
            <h1>
                {{ $question->title }}
            </h1>
            <div class="d-flex gap-5">
                <p>
                    Asked {{ \Carbon\Carbon::parse($question->created_at)->diffForHumans() }} by 
                    @if(auth()->check() && ($question->user->id === auth()->user()->id || Auth::user()->type === "Admin"))
                    <a class="text-decoration-none" href="/users/{{ $question->user->id }}">{{ $question->user->username }}</a>
                    @else
                    {{ $question->user->username }}
                    @endif
                </p>
                @if ($question->contentVersions()->count() > 1)
                <p>
                    Last Edited {{ \Carbon\Carbon::parse($question->updated_at)->diffForHumans() }}
                </p>
                @endif
            </div>
        </hgroup>
        <div class="d-flex">
            @if (auth()->check())
                <button id="edit-question" class="btn btn-secondary my-2 my-sm-0">Edit</button>
                @if (auth()->user()->id === $question->user->id || auth()->user()->type === "Admin")
                <form class="px-2" method="POST" action="{{ route('question/delete') }}" onclick="return confirm('Are you sure you want to delete this question?');">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Delete</button>
                </form>
                @endif
            @endif
        </div>
    </header>
    <hr>
    <div class="d-flex gap-3 my-3">
        <div class="question-interactions d-flex flex-column align-items-center">
            <button class="vote-button upvote {{ $vote === 'upvote' ? 'on' : 'off' }}"><i class="bi bi-caret-up-fill"></i></button>
            <p class="vote-count px-4 mb-0">{{ $question->voteBalance() }}</p>
            <button class="vote-button downvote {{ $vote === 'downvote' ? 'on' : 'off' }}"><i class="bi bi-caret-down-fill"></i></button>
            @if ($follow)
                <button class="vote-button on my-2"><i class="bi bi-bookmark-fill"></i></button>
            @else 
                <button class="vote-button off my-2"><i class="bi bi-bookmark"></i></button>
            @endif
        </div>
        <form method="POST" class="flex-grow-1" action="{{ route('question/edit') }}">
            {{ csrf_field() }}
            @method('PATCH')
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <textarea id="question-input" name="body" class="form-control form-control-plaintext" minlength="20" maxlength="30000" readonly>{{ $question->updatedVersion->body }}</textarea>
            <div>
                <button id="cancel-edit-question" class="btn btn-secondary mt-2 d-none" type="button">Cancel</button>
                <button id="submit-edit-question" class="btn btn-primary mt-2 d-none submit-edit" type="submit">Submit</button>
            </div>
        </form>
    </div>
    <footer class="d-flex justify-content-between align-items-center pb-2">
        <h3>
            {{ $question->answers->count() }}
            @if ($question->answers->count() != 1)
            answers
            @else
            answer
            @endif
        </h3>
        <div class="d-flex gap-2 align-items-center">
            <p class="text-nowrap m-0">Sorted by:</p>
            <label for="answers-sort" class="form-label mt-4"></label>
            <select id="answers-sort" class="form-select" value="score">
                <option value="votes">Highest score (default)</option>
                <option value="newest">Date modified (newest first)</option>
                <option value="oldest">Date created (oldest first)</option>
            </select>
        </div>
    </footer>
</section>