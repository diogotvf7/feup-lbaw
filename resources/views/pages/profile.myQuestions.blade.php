<!--
@extends('layouts.app')

@section('content')
<nav id="Nav Bar">
    <a href=>Settings</a>
    <a href=>My Questions</a>
    <a href=>My Answers</a>
    <a href=>My Comments</a>
    <a href=>Follwed Questions</a>
    <a href=>Followed Tags</a>
    <a href=>Badges</a>
</nav>

<div>
    <div class="profile-pic">
    </div>
    <h1 class="username"> Username </h1>
    <div id="additional-info"></div>

</div>
<section>
    @each('partials.questionPreview',$question,'question')
</section> 


@endsection -->

@extends('layouts.app')

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

<div class= "main-part d-flex justify-content-between align-items-center">
    <div class="card text-white bg-info mb-3" style="max-width: 20em;" >
        <div class="card-body d-flex  align-items-center flex-column">
            <div class="profile-pic">
            </div>
            <h4 class="username"> Username </h4>
            <div id="additional-info">
                <p>level 6 </p>
                <p>badges 0 </p>
                <p>karma(forgot the name) 0 </p>
            </div>
        </div>
    </div>

    <section class="card text-white bg-info mb-3" style="max-width: 60em;">
        <div class="card-body align-items-center flex-column">
            <p> Hello </p>
            <p> Hello again </p>
            <p> Hello again again </p>
        </div>
    </section>
</div>

@endsection