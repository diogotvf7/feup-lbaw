    @forelse (Auth::user()->notifications as $notification)
    @if($loop->first)
    <li class="list-group-item list-group-item-action"><button id="dismiss-notifications" class="btn btn-secondary">Dismiss All</button></li>
    @endif
    <li class="list-group-item list-group-item-action">
        @include('partials.notification', ['notification' => $notification])
    </li>
    @empty
    <li class="list-group-item list-group-item-action">
        <p>Nothing to see here!</p>
    </li>
    @endforelse
