<?php
$author = auth()->check() && $user->id === auth()->user()->id;
$admin = !$author && (auth()->check() && auth()->user()->type === "Admin");
?>

<div id="edit-pfp" class="modal">
    <div class="modal-content">
        <header class="d-flex justify-content-between">
            @if($author)
            <h2>Edit Profile Picture</h2>
            @elseif($admin)
            <h2>Remove Profile Picture</h2>
            @endif
            <button type="button" class="close-modal btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </header>

        <hr />

        @if($author)
        <form method="POST" action="{{route('file.upload')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="modal-body m-3">
                <input name="file" type="file" required>
                <input name="id" type="number" value="{{ $user->id }}" hidden>
                <input name="type" type="text" value="profile" hidden>

            </div>
            <div class="modal-footer">
                <button type="submit" id="submit-image" class="btn btn-primary" aria-label="Save Changes">Save changes</button>
                <button type="button" class="close-modal btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
        @elseif($admin)
        <form method="POST" action="{{route('file.delete')}}">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="modal-body m-3">
                <p>Are you sure you want to remove
                <strong class="fw-bold">{{$user->username}}</strong>'s profile picture permanently?
                </p>
                <input name="id" type="number" value="{{ $user->id }}" hidden>
                <input name="type" type="text" value="profile" hidden>

            </div>
            <div class="modal-footer">
                <button type="submit" id="submit-image" class="btn btn-primary" aria-label="Save Changes">Confirm</button>
                <button type="button" class="close-modal btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
        @endif
    </div>
</div>