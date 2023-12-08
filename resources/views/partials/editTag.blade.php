<div id="create-tag" class="modal">
    <div class="modal-content">
        <form id="editor-tag" method="POST" action="{{ route('tag.update') }}">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <input id="default-name" type="hidden" name="name" value="{{ $tag->name }}"></input>
            <input id="default-description" type="hidden" name="description" value="{{ $tag->description }}"></input>

            <fieldset>
                <legend class="d-flex justify-content-between">
                    Create Tag
                    <button id="close-modal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </legend>

                <div class="form-group">
                    <label for="name" class="form-label mt-4">Name</label>
                    <div id="name" class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Enter name" autofocus>
                    </div>
                    @if ($errors->has('name'))
                    <span class="error">
                        {{ $errors->first('name') }}
                    </span>
                    @endif
                </div>
                <div id="description" class="form-group">
                    <label for="description" class="form-label mt-4">Description</label>
                    <div class="input-group">
                        <textarea name="description" class="form-control" placeholder="Enter description" required></textarea>
                    </div>
                    @if ($errors->has('description'))
                    <span class="error">
                        {{ $errors->first('description') }}
                    </span>
                    @endif
                </div>

                <div class="form-group pt-3">
                    <button id="open-modal" type="submit" class="btn btn-primary" aria-label="Create Tag">
                        <i class="bi bi-check-circle"></i> Create Tag
                    </button>
                </div>
        </form>
    </div>
</div>