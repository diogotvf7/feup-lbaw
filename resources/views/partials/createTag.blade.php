<div id="create-tag" class="modal">
    <div class="modal-content">
        <header class="d-flex justify-content-between">
            <h2>Create Tag</h2>
            <button type="button" class="close-modal btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </header>

        <div id="name" class="form-group">
            <label for="name" class="form-label mt-4">Name</label>
            <div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="Enter name" maxlength="30" required autofocus>
            </div>
            <p class="error"></p>
        </div>
        <div id="description" class="form-group">
            <label for="description" class="form-label mt-4">Description</label>
            <div class="input-group">
                <textarea name="description" class="form-control" placeholder="Enter description" maxlength="300" required></textarea>
            </div>
            <p class="error"></p>
        </div>

        <div class="form-group pt-3">
            <button id="submit-tag" type="button" class="btn btn-primary" aria-label="Create Tag">
                <i class="bi bi-check-circle"></i> Create Tag
            </button>
        </div>
    </div>
</div>