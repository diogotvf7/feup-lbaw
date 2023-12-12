<div id="create-tag" class="modal">
    <div class="modal-content">
        <header class="d-flex justify-content-between">
            <h2>Create Tag</h2>
            <button type="button" class="close-modal btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </header>
        
        <hr/>

        <div class="name form-group">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" placeholder="Enter name" maxlength="30" autofocus>
            <p class="text-danger"></p>
        </div>

        <div class="description form-group">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" placeholder="Enter description" maxlength="300"></textarea>
            <p class="text-danger"></p>
        </div>

        <div class="form-group pt-3">
            <button id="submit-tag" class="btn btn-primary" aria-label="Create Tag">
                <i class="bi bi-check-circle"></i> Create Tag
            </button>
        </div>
    </div>
</div>