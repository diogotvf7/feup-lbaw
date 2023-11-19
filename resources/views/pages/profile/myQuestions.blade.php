@extends('layouts.profile')

@section('questions')

<div class= "body d-flex flex-column justify-content-evenly align-items-center pt-4" >
    <div class="card text-white bg-info mb-3" style=" width: 20em; max-width: 20em;" >
        <div class="card-body d-flex  align-items-center flex-column">
            <div class="profile-pic">
            </div>
            <h4 class="username">{{Auth::user()->username}} </h4>
            <div id="additional-info" class= "d-flex flex-row justify-content-around" style= "width: 100%;">
                <p>Level: {{Auth::user()->experience}} </p> <!-- todo: level calculator function -->
                <p>Kleos: {{Auth::user()->score}} </p>
            </div>
        </div>
    </div>

    <section class="card text-white bg-info mb-3" style="max-height:70vh; overflow: scroll; ">
        <div class="card-body align-items-center flex-column">
            @each('partials.questionPreview', $questions, 'question')
        </div>
    </section>
</div>

@endsection