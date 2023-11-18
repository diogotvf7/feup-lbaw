@extends('layouts.app')

@section('content')
<div class="px-4 pt-4 pb-2 mb-4 border-bottom border-3 border-secondary">    
<section id="question" class="py-2 card border-secondary mb-3" >
    <div class="card-header d-flex justify-content-between align-items-center px-4 py-0">
        <h1>Title</h1>
        <p>Finances | 4h ago</p>
        <p>Made by a user</p>
    </div>

    <div class="px-4 pt-2">
        <p class="py-0 my-0">Description of the question</p>
        <p class="py-0 my-0">Description of the question</p>
        <p class="py-0 my-0">Description of the question</p>
    </div>

    <div class="d-flex pt-2">
        <p class="px-4">5 answers</p>
        <p class="px-4">24 votes</p>
    </div>
    </section>
</div>
<div class="px-4 pt-2 pb-2">
    <div class="py-2 card border-secondary mb-3">
    <form class="d-flex px-4 py-4" method="POST">
        {{ csrf_field() }} 
        <input class="form-control me-sm-2 mw-80" type="text" name="answer" placeholder="Add an answer..." required>
        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Submit</button>
    </form>

    <section id="answers" class="px-4">
        <div class="card border-primary mb-3">
            <div class="card-header">User | 3h ago</div>
                <div class="card-body">
                    <p class="card-text">Answer</p>
                </div>
        </div>
        <div class="card border-primary mb-3">
            <div class="card-header">User | 3h ago</div>
                <div class="card-body">
                    <p class="card-text">Answer</p>
                </div>
        </div>
        <div class="card border-primary mb-3">
            <div class="card-header">User | 3h ago</div>
                <div class="card-body">
                    <p class="card-text">Answer</p>
                </div>
        </div>
    </section> 
</div>  
</div>

@endsection

<!--
@extends('layouts.app')

@section('content')
    <section id="question">
        @include('partials.question', ['question' => $question])
    </section>
    
    <form method="POST" action="{{ route('answers.store') }}">
        {{ csrf_field() }}    
        <input id="answer" type="text" name="answer" placeholder="Add an answer..." required>
        <button type="submit">Submit</button>
    </form>

    <section id="answers">
        @foreach ($answers as $answer)
            @include('partials.answer', ['answer' => $answer])
        @endforeach
    </section>    
@endsection
