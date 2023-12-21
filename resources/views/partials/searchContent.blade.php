<div id="myTabContent" class="tab-content">
    <div id="questions" class="tab-pane fade active show w-100" role="tabpanel">
        @if (count($questions) !== 0)
            @foreach ($questions as $question)
                @include('partials.questionPreview', ['question' => $question])
                @if (!$loop->last)
                    <hr>
                @endif
            @endforeach
        @else
            <p class="text-center">No questions found</p>
        @endif
    </div>

    <div id="tags" class="tab-pane fade w-100" role="tabpanel">
        @if (count($tags) !== 0)
        @foreach ($tags as $tag)
        @include('partials.tagPreview', ['tag' => $tag])
        @endforeach
        @else
        <p class="text-center">No tags found</p>
        @endif
    </div>
</div>