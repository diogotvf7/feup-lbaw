@extends('layouts.app')

@section('title', 'Top Questions')
@section('content')
<div class="d-flex flex-fill overflow-hidden">
    <nav class="sidebar position-relative d-flex flex-column align-items-stretch bg-primary" style="min-width: 250px; max-width: 250px;">
        <ul class="list-unstyled p-0">
            <li class="py-3 px-5 sidebar-element">
                <a href="#" class="nav-link">Top Questions</a>
            </li>
            <li class="py-3 px-5 sidebar-element">
                <a href="#" class="nav-link">Followed Questions</a>
            </li>
            <li class="py-3 px-5 sidebar-element">
                <a href="#" class="nav-link">Alguma coisa</a>
            </li>
        </ul>
    </nav>
    <section class="overflow-y-scroll w-100 p-3">
        <!-- Questions -->
        <div class="w-100">
            <!-- @each('partials.questionPreview', $questions, 'question') -->
            @foreach ($questions as $question)
                @include('partials.questionPreview', $question)
                @if(!$questions->isEmpty())
                    <hr class="my-4">
                @endif
            @endforeach
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $questions->links() }}
        </div>
    </section>
</div>
@endsection 