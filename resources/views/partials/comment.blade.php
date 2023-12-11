<article class="comment">
    <div class="d-flex justify-content-start gap-2 pt-2">
        <p class="pt-1">Commented {{ \Carbon\Carbon::parse($comment->date)->diffForHumans() }} by {{ $comment->user->username }}</p>
        <div class="d-flex">
        @if (auth()->check())
            @if (auth()->user()->id === $comment->user->id)
            <div class="px-2">
                <button class="edit-comment btn btn-secondary btn-sm my-2 my-sm-0">Edit</button>
            </div>
            @endif
            @if (auth()->user()->id === $comment->user->id || auth()->user()->type === "Admin")
            <form class="px-2" method="POST" action="{{ route('comment/delete') }}" onclick="return confirm('Are you sure you want to delete this comment?');">
                {{ csrf_field() }}
                @method('DELETE')
                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                <button class="btn btn-secondary btn-sm my-2 my-sm-0" type="submit">Delete</button>
            </form>
            @endif
        @endif
        </div>                    
    </div>
    <form method="POST" action="{{ route('comment/edit') }}">
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
</article>
