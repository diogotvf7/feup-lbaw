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
                            <a class="ms-3" href="{{ route('google-auth') }}">
                                <button class="gsi-material-button" type="button">
                                    <div class="gsi-material-button-state"></div>
                                    <div class="gsi-material-button-content-wrapper">
                                        <div class="gsi-material-button-icon">
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: block;">
                                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                                                <path fill="none" d="M0 0h48v48H0z"></path>
                                            </svg>
                                        </div>
                                        <span class="gsi-material-button-contents">Continue with Google</span>
                                        <span style="display: none;">Continue with Google</span>
                                    </div>
                                </button>
                            </a>
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