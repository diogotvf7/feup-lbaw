@extends('layouts.app')

@section('title', 'Top Questions')

@section('content')


<section id="top-questions">
    @each('partials.questionPreview', $questions, 'question')
</section>

@endsection