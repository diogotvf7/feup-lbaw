@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <fieldset>
        <legend>Register</legend>

        <div class="form-group">
            <label for="email" class="form-label mt-4">E-Mail address</label>
            <input id="email" type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
            @endif        
        </div>

        <div class="form-group">
            <label for="password" class="form-label mt-4">Password</label>
            <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
            @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
            @endif
        </div>

        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" value="" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
            </label>
        </div>

        <button type="submit" class="btn btn-primary">
            Login
        </button>
        <a class="btn btn-secondary" href="{{ route('register') }}">Register</a>
        @if (session('success'))
            <p class="success">
                {{ session('success') }}
            </p>
        @endif 
    </fieldset>
</form>
@endsection