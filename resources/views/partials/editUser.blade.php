<?php 
    use Illuminate\Support\Facades\Route;
    $displayErrors = session()->has('edit_error_id') || Route::is('user.profile');
?>

@if (Route::is('user.profile'))
<div class="col align-middle w-lg-50">
    <div class="row justify-content-center p-3">
        <form id="editor-profile" method="POST" action="{{ route('user.update', $user->id) }}" class="col-4 border border-2 rounded p-3">
@elseif (Route::is('admin.users'))
<div id="edit-user" class="modal">
    <div class="modal-content">
        <form method="POST" action="{{ route('user.update', 1) }}">
@endif        
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            <header class="d-flex justify-content-between">
                <h2>Edit Profile</h2>
                @if (Route::is('admin.users'))
                <button type="button" class="close-modal btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
                @endif
            </header>

            @if (Route::is('user.edit'))
                <input type="hidden" name="adminPage" value="true"></input>
            @else
                <input type="hidden" name="adminPage" value=""></input>
            @endif
            <input name="id" class="id" type="hidden"></input>
            <input id="edit-error" value="{{ session('edit_error_id') }}" type="hidden">

            <div class="form-group">
                <label class="form-label">Name</label>
                <div class="name input-group">
                    <input type="text" name="name" class="form-control" placeholder="Enter new name (optional)" value="{{ $user->name }}" autofocus>
                    <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
                <p class="text-danger">
                @if ($displayErrors && $errors->has('name'))
                    {{ $errors->first('name') }}
                @endif
                </p>
            </div>
            <div class="form-group">
                <label class="form-label">Username</label>
                <div class="username input-group">
                    <input type="text" name="username" class="form-control" placeholder="Enter new username" value="{{ $user->username }}" required>
                    <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
                <p class="text-danger">
                @if ($displayErrors && $errors->has('username'))
                    {{ $errors->first('username') }}
                @endif
                </p>
            </div>

            <div class="form-group">
                <label class="form-label">E-mail address</label>
                <div class="email input-group">
                    <input type="email" name="email" class="form-control" placeholder="Enter new email" value="{{ $user->email }}" required>
                    <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
                <p class="text-danger">
                @if ($displayErrors && $errors->has('email'))
                    {{ $errors->first('email') }}
                @endif
                </p>
            </div>

            <div class="password form-group">
                <label class="form-label">Password <small class="text-secondary">(Leave blank if you don't want to update)</small></label>
                <input type="password" name="password" class="form-control" placeholder="New password">
                <p class="text-danger">
                @if ($displayErrors && $errors->has('password'))
                    {{ $errors->first('password') }}
                @endif
                </p>
            </div>

            <div class="password-confirmation form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
            </div>

            <div class="form-group pt-3">
                <button id="update-user" type="submit" class="btn btn-primary" aria-label="Save Changes">
                    <i class="bi bi-check-circle"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>