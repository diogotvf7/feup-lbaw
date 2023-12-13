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