@extends('layouts.app')

@section('content')
<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <section class="overflow-y-scroll w-100 p-5">
        @include('partials.question', ['question' => $question])
        <hr class="m-0">
        <section id="answers-container">
        </section>
        @if (Auth()->check() && Auth()->user()->id !== $question->user->id)
        <form id="answer-form" class="d-flex flex-column gap-3 mt-3" method="POST" action="{{ route('answer/create') }}">
            {{ csrf_field() }}
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <textarea id="answer-input" name="body" class="form-control" placeholder="Write your answer here..." required></textarea>
            <button id="submit-answer" class="btn btn-primary" type="submit">Submit</button>
        </form>
        @endif
    </section>
</div>
@endsection
