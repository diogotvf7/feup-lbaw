@if (Auth::check() && isset($user))
<a href="{{ route('user.profile', $user) }}" class="text-decoration-none">
    @endif
    <div class="d-flex flex-row align-items-center">
        @if (isset($user))
        <img class="rounded-circle object-fit-cover me-2" src="{{ $user->getProfilePicture() }}" style="min-width: 20px; min-height: 20px; width: 2dvw; height: 2dvw;" alt="{{$user->username}}'s profile picture"></img>
        <p class="m-0">{{$user->username}}</p>
        @else
        <p class="m-0">Deleted User</p>
        @endif
    </div>
    @if (Auth::check())
</a>
@endif