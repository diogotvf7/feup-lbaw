@extends('layouts.app')

@section('content')
<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <div class="container">
        <div class="row">
            <div class="col align-self-start">
                <form id="recover" method="POST" action="{{ route('password.email') }}" class="rounded p-3">
                    {{ csrf_field() }}

                    <fieldset>
                        <legend><h1>Password Recovery</h1></legend>

                        <div class="form-group">
                            <label for="email" class="form-label mt-4">Your email</label>
                            <input type="email" name="email" placeholder="Email" class="form-control" required autofocus>
                        </div>

                        <div class="form-group pt-3 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                Send Email
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
