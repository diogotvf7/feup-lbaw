<div id="edit-pfp" class="modal">
    <div class="modal-content">
        <header>
            <button type="button" class="close-modal btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </header>
<div class="d-flex">
<form method="POST" action="/file/upload" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <input name="file" type="file" required>
    <input name="id" type="number" value="{{ $user->id }}" hidden>
    <input name="type" type="text" value="profile" hidden>
    <button id="submit-image" type="submit" class="btn btn-primary" aria-label="Submit Image" required>Submit Image</button>
</form>
