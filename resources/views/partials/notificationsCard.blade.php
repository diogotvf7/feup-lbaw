@forelse (Auth::user()->notifications as $notification)
@if($loop->first)
<li class="list-group-item list-group-item-action"><a id="dismiss-notifications" href="javascript:void(0);" class="btn btn-secondary">Dismiss All</a></li>
@endif
<li class="list-group-item list-group-item-action">
    @include('partials.notification', ['notification' => $notification])
</li>
@empty
<li class="list-group-item list-group-item-action d-none"><a id="dismiss-notifications" href="javascript:void(0);" class="btn btn-secondary">Dismiss All</a></li>
<li class="list-group-item list-group-item-action">
    <p>Nothing to see here!</p>
</li>
@endforelse