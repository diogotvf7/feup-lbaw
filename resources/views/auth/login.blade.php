@extends('layouts.app')

@section('content')
<div class="col align-middle">
    <div class="row justify-content-center p-3">
        <form method="POST" action="{{ route('login') }}" class="col-4 border border-primary border-2 rounded p-3">
            {{ csrf_field() }}

            <fieldset>
                <legend>Login</legend>

                <div class="form-group">
                    <label for="email" class="form-label mt-4">E-mail address</label>
                    <input id="email" type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
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

                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                    </label>
                </div>

                <div class="form-group pt-3">
                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>
                    <a class="btn btn-secondary" href="{{ route('register') }}">Register</a>
                    @if (session('success'))
                        <p class="success">
                            {{ session('success') }}
                        </p>
                    @endif 
                </div>
                
            </fieldset>
        </form>
    </div>
</div>
@endsection