@extends('layouts.app')
dd($user);
@section('content')

<nav class="navbar navbar-expand-lg bg-light" data-bs-theme="light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarColor03">
            <ul class="w-100 mx-auto navbar-nav">
                <li class="nav-item flex-fill">
                    <a href=>Settings</a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="mx-auto" href=>My Questions</a>
                </li>
                <li class="nav-item flex-fill">
                    <a href=>My Answers</a>
                </li>
                <li class="nav-item flex-fill">
                    <a href=>My Comments</a>
                </li>
                <li class="nav-item flex-fill">
                    <a href=>Followed Questions</a>
                </li>
                <li class="nav-item flex-fill">
                    <a href=>Followed Tags</a>
                </li>
                <li class="nav-item flex-fill">
                    <a href=>Badges</a>
                </li>
            </ul>
        </div>
        <div>
</nav>

<div class= "main-part d-flex justify-content-evenly align-items-baseline pt-4">
    <div class="card text-white bg-info mb-3" style=" width: 20em; max-width: 20em;" >
        <div class="card-body d-flex  align-items-center flex-column">
            <div class="profile-pic">
            </div>
            <h4 class="username">{{$user->username}} </h4>
            <div id="additional-info">
                <p>level 6 </p>
                <p>badges 0 </p>
                <p>karma(forgot the name) 0 </p>
            </div>
        </div>
    </div>

    <section class="card text-white bg-info mb-3" style="width: 50em;max-width: 60em;">
        <div class="card-body align-items-center flex-column">
            @each('partials.questionPreview', $questions, 'question')
        </div>
    </section>
</div>

@endsection