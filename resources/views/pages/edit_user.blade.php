@extends('layouts.app')

@section('content')    
    <div class="col align-middle">
        <div class="row justify-content-center p-3">
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="col-4 border border-primary border-2 rounded p-3">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            <fieldset>
            <legend>
                Edit {{ $user->username }} 
                @if ($user->name) 
                    ({{ $user->name }})
                @endif
                profile
            </legend>

            <div class="form-group">
                <label for="name" class="form-label mt-4">Name</label>
                <input id="name" type="text" name="name" class="form-control" placeholder="Enter your name (optional)" value="{{ $user->name }}" autofocus>
            </div>
            @if ($errors->has('name'))
                <span class="error">
                    {{ $errors->first('name') }}
                </span>
            @endif

            <button type="submit" class="btn btn-primary" aria-label="Save Changes">
                <i class="bi bi-check-circle"></i> Save Changes
            </button>
        </form>
    </div>
</div>
@endsection