<div class="px-4 pt-4 pb-2 mb-4 border-bottom border-3 border-secondary">
    <section id="question" class="card border-secondary mb-3">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-end px-4 pb-0">
            <h2>{{ $question->title }}</h2>
            <!-- <p>
            @foreach($question->tags as $tag)
                {{ $tag->name }}
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
            | {{ \Carbon\Carbon::parse($question->firstVersion->date)->diffForHumans() }}
        </p>  -->
            <p>Made by {{ $question->user->username }} | {{ \Carbon\Carbon::parse($question->firstVersion->date)->diffForHumans() }}</p>
            @if(auth()->check() && ($question->user->id === auth()->user()->id || Auth::user()->type === "Admin"))
            <div class="d-flex pb-2">
                @if((auth()->check() && $question->user->id === auth()->user()->id))
                <button class="btn btn-secondary my-2 my-sm-0 edit-question">Edit</button>
                <button class="btn btn-secondary my-2 my-sm-0 stop-editing d-none">Stop Editing</button>
                @endif
                <form class="px-2" method="POST" action="{{ route('question/delete') }}" onclick="return confirm('Are you sure you want to delete this question?');">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Delete</button>
                </form>
            </div>
            @endif
        </div>

        <div class="px-4 py-4">
            <span class="question-body">{{ $question->updatedVersion->body }}</span>
            <form method="POST" action="{{ route('question/edit') }}">
                {{ csrf_field() }}
                @method('PATCH')
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <input type="text" name="body" class="form-control edit-input d-none" value="{{ $question->updatedVersion->body }}">
                <button class="btn btn-primary mt-2 d-none submit-edit" type="submit">Submit</button>
            </form>
        </div>

        <div class="card-footer d-flex align-items-center px-0">
            <p class="px-4 mb-0">{{ $question->answers->count() }}
                @if($question->answers->count() === 1)
                answer
                @else
                answers
                @endif
            </p>
            <p class="px-4 mb-0">{{ $question->voteBalance() }}
                @if($question->voteBalance() === 1)
                vote
                @else
                votes
                @endif
            </p>
        </div>
    </section>
</div>