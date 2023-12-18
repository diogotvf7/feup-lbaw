@extends('layouts.app')

@section('content')
<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <div class="container">
        <div class="row">
            <div class="col align-self-start">
                <form id="login" method="POST" action="{{ route('login') }}" class="rounded p-3">
                    {{ csrf_field() }}

                    <fieldset>
                        <h1>Login</h1>

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
                            <label for="password" class="form-label d-flex justify-content-between mt-4">
                                Password
                                <a class="btn btn-link text-decoration-none fw-lighter p-0" href="{{ route('password.request') }}">Forgot your password?</a>
                            </label>
                            <input id="password" type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            @if ($errors->has('password'))
                                <span class="text-danger">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>

                        <div class="form-check">
                            <label class="form-check-label mt-4">
                                <input type="checkbox" class="form-check-input" value="" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                            </label>
                        </div>

                        <div class="form-group pt-3">
                            <button type="submit" class="btn btn-primary">
                                Login
                            </button>
                            <a class="btn btn-secondary" href="{{ route('register') }}">Register</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@if (session('success')) 
<div class="alert alert-dismissible alert-success position-absolute bottom-0 end-0 m-5">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>{{ session('success') }}</strong>
</div>
@endif
@endsection