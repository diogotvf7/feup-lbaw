<div class="card my-4 d-flex flex-column align-items-center">
    <div class="card-body d-flex flex-column align-items-center" style="width:fit-content">
        <h4 class="card-title"> {{$user->username}}</h4>
        @if($user->name)
        <h5 class="card-subtitle text-muted">{{($user->name)}}</h5>
        @endif
        <img class="object-fit-cover rounded-circle mt-5 mx-3" src="{{ $user->getProfilePicture() }}" id="profile-picture" style="width: 15dvw; height: 15dvw;"></img>
    </div>
    <div class="card-body d-flex align-content-around">
        <p class="badge rounded-pill bg-primary me-2">Kleos:&NonBreakingSpace;{{$user->score}}</p>
        <p class="badge rounded-pill bg-primary">Level:&NonBreakingSpace;{{$user->experience}}</p>
    </div>
    <ul class="list-group list-group-flush text-primary w-100">
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

