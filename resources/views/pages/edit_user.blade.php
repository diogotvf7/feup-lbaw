@extends('layouts.app')

@section('content')    
    <div class="col align-middle">
        <div class="row justify-content-center p-3">
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="col-4 border border-primary border-2 rounded p-3">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

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
                    <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field" onclick="resetField('name', '{{ $user->name }}')">
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
                    <button type="button" class="btn btn-secondary btn-sm" aria-label="Reset Field" onclick="resetField('name', '{{ $user->name }}')">
                        <i class="bi bi-arrow-counterclockwise"></i>            
                    </button>
                </div>
                @if ($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                @endif   
            </div>

            <div class="form-group row mt-3">
                <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                    <input type="checkbox" name="is_admin" class="btn-check" id="admin_check" {{ $user->is_admin ? 'checked' : '' }} autocomplete="off">
                    <label class="btn {{ $user->is_admin ? 'btn-success' : 'btn-danger' }}" for="admin_check">Admin</label>
                    <input type="checkbox" name="is_banned" class="btn-check" id="banned_check" {{ $user->is_banned ? 'checked' : '' }} autocomplete="off">
                    <label class="btn {{ $user->is_banned ? 'btn-success' : 'btn-danger' }}" for="banned_check">Banned</label>
                </div>
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
@endsection