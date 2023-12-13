<div id="create-tag" class="modal">
    <div class="modal-content">
        <form method="POST" action="{{ route('tag.store') }}">
            {{ csrf_field() }}

            <header class="d-flex justify-content-between">
                <h2>Create Tag</h2>
                <button type="button" class="close-modal btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </header>
            
            <hr/>
            <input id="create-error" value="{{ session('create_error_id') }}" type="hidden">

            <div class="name form-group">
                <label class="form-label">Name</label>
                <input name="name" type="text" class="form-control" placeholder="Enter name" required autofocus>
                <p class="text-danger">
                @if (session()->has('create_error_id') && $errors->has('name'))
                    {{ $errors->first('name') }}
                @endif
                </p>
            </div>

            <div class="description form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" placeholder="Enter description" required></textarea>
                <p class="text-danger">
                @if (session()->has('create_error_id') && $errors->has('description'))
                    {{ $errors->first('description') }}
                @endif
                </p>
            </div>

            <div class="form-group pt-3">
                <button id="submit-tag" type="submit" class="btn btn-primary" aria-label="Create Tag">
                    <i class="bi bi-check-circle"></i> Create Tag
                </button>
            </div>
        </form>
    </div>
</div>