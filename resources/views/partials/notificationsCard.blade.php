    @forelse (Auth::user()->notifications as $notification)
    <li class="list-group-item list-group-item-action">
        @include('partials.notification', ['notification' => $notification])
    </li>
    @empty
    <li id="empty-notifications" class="list-group-item list-group-item-action">
        <p>Nothing to see here!</p>
    </li>
    @endforelse