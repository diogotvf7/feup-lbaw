@extends('layouts.app')

@section('title', 'Tags')
@section('content')
<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <section class="overflow-y-scroll w-100 p-3">
        <header class="d-flex justify-content-between align-items-center p-3">
            <h1>
                Tags
            </h1>
        </header>
        <hr>
        <div id="tags-container" class="d-grid gap-1">
        </div>
        <div id="loader" class="invisible">Loading...</div>
    </section>
</div>
@endsection 


<?php 
// <article class="d-flex flex-column justify-content-between tag-preview p-3 m-1 border border-primary-subtle rounded">
//     <h2 class="badge bg-primary">
//         <a href="/tags/{{ $tag->id }}" class="text-reset text-decoration-none">{{ $tag->name }}</a>
//     </h2>
//     <p>{{ $tag->description }}</p>
//     <div class="d-flex justify-content-between">
//         <p>
//             {{ $tag->questions->count() }} questions
//         </p>
//         <p>
//             {{ $tag->usersThatFollow->count() }} followers
//         </p>
//     </div>
// </article>
?>