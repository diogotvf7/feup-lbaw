@extends('layouts.app')

@section('title', 'Questions')
@section('content')
<div class="d-flex flex-fill overflow-hidden">
    <nav class="sidebar position-relative d-flex flex-column align-items-stretch bg-primary" style="min-width: 250px; max-width: 250px;">
        <ul class="list-unstyled p-0">
            <li class="py-3 px-5 sidebar-element">
                <a href="{{ route('questions.top') }}" class="nav-link">Top Questions</a>
            </li>
            <li class="py-3 px-5 sidebar-element">
                <a href="{{ route('questions') }}" class="nav-link">All Questions</a>
            </li>
            @if (Auth::check())
                <li class="py-3 px-5 sidebar-element">
                    <a href="{{ route('questions.followed') }}" class="nav-link">Followed Questions</a>
                </li>
            @endif
        </ul>
    </nav>
    <section class="overflow-y-scroll w-100 p-3">
        <header class="d-flex justify-content-between align-items-center p-3">
            <h1>
                <?= ucfirst(isset($_GET['filter']) ? $_GET['filter'] : 'all') . ' Questions' ?>
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