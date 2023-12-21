<div id="edit-pfp" class="modal">
    <div class="modal-content">
        <header class="d-flex justify-content-between">
            <h2>Edit Profile Picture</h2>
            <button type="button" class="close-modal btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </header>

        <hr />
        <form method="POST" action="/file/upload" enctype="multipart/form-data" class="">
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
    </div>
</div>