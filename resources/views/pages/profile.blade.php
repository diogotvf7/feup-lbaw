@extends('layouts.app')

@section('content')

<section id="profile" class="scroll-container" style="overflow: scroll;">

    <ul class="nav nav-tabs d-flex justify-content-center" role="tablist">
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

        <div class="tab-pane fade active show" id="informations" role="tabpanel">
            <div class="d-flex flex-column flex-lg-row justify-content-around align-items-center">
                @include('partials.profileCard')
                @if (Auth::user()->id === $user->id)
                        @include('partials.editUser')
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

                <section class="card mb-3 w-75" style="overflow: scroll;">
                    <div class="card-body">
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

                <section class="card mb-3 w-75" style="max-height:70vh; overflow: scroll; ">
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

                <section class="card mb-3 w-75" style="max-height:70vh; overflow: scroll; ">
                    <div class="card-body align-items-center flex-column">
                        @if (count($user->comments)=== 0)
                        <h4>User has no answers</h4>
                        @else
                            @foreach ($user->comments as $comment)
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