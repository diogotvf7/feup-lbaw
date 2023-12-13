@extends('layouts.app')

@section('title', 'Questions')
@section('content')
<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <section class="overflow-y-scroll w-100 p-3">
        <header class="d-flex justify-content-between align-items-center p-3">
            <?php
                use App\Models\Tag;
                
                $uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                if (count($uri) == 1)
                    echo '<h1>All Questions</h1>';
                else if ($uri[1] == 'top')
                    echo '<h1>Top Questions</h1>';
                else if ($uri[1] == 'followed')
                    echo '<h1>Followed Question</h1>';
                else if ($uri[1] == 'tag') {
                    $tag = Tag::find($uri[2]);
                    if ($tag) {
                        echo '<div>';
                            echo '<h1>Questions Tagged [' . $tag->name . ']</h1>';
                            echo '<p class="px-3 my-0">' . $tag->description . '</p>';
                        echo '</div>';
                    } else {
                        echo '<h1>Invalid Tag</h1>';
                    }
                }
            ?>
            <a href="/questions/create" class="btn btn-primary">Ask Question</a>
        </header>
        <hr>
        <div id="questions-container" class="w-100">
            <div id="loader" class="invisible">Loading...</div> 
        </div>
    </section>
</div>
@if (session('tag-request')) 
    <div class="alert alert-dismissible alert-info position-absolute bottom-0 end-0 m-5">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ session('tag-request')[0] }}</strong> 
        {{ session('tag-request')[1] }}
    </div>
@endif
@if (session('question-create')) 
    <div class="alert alert-dismissible alert-success position-absolute bottom-0 end-0 m-5">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ session('question-create')[0] }}</strong> 
        <a href="{{ session('question-create')[1] }}" class="alert-link">Check it here</a>.
    </div>
@endif
@endsection 