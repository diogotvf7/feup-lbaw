<section>
    <header class="d-flex justify-content-between align-items-center">
        <hgroup>
            <h1 class="text-wrap text-break me-3">
                {{ $question->title }}
            </h1>
            <div class="d-flex gap-5">
                <p>
                    Asked {{ \Carbon\Carbon::parse($question->created_at)->diffForHumans() }} by 
                    @if(auth()->check())
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
                @if ($question->user->id === auth()->user()->id)
                    <button id="edit-question" class="btn btn-secondary my-2 my-sm-0">Edit</button>
                @endif
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

    @if (auth()->check())
        <div class="question-interactions d-flex flex-column align-items-center">
            <button class="vote-button upvote {{ $vote === 'upvote' ? 'on' : 'off' }}"><i class="bi bi-caret-up-fill"></i></button>
            <p class="vote-count px-4 mb-0">{{ $question->voteBalance() }}</p>
            <button class="vote-button downvote {{ $vote === 'downvote' ? 'on' : 'off' }}"><i class="bi bi-caret-down-fill"></i></button>
            @if ($follow)
                <button id = "follow-button" class="vote-button my-2 on"><i class="bi bi-bookmark-fill"></i></button>
            @else 
                <button id = "follow-button" class="vote-button my-2 off"><i class="bi bi-bookmark-fill"></i></button>
            @endif
        </div>
    @endif
        <form method="POST" class="flex-grow-1" action="{{ route('question/edit') }}">
            {{ csrf_field() }}
            @method('PATCH')
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <textarea id="question-input" name="body" class="form-control form-control-plaintext" minlength="20" maxlength="30000" readonly>{{ $question->updatedVersion->body }}</textarea>
            @if ($errors->has('body'))
                <span class="text-danger">
                    {{ $errors->first('body') }}
                </span>
            @endif
            <div>
                <button id="cancel-edit-question" class="btn btn-secondary mt-2 d-none" type="button">Cancel</button>
                <button id="submit-edit-question" class="btn btn-primary mt-2 d-none submit-edit" type="submit">Submit</button>
            </div>
        </form>
    </div>
    <div class="d-flex gap-3">
        <p class="pt-1">
            {{ $question->comments->count() }}
            @if ($question->comments->count() != 1)
            comments
            @else
            comment
            @endif
        </p>
        @if ($question->comments->count() > 0)
        <div class="px-2">
            <button class="show-comments btn btn-secondary btn-sm my-2 my-sm-0" data-question-id="{{ $question->id }}">Show comments</button>
        </div>
        @endif
    </div>
    <section class="ms-4" id="comments-container" data-question-id="{{ $question->id }}" style="display:none">
    </section>
    @if (Auth()->check())
    <form id="comment-form" class="d-flex gap-3 align-items-end my-2" method="POST" action="{{ route('comment/create') }}">
        {{ csrf_field() }}
        <input type="hidden" name="question_id" value="{{ $question->id }}">
        <textarea id="comment-input" name="body" class="form-control" placeholder="Write your comment here..." maxlength="30000" rows="1"></textarea>
        <button id="submit-comment" class="btn btn-primary" type="submit">Submit</button>
    </form>
    @endif
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