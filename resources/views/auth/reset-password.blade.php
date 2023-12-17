@extends('layouts.app')

@section('content')

<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <div class="container">
        <div class="row">
            <div class="col align-self-start">
                <form id="recover" method="POST" action="{{ route('password.update') }}" class="rounded p-3">
                    {{ csrf_field() }}

                    <fieldset>
                        <h1>Reset Recovery</h1>

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="email" class="form-label mt-4">E-mail address</label>
                            <input id="email" type="email" name="email" class="form-control" placeholder="Enter your email" required autofocus>
                            @if ($errors->has('email'))
                            <span class="text-danger">
                                {{ $errors->first('email') }}
                            </span>
                            @endif        
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label mt-4">New Password</label>
                            <input id="password" type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            @if ($errors->has('password'))
                            <span class="text-danger">
                                {{ $errors->first('password') }}
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="form-label mt-4">Confirm New Password</label>
                            <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
                        </div>
                        
                        <div class="form-group pt-3">
                            <button type="submit" class="btn btn-primary">
                                Reset Password
                            </button>
                            <a class="btn btn-secondary" href="{{ route('login') }}">Login</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@if (session('error')) 
<div class="alert alert-dismissible alert-danger position-absolute bottom-0 end-0 m-5">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>{{ session('error') }}</strong>
</div>
@endif
@endsection
