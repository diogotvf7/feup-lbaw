<div id="edit-tag" class="modal">
    <div class="modal-content">
        <form method="POST" action="{{ route('tag.update', 1) }}">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            <header class="d-flex justify-content-between">
                <h2>Edit Tag</h2>
                <button type="button" class="close-modal btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </header>

            <hr/>

            <input name="id" type="hidden" class="id"></input>
            <input id="edit-error" value="{{ session('edit_error_id') }}" type="hidden">

            <div class="form-group">
                <label class="form-label">Name</label>
                <div class="name input-group">
                    <input name="name" type="text" class="form-control" placeholder="Enter name" autofocus>
                    <button class="btn btn-secondary btn-sm" aria-label="Reset Field">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
                <p class="text-danger">
                @if (session()->has('edit_error_id') && $errors->has('name'))
                    {{ $errors->first('name') }}
                @endif
                </p>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <div class="description input-group">
                    <textarea name="description" class="form-control" placeholder="Enter description" required></textarea>
                    <button class="btn btn-secondary btn-sm" aria-label="Reset Field">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
                <p class="text-danger">
                @if (session()->has('edit_error_id') && $errors->has('description'))
                    {{ $errors->first('description') }}
                @endif
                </p>
            </div>

            <div class="form-group pt-3">
                <button id="update-tag" class="btn btn-primary" aria-label="Update Tag">
                    <i class="bi bi-check-circle"></i> Update Tag
                </button>
            </div>
        </form>
    </div>
</div>