<div id="edit-tag" class="modal">
    <div class="modal-content">
            <header class="d-flex justify-content-between">
                Edit Tag
                <button class="close-modal btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </header>

            <input type="hidden" class="id"></input>

            <div class="form-group">
                <label for="name" class="form-label mt-4">Name</label>
                <div class="name input-group">
                    <input type="text" class="form-control" placeholder="Enter name" autofocus>
                    <button class="btn btn-secondary btn-sm" aria-label="Reset Field">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
                <p class="error"></p>
            </div>
            <div class="form-group">
                <label for="description" class="form-label mt-4">Description</label>
                <div class="description input-group">
                    <textarea class="form-control" placeholder="Enter description" required></textarea>
                    <button class="btn btn-secondary btn-sm" aria-label="Reset Field">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
                <p class="error"></p>
            </div>

            <div class="form-group pt-3">
                <button id="update-tag" class="btn btn-primary" aria-label="Update Tag">
                    <i class="bi bi-check-circle"></i> Update Tag
                </button>
            </div>
    </div>
</div>