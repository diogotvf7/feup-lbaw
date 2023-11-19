<div class="px-4 pt-4 pb-2 mb-4 border-bottom border-3 border-secondary">    
<section id="question" class="py-2 card border-secondary mb-3" >
    <div class="card-header d-flex flex-wrap justify-content-between align-items-end px-4 py-0">
        <h2>{{ $question->title }}</h2>
        <p>
            @foreach($question->tags as $tag)
                {{ $tag->name }}
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
            | {{ \Carbon\Carbon::parse($question->updatedVersion->date)->diffForHumans() }}
        </p>
        <p>Made by {{ $question->user->username }}</p>
        @if(auth()->check() && $question->user->id === auth()->user()->id)
            <div class="d-flex pb-2">
                <button class="btn btn-secondary my-2 my-sm-0 edit-question">Edit</button>
                <button class="btn btn-secondary my-2 my-sm-0 stop-editing d-none">Stop Editing</button>
                <form class="px-2" method="POST" action="{{ route('destroy-question') }}">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Delete</button>
                </form>
            </div>
        @endif
    </div>

    <div class="px-4 pt-4">
        <span class="question-body">{{ $question->updatedVersion->body }}</span>
        <form method="POST" action="{{ route('update-question') }}">
            {{ csrf_field() }}
            @method('POST')
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input type="text" name="body" class="form-control edit-input d-none" value="{{ $question->updatedVersion->body }}">
            <button class="btn btn-primary mt-2 d-none submit-edit" type="submit">Submit</button>
        </form>
    </div>

    <div class="d-flex pt-2">
        <p class="px-4">{{ $question->answers->count() }} 
            @if($question->answers->count() === 1)
                answer
            @else
                answers
            @endif
        </p>
        <p class="px-4">{{ $question->votes->count() }} 
            @if($question->votes->count() === 1)
                vote
            @else
                votes
            @endif
        </p>
    </div>
</section>
</div>