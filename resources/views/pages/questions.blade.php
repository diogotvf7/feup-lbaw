@extends('layouts.app')

@section('title', 'Questions')
@section('content')
<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <section class="overflow-y-scroll w-100 p-3">
        <header class="d-flex justify-content-between align-items-center p-3">
            <h1>
                <?php
                    use App\Models\Tag;
                    
                    $uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                    if (count($uri) == 1)
                        echo 'All Questions';
                    else if ($uri[1] == 'top')
                        echo 'Top Questions';
                    else if ($uri[1] == 'followed')
                        echo 'Followed Question';
                    else if ($uri[1] == 'tag') {
                        $tag = Tag::find($uri[2]);
                        if ($tag) {
                            echo 'Questions Tagged [' . $tag->name . ']';
                        } else {
                            echo 'Invalid Tag';
                        }
                    }
                ?>
            </h1>
            <a href="/questions/create" class="btn btn-primary">Ask Question</a>
        </header>
        <hr>
        <div id="questions-container" class="w-100">
            <div id="loader" class="invisible">Loading...</div> 
        </div>
    </section>
</div>
@endsection 