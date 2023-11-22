@extends('layouts.app')

@section('title', 'Top Questions')

@section('content')


<section id="top-questions" class="m-5">
    @each('partials.questionPreview', $questions, 'question')
    <div class="d-flex justify-content-center ">
        {{ $questions->links() }}
    </div>
</section>
@endsection 
