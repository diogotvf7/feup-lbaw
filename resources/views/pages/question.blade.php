@extends('layouts.app')

@section('content')
<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <section class="overflow-y-scroll w-100 p-5">
        @include('partials.question', ['question' => $question])
        @include('partials.answerSection', ['question' => $question])
    </section>
</div>
@endsection
