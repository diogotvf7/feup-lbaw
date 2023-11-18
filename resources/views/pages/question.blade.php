@extends('layouts.app')

@section('content')

<section id="question">
    @include('partials.question', ['question' => $question])
</section>
<section id="answers">
    @include('partials.answer', ['question' => $question])
</section>

@endsection
