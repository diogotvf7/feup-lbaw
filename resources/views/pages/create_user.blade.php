@extends('layouts.app')

@section('content')    
    <div class="col align-middle">
        <div class="row justify-content-center p-3">
        <form method="POST" action="{{ route('user.store') }}" class="col-4 border border-primary border-2 rounded p-3">
            {{ csrf_field() }}

            <fieldset>
            <legend>
                Create Account
            </legend>

            <div class="form-group">
                <label for="name" class="form-label mt-4">Name</label>
                <div class="input-group">
                    <input id="name" type="text" name="name" class="form-control" placeholder="Enter a name (optional)" value="{{ old('name') }}" autofocus>
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
                    <input id="username" type="text" name="username" class="form-control" placeholder="Enter a username" value="{{ old('username') }}" required>
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
                    <input id="email" type="email" name="email" class="form-control" placeholder="Enter a email" value="{{ old('email') }}" required>
                </div>
                @if ($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                @endif   
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_admin" id="admin-checkbox">
                <label class="form-check-label" for="admin-checkbox">
                Admin User
                </label>
            </div>

            <div class="form-group">
                <label for="password" class="form-label mt-4">Password</label>
                <input id="password" type="password" name="password" class="form-control" placeholder="Enter a password" required>
                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="password-confirm" class="form-label mt-4">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
            </div>

            <div class="form-group pt-3">
                <button type="submit" class="btn btn-primary" aria-label="Save Changes">
                    <i class="bi bi-check-circle"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection