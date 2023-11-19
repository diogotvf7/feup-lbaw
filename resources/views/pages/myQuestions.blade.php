@extends('layouts.app')

@section('content')
<section id="profile">
    <ul class="nav nav-tabs flex-d" style="background-color: var(--bs-blue);" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active" data-bs-toggle="tab" href="#home" aria-selected="true" role="tab">Information</a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" data-bs-toggle="tab" href="#profile" aria-selected="false" role="tab" tabindex="-1">My Questions</a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" data-bs-toggle="tab" href="#" aria-selected="false" tabindex="-1" role="tab">My Answers</a>
      </li>
    </ul>

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

        <section class="card text-white bg-info mb-3" style="max-height:80vh; overflow: scroll; ">
            <div class="card-body align-items-center flex-column">
                @each('partials.questionPreview', $questions, 'question')
            </div>
        </section>
    </div>
    </section>
@endsection

