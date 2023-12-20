<div class="card my-4">
    <div class="card-body" style="width:35dvw">
        <h4 class="card-title"> {{$user->username}}</h4>
        @if($user->name)
        <h5 class="card-subtitle text-muted">{{($user->name)}}</h5>
        @endif
        <img src="{{ $user->getProfilePicture() }}" id="profile-picture" type="button" ></button>
    </div>
    <div class="card-body d-flex align-content-around">
        <p class="card-text">Kleos: {{$user->score}}</p>
        <p class="card-text mx-2">Level: {{$user->experience}}</p>
    </div>
    <ul class="list-group list-group-flush text-primary">
        <li class="list-group-item d-flex flex-row">
            <p class="text-primary"> Questions asked: </p>
            <p class="pl-3 mx-1"> {{count($user->questions)}} </p>
        </li>
        <li class="list-group-item d-flex flex-row">
            <p class="text-primary"> Answers given: </p>
            <p class="pl-3 mx-1">{{count($user->answers)}}</p>
        </li>
        <li class="list-group-item d-flex flex-row">
            <p class="text-primary"> Member since: </p>
            <p class="pl-3 mx-1">{{$user->member_since}}</p>
        </li>
    </ul>
</div>
