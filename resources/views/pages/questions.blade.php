@extends('layouts.app')

@section('title', 'Questions')
@section('content')
<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <section class="scroll-container overflow-y-scroll w-100 p-3">
        <header class="d-flex justify-content-between align-items-center p-3">
            <?= $title ?>
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
<button type="button" class="btn btn-primary rounded" id="back-top">
    <i class="bi bi-arrow-up"></i>
</button>
@endsection 