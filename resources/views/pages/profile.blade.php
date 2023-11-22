@extends('layouts.app')

@section('content')

<section id="profile">

    <ul class="nav nav-tabs flex-d" style="background-color: var(--bs-blue);" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#informations" aria-selected=true role="tab">Information</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#questions" aria-selected="false" role="tab" tabindex="-1">{{Auth::user()->id === $user->id ? "My Questions" : "Questions"}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#answers" aria-selected="false" role="tab" tabindex="-1">{{Auth::user()->id === $user->id ? "My Answers" : "Answers"}}</a>
        </li>
    </ul>

    <div id="myTabContent" class="tab-content" class="d-flex flex-row align-items-center" style="height: 100%;">
        <div class="tab-pane fade active show" id="informations" role="tabpanel" style="height: 100%;">
            <div class="d-flex justify-content-around align-items-center" style="height: 100%;">
                @include('partials.profileCard')
                @if (Auth::user()->id === $user->id)
                        @include('partials.editUser')
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="questions" role="tabpanel">

            <div class="body d-flex flex-column justify-content-evenly align-items-center pt-4">
                <div class="card text-white bg-info mb-3" style=" width: 20em; max-width: 20em;">
                    <div class="card-body d-flex  align-items-center flex-column">
                        <div class="profile-pic">
                        </div>
                        <h4 class="username">{{$user->username}} </h4>
                        <div id="additional-info" class="d-flex flex-row justify-content-around" style="width: 100%;">
                            <p>Level: {{$user->experience}} </p>
                            <p>Kleos: {{$user->score}} </p>
                        </div>
                    </div>
                </div>

                <section class="card mb-3" style="max-height:70vh; overflow: scroll; ">
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

        <div class="tab-pane fade" id="answers" role="tabpanel">

            <div class="body d-flex flex-column justify-content-evenly align-items-center pt-4">
                <div class="card text-white bg-info mb-3" style=" width: 20em; max-width: 20em;">
                    <div class="card-body d-flex  align-items-center flex-column">
                        <div class="profile-pic">
                        </div>
                        <h4 class="username">{{$user->username}} </h4>
                        <div id="additional-info" class="d-flex flex-row justify-content-around" style="width: 100%;">
                            <p>Level: {{$user->experience}} </p>
                            <p>Kleos: {{$user->score}} </p>
                        </div>
                    </div>
                </div>

                <section class="card mb-3" style="max-height:70vh; overflow: scroll; ">
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
    </div>

</section>
@endsection