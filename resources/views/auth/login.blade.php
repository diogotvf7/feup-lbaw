@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg bg-light" data-bs-theme="light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarColor03">
            <ul class="navbar-nav me-auto">
                <li class= "nav-item">
                    <a href=>Settings</a>
                </li>
                <li class= "nav-item">
                    <a href=>My Questions</a>
                </li>
                <li class= "nav-item">
                    <a href=>My Answers</a>
                </li>
                <li class= "nav-item">
                    <a href=>My Comments</a>
                </li>
                <li class= "nav-item">
                    <a href=>Follwed Questions</a>
                </li>
                <li class= "nav-item">
                    <a href=>Followed Tags</a>
                </li>
                <li class= "nav-item">
                    <a href=>Badges</a>
                </li>
            </ul>
        </div>
    <div>
</nav>

<div>
    <div class="profile-pic">
    </div>
    <h1 class="username"> Username </h1>
    <div id="additional-info"></div>
</div>
<section>
    <p> Hello </p>
    <p> Hello again </p>
    <p> Hello again again </p>
</section>


@endsection
