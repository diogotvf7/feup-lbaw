<div id="create-user" class="modal">
    <div class="modal-content">
        <form method="POST" action="{{ route('user.store') }}">
            {{ csrf_field() }}

            <header class="d-flex justify-content-between">
                <h2>Create Account</h2>
                <button type="button" class="close-modal btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </header>

            <hr/>
            <input id="create-error" value="{{ session('create_error_id') }}" type="hidden">

            <div class="name form-group">
                <label class="form-label">Name</label>
                <input name="name" type="text" class="form-control" maxlength="250" placeholder="Enter name (optional)" autofocus>
                <p class="text-danger">
                @if (session()->has('create_error_id') && $errors->has('name'))
                    {{ $errors->first('name') }}
                @endif
                </p>
            </div>

            <div class="username form-group">
                <label class="form-label">Username</label>
                <input name="username" type="text" class="form-control" maxlength="30" placeholder="Enter a username" required>
                <p class="text-danger">
                @if (session()->has('create_error_id') && $errors->has('username'))
                    {{ $errors->first('username') }}
                @endif
                </p>
            </div>

            <div class="email form-group">
                <label class="form-label">E-mail address</label>
                <input name="email" type="email" class="form-control" maxlength="250" placeholder="Enter a email" required>
                <p class="text-danger">
                @if (session()->has('create_error_id') && $errors->has('email'))
                        {{ $errors->first('email') }}
                @endif
                </p>
            </div>

            <div class="type form-check">
                <input name="type" type="checkbox" class="form-check-input">
                <label class="form-check-label">Admin User</label>
            </div>

            <div class="password form-group">
                <label class="form-label">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Enter a password" required>
                <p class="text-danger">
                @if (session()->has('create_error_id') && $errors->has('password'))
                        {{ $errors->first('password') }}
                @endif
                </p>
            </div>

            <div class="password_confirmation form-group">
                <label class="form-label">Confirm Password</label>
                <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm password" required>
            </div>

            <div class="form-group pt-3">
                <button id="submit-user" type="submit" class="btn btn-primary" aria-label="Save Changes" required>
                    <i class="bi bi-check-circle"></i> Create Account
                </button>
            </div>
        </form> 
    </div>
</div>
