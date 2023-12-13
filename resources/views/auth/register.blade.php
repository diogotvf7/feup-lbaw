@extends('layouts.app')

@section('content')
<div class="col align-middle">
    <div class="row justify-content-center p-3">
        <form method="POST" action="{{ route('register') }}" class="col-4 border border-primary border-2 rounded p-3">
            {{ csrf_field() }}

            <fieldset>
            <legend>Register</legend>

            <div class="form-group">
                <label for="name" class="form-label mt-4">Name</label>
                <input id="name" type="text" name="name" class="form-control" placeholder="Enter your name (optional)" value="{{ old('name') }}" autofocus>
                @if ($errors->has('name'))
                    <span class="text-danger">
                        {{ $errors->first('name') }}
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="username" class="form-label mt-4">Username</label>
                <input id="username" type="text" name="username" class="form-control" placeholder="Enter your username" value="{{ old('username') }}" required>
                @if ($errors->has('username'))
                    <span class="text-danger">
                        {{ $errors->first('username') }}
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="email" class="form-label mt-4">E-mail address</label>
                <input id="email" type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                <span class="text-danger">
                    {{ $errors->first('email') }}
                </span>
                @endif        
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label mt-4">Password</label>
                <input id="password" type="password" name="password" class="form-control" placeholder="Enter your password" required>
                @if ($errors->has('password'))
                <span class="text-danger">
                    {{ $errors->first('password') }}
                </span>
                @endif
            </div>

            <div class="form-group">
                <label for="password-confirm" class="form-label mt-4">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
            </div>
            
            <div class="form-group pt-3">
                <button type="submit" class="btn btn-primary">
                Register
                </button>
                <a class="btn btn-secondary" href="{{ route('login') }}">Login</a>
            </div>

            </fieldset>
        </form>
    </div>
</div>
@endsection