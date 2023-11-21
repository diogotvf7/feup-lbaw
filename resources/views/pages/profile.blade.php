@extends('layouts.app')

@section('content')
<section id="profile">



  <ul class="nav nav-tabs flex-d" style="background-color: var(--bs-blue);" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" data-bs-toggle="tab" href="#informations" aria-selected=true role="tab">Information</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class= "nav-link" data-bs-toggle="tab" href="#questions" aria-selected="false" role="tab" tabindex="-1">{{Auth::user()->id === $user->id ? "My Questions" : "Questions"}}</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" data-bs-toggle="tab" href="#answers" aria-selected="false" role="tab" tabindex="-1" >{{Auth::user()->id === $user->id ? "My Answers" : "Answers"}}</a>
    </li>
  </ul>





 <!-- if else to make it work -->

    <div id="myTabContent" class="tab-content" style="height: 100%;">
        <div class="tab-pane fade active show" id="informations" role="tabpanel">
            <div class="d-flex justify-content-around align-items-center" style="height: 100%;">
                <div class="card my-4">
                    <div class="card-body" style="width:35dvw">
                        <h4 class="card-title"> {{$user->username}}</h4>
                        @if($user->name)
                        <h5 class="card-subtitle text-muted">{{($user->name)}}</h5>
                        @endif
                    </div>
                    <div class="card-body d-flex align-content-around">
                        <p class="card-text">Kleos: {{$user->score}}</p>
                        <p class="card-text mx-2">Level: {{$user->experience}}</p>
                    </div>
                    <ul class="list-group list-group-flush text-primary">
                        <li class="list-group-item d-flex flex-row"> <p class="text-primary"> Questions asked: </p> <p class="pl-3 mx-1"> {{count($user->questions)}} </p> </li>
                        <li class="list-group-item d-flex flex-row"> <p class="text-primary"> Answers given: </p> <p class="pl-3 mx-1">{{count($user->answers)}}</p></li>
                        <li class="list-group-item d-flex flex-row"> <p class="text-primary"> Member since: </p> <p class="pl-3 mx-1">{{$user->member_since}}</p></li>
                    </ul>
                </div>
                @if (Auth::user()->id === $user->id)
                <div class="card my-4" style="width: 55dvw;"> 
                    <div class="card-body">
                        <div class="col align-middle">
                            <div class="row justify-content-center p-3">         
                                <fieldset>
                                    <legend>
                                        Edit Profile
                                    </legend>    
                                    <div class="form-group">
                                        <label for="name" class="form-label mt-4">Name</label>
                                        <div class="input-group">
                                            <input id="name" type="text" name="name" class="form-control" placeholder="Enter new name (optional)" value="{{ Auth::user()->name }}" autofocus>
                                            <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field" onclick="resetField('name', '{{ Auth::user()->name }}')">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                        @if ($errors->has('name'))
                                        <span class="error">
                                            {{$errors->first('name')}}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="username" class="form-label mt-4">Username</label>
                                        <div class="input-group">
                                            <input id="username" type="text" name="username" class="form-control" placeholder="Enter new username" value="{{ Auth::user()->username }}" required>
                                            <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field" onclick="resetField('name', '{{ Auth::user()->name }}')">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                        @if ($errors->has('username'))
                                        <span class="error">
                                            {{ $errors->first('username') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label mt-4">E-mail address</label>
                                        <div class=input-group>
                                            <input id="email" type="email" name="email" class="form-control" placeholder="Enter new email" value="{{ Auth::user()->email }}" required>
                                            <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field" onclick="resetField('name', '{{ Auth::user()->name }}')">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                        @if ($errors->has('email'))
                                        <span class="error">
                                            {{ $errors->first('email') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="form-label mt-4">Password</label>
                                        <input id="password" type="password" name="password" class="form-control" placeholder="New password">
                                        @if ($errors->has('password'))
                                        <span class="error">
                                            {{ $errors->first('password') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="password-confirm" class="form-label mt-4">Confirm Password</label>
                                        <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                                    </div>
                                    <div class="form-group pt-3">
                                        <button type="submit" class="btn btn-primary" aria-label="Save Changes">
                                            <i class="bi bi-check-circle"></i> Save Changes
                                        </button>
                                    </div>
                                </fieldset>
                            </div>
                        </div> 
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="questions" role="tabpanel">

            <div class= "body d-flex flex-column justify-content-evenly align-items-center pt-4" >
                <div class="card text-white bg-info mb-3" style=" width: 20em; max-width: 20em;" >
                    <div class="card-body d-flex  align-items-center flex-column">
                        <div class="profile-pic">
                        </div>
                        <h4 class="username">{{$user->username}} </h4>
                        <div id="additional-info" class= "d-flex flex-row justify-content-around" style= "width: 100%;">
                            <p>Level: {{$user->experience}} </p> 
                            <p>Kleos: {{$user->score}} </p>
                        </div>
                    </div>
                </div>

                <section class="card text-white bg-info mb-3" style="max-height:70vh; overflow: scroll; ">
                    <div class="card-body align-items-center flex-column">
                        @if(count($user->questions)=== 0)
                        <h4>User has no questions</h4> 
                        @else
                        @each('partials.questionPreview', $user->questions, 'question')
                        @endif
                    </div>
                </section>
            </div>
        </div>

        <div class="tab-pane fade" id="answers" role="tabpanel">

            <div class= "body d-flex flex-column justify-content-evenly align-items-center pt-4" >
                <div class="card text-white bg-info mb-3" style=" width: 20em; max-width: 20em;" >
                    <div class="card-body d-flex  align-items-center flex-column">
                        <div class="profile-pic">
                        </div>
                        <h4 class="username">{{$user->username}} </h4>
                        <div id="additional-info" class= "d-flex flex-row justify-content-around" style= "width: 100%;">
                            <p>Level: {{$user->experience}} </p> 
                            <p>Kleos: {{$user->score}} </p>
                        </div>
                    </div>
                </div>

                <section class="card text-white bg-info mb-3" style="max-height:70vh; overflow: scroll; ">
                    <div class="card-body align-items-center flex-column">
                        @if (count($user->answers)=== 0) 
                        <h4>User has no answers</h4> 
                        @else
                        @each('partials.answerPreview', $user->answers, 'answer')
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
  
</section>
@endsection


