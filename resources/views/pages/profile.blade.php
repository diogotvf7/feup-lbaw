@extends('layouts.app')

@section('content')

<section id="profile" class="scroll-container" style="overflow: scroll;">

    <ul class="nav nav-tabs d-flex" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#informations" aria-selected=true role="tab">Information</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#questions" aria-selected="false" role="tab" tabindex="-1">{{Auth::user()->id === $user->id ? "My Questions" : "Questions"}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#answers" aria-selected="false" role="tab" tabindex="-1">{{Auth::user()->id === $user->id ? "My Answers" : "Answers"}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#comments" aria-selected="false" role="tab" tabindex="-1">{{Auth::user()->id === $user->id ? "My Comments" : "Comments"}}</a>
        </li>
    </ul>

    <div id="myTabContent" class="tab-content">

        <div id="informations" class="tab-pane fade active show" role="tabpanel">
            <div class="d-flex flex-column">
                <div class="d-flex flex-column flex-lg-row justify-content-around align-items-center">
                <div class="profile">
                    <h3>Profile</h3>
                    <h4>&#64;{{ $user->username }}</h4>
                    <img src="{{ $user->getProfileImage() }}">
                </div>
                    @include('partials.profileCard')
                    @if (Auth::user()->id === $user->id)
                            @include('partials.editUser')
                    @endif
                </div>
                @if (Auth::user()->id === $user->id)
                <div id= "bottom-tab" style= "margin: 13vh 3vh 2vh; padding: 0vh 4vh;" > 
                    <form class="d-inline-block" action="{{ route('user.destroy', $user->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button id="delete-button" type="submit" class="btn btn-primary d-flex justify-content-center align-items-center" style="min-width:3vh;width:7vh;min-height:7vh;height:3vh;" onclick="return confirm('Are you sure you want to delete your account?')" aria-label="Delete Account">
                            <i class="bi bi-trash3-fill" style="font-size:3vh;"></i>
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <div id="questions" role="tabpanel" class="tab-pane fade">
            <div class="d-flex flex-column justify-content-evenly align-items-center pt-4">
                <div class="card mb-3 w-25">
                    <div class="card-body d-flex align-items-center flex-column">
                        <div class="profile-pic">
                        </div>
                        <h4 class="username">{{$user->username}} questions</h4>
                        <div id="additional-info" class="d-flex flex-row justify-content-around" style="width: 100%;">
                            <p class="pe-3">Level {{$user->experience}}</p>
                            <p>Kleos {{$user->score}}</p>
                        </div>
                    </div>
                </div>

                <section class="card mb-3 w-75" style="max-height: 65vh; overflow: scroll;">
                    <div class="card-body align-items-center flex-column">
                        @if(count($user->questions)=== 0)
                        <h4>User has no questions</h4>
                        @else
                            @foreach ($user->questions as $question)
                                @include('partials.questionPreview', ['question' => $question])
                                @if (!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </section>
            </div>
        </div>

        <div id="answers" role="tabpanel" class="tab-pane fade">
            <div class="d-flex flex-column justify-content-evenly align-items-center pt-4">
                <div class="card mb-3 w-25">
                    <div class="card-body d-flex align-items-center flex-column">
                        <div class="profile-pic">
                        </div>
                        <h4 class="username">{{$user->username}} answers</h4>
                        <div id="additional-info" class="d-flex flex-row justify-content-around" style="width: 100%;">
                            <p class="pe-3">Level {{$user->experience}} </p>
                            <p>Kleos {{$user->score}} </p>
                        </div>
                    </div>
                </div>

                <section class="card mb-3 w-75" style="max-height:65vh; overflow: scroll; ">
                    <div class="card-body align-items-center flex-column">
                        @if (count($user->answers)=== 0)
                        <h4>User has no answers</h4>
                        @else
                            @foreach ($user->answers as $answer)
                                @include('partials.answerPreview', ['answer' => $answer])
                                @if (!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </section>
            </div>
        </div>

        <div id="comments" role="tabpanel" class="tab-pane fade">
            <div class="d-flex flex-column justify-content-evenly align-items-center pt-4">
                <div class="card mb-3 w-25">
                    <div class="card-body d-flex align-items-center flex-column">
                        <div class="profile-pic">
                        </div>
                        <h4 class="username">{{$user->username}} comments</h4>
                        <div id="additional-info" class="d-flex flex-row justify-content-around" style="width: 100%;">
                            <p class="pe-3">Level {{$user->experience}} </p>
                            <p>Kleos {{$user->score}} </p>
                        </div>
                    </div>
                </div>

                <section class="card mb-3 w-75" style="max-height:65vh; overflow: scroll; ">
                    <div class="card-body align-items-center flex-column">
                        @if (count($user->comments)=== 0)
                        <h4>User has no comments</h4>
                        @else
                            @foreach ($user->comments as $comment)
                                @include('partials.commentPreview', ['comment' => $comment])
                                @if (!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<button type="button" class="btn btn-primary rounded" id="back-top">
    <i class="bi bi-arrow-up"></i>
</button>
@if (session('success')) 
    <div class="alert alert-dismissible alert-success position-absolute bottom-0 end-0 m-5">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ session('success')[0] }}</strong>
    </div>
@endif
@endsection