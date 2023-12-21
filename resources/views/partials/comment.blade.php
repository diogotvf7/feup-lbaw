<?php
$isAuthor = auth()->check() && $comment->user && $comment->user->id === auth()->user()->id;
$isAuthorOrAdmin = $isAuthor || (auth()->check() && auth()->user()->type === "Admin");
?>

<article class="comment {{ $hidden ? 'd-none' : '' }}">
    <div class="d-flex">

        <form method="POST" action="{{ route('comment/edit') }}" class="flex-grow-1">
            {{ csrf_field() }}
            @method('PATCH')
            <input type="hidden" name="comment_id" value="{{ $comment->id }}">
            <textarea name="body" class="comment-input form-control form-control-plaintext" minlength="20" maxlength="30000" readonly>{{ $comment->body }}</textarea>
            @if ($errors->has('body'))
            <span class="error">
                {{ $errors->first('body') }}
            </span>
            @endif
            <div>
                <button class="cancel-edit-comment btn btn-secondary btn-sm mt-2 d-none" type="button">Cancel</button>
                <button class="submit-edit-comment btn btn-primary btn-sm mt-2 d-none submit-edit" type="submit">Submit</button>
            </div>
        </form>
        <div class="d-flex flex-row align-items-center">
            <p class="m-0">
                &#8212;&NonBreakingSpace;
            </p>
            @include('partials.userPreview', ['user' => $comment->user])
            <p class="m-0">
            &NonBreakingSpace;commented {{ \Carbon\Carbon::parse($comment->date)->diffForHumans() }}
            </p>
        </div>
        <div class="d-flex">
            @if ($isAuthor)
            <div class="px-2">
                <button class="edit-comment btn btn-secondary btn-sm">
                    <i class="bi bi-pencil-fill"></i>
                </button>
            </div>
            @endif
            @if ($isAuthorOrAdmin)
            <form class="px-2" method="POST" action="{{ route('comment/delete') }}" onclick="return confirm('Are you sure you want to delete this comment?');">
                {{ csrf_field() }}
                @method('DELETE')
                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                <button class="btn btn-secondary btn-sm" type="submit">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </form>
            @endif

        </div>
    </div>
    <hr class="mt-0" />
</article>