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



<nav class="navbar navbar-expand-lg bg-light" data-bs-theme="light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor03">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="#">Home
            <span class="visually-hidden">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
          </div>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-sm-2" type="search" placeholder="Search">
        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

