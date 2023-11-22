@extends('layouts.app')

@section('title', 'Top Questions')
@section('content')

<div class="d-flex flex-fill overflow-hidden">
    <nav class="sidebar position-relative d-flex flex-column align-items-stretch bg-primary" style="min-width: 250px; max-width: 250px;">
        <ul class="list-unstyled p-0">
            <li class="py-3 px-5 sidebar-element">
                <a href="/questions?filter=top" class="nav-link">Top Questions</a>
            </li>
            @if (Auth::check())
                <li class="py-3 px-5 sidebar-element">
                    <a href="/questions?filter=followed" class="nav-link">Followed Questions</a>
                </li>
            @endif
        </ul>
    </nav>
    <section class="overflow-y-scroll w-100 p-3">
        <div id="questions-container" class="w-100">
            <div id="loader" class="invisible">Loading...</div> 
        </div>
    </section>
</div>
@endsection 