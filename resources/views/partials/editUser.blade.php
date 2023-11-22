<div class="col align-middle">
    <div class="row justify-content-center p-3">
        <form id="editor-profile" method="POST" action="{{ route('users.update', $user->id) }}" class="col-4 border border-primary border-2 rounded p-3">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            @if (Route::is('admin.users.edit'))
            <input type="hidden" name="adminPage" value="true"></input>
            @else
            <input type="hidden" name="adminPage" value=""></input>
            @endif

            <fieldset>
                <legend>
                    Edit Profile
                </legend>

                <div class="form-group">
                    <label for="name" class="form-label mt-4">Name</label>
                    <div class="input-group">
                        <input id="name" type="text" name="name" class="form-control" placeholder="Enter new name (optional)" value="{{ $user->name }}" autofocus>
                        <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field" onclick="resetField('name', '{{ $user->name }}')">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                    @if ($errors->has('name'))
                    <span class="error">
                        {{ $errors->first('name') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="username" class="form-label mt-4">Username</label>
                    <div class="input-group">
                        <input id="username" type="text" name="username" class="form-control" placeholder="Enter new username" value="{{ $user->username }}" required>
                        <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field" onclick="resetField('username', '{{ $user->username }}')">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                    @if ($errors->has('username'))
                    <span class="error">
                        {{ $errors->first('username') }}
                    </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="email" class="form-label mt-4">E-mail address</label>
                    <div class=input-group>
                        <input id="email" type="email" name="email" class="form-control" placeholder="Enter new email" value="{{ $user->email }}" required>
                        <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field" onclick="resetField('email', '{{ $user->email }}')">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                    @if ($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password" class="form-label mt-4">Password</label>
                    <input id="password" type="password" name="password" class="form-control" placeholder="New password">
                    @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label mt-4">Confirm Password</label>
                    <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                </div>

                <div class="form-group pt-3">
                    <button type="submit" class="btn btn-primary" aria-label="Save Changes">
                        <i class="bi bi-check-circle"></i> Save Changes
                    </button>
                </div>
        </form>
    </div>
</div>