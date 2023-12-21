<?php
$canInteract = (auth()->check() && $question->user && auth()->user()->id !== $question->user->id);
$isAuthor = auth()->check() && $question->user && $question->user->id === auth()->user()->id;
$isAuthorOrAdmin = $isAuthor || (auth()->check() && auth()->user()->type === "Admin");
?>

<section>
    <header class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="text-wrap text-break me-3">
                {{ $question->title }}
            </h2>
            <div class="d-flex flex-row align-items-center mb-2">
                <p class="my-0 me-2">Asked {{ \Carbon\Carbon::parse($question->firstVersion->date)->diffForHumans() }} by</p>
                @include('partials.userPreview', ['user' => $question->user])
                <p class="ms-2 m-0">
                    @if ($question->contentVersions()->count() > 1)
                    (Last Edited {{ \Carbon\Carbon::parse($question->updatedVersion->date)->diffForHumans() }})
                </p>
                @endif
            </div>
        </div>
        <div class="d-flex">
            @if ($isAuthor)
            <button id="edit-question" class="btn btn-secondary my-2 my-sm-0">Edit</button>
            @endif
            @if ($isAuthorOrAdmin)
            <form class="px-2" method="POST" action="{{ route('question/delete') }}" onclick="return confirm('Are you sure you want to delete this question?');">
                {{ csrf_field() }}
                @method('DELETE')
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Delete</button>
            </form>
            @endif
        </div>
    </header>
    <input form="questionForm" id="tag-input" type="text" name="tags" class="form-control mb-2" readonly>
    <hr class="mt-0">
    <div class="d-flex gap-3 my-3">
        <div id="question-interactions" class="d-flex flex-column align-items-center">
            <button class="interaction-button upvote {{ $vote === 'upvote' ? 'on' : 'off' }}" {{ $canInteract ? '' : 'disabled' }}><i class="bi bi-caret-up-fill"></i></button>
            <p class="vote-count px-4 mb-0">{{ $question->voteBalance() }}</p>
            <button class="interaction-button downvote {{ $vote === 'downvote' ? 'on' : 'off' }}" {{ $canInteract ? '' : 'disabled' }}><i class="bi bi-caret-down-fill"></i></button>
            @if ($follow)
            <button id="follow-button" class="interaction-button my-2 on"><i class="bi bi-bookmark-fill"></i></button>
            @else
            <button id="follow-button" class="interaction-button my-2 off" {{ $canInteract ? '' : 'disabled' }}><i class="bi bi-bookmark-fill"></i></button>
            @endif
        </div>
        <form id="questionForm" method="POST" class="flex-grow-1" action="{{ route('question/edit') }}">
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
    <div class="mx-4 mb-3 comments-container">
        <h4>
            {{ $question->comments->count() }}
            @if ($question->comments->count() != 1)
            comments
            @else
            comment
            @endif
        </h4>
        @foreach ($question->comments as $comment)
        @include('partials.comment', ['hidden' => $loop->index > 2])
        @endforeach
        <div class="d-flex">
            @if (sizeof($question->comments) > 3)
            <button class="show-comments btn btn-link btn-sm text-decoration-none">show more comments</button>
            @endif
            @if (Auth()->check())
            <button class="show-comment-input btn btn-link btn-sm text-decoration-none">comment</button>
            @endif
        </div>
        @if (Auth()->check())
        <form class="d-flex gap-3 align-items-end my-2 d-none" method="POST" action="{{ route('comment/create') }}">
            {{ csrf_field() }}
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <textarea id="comment-input" name="body" class="form-control" placeholder="Write your comment here..." maxlength="30000" rows="1"></textarea>
            <button class="btn btn-primary" type="submit">Submit</button>
            <button class="cancel-comment btn btn-secondary" type="button">Cancel</button>
        </form>
        @endif
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
            <select id="answers-sort" class="form-select">
                <option value="votes">Highest score (default)</option>
                <option value="newest">Date modified (newest first)</option>
                <option value="oldest">Date created (oldest first)</option>
            </select>
        </div>
    </footer>
</section>