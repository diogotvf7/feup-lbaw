@extends('layouts.app')
@section('content')
<div class="col align-middle">
    <div class="row justify-content-center p-3">
        <form id="editor-tag" method="POST" action="{{ route('tag.store') }}" class="col-4 border border-primary border-2 rounded p-3">
            {{ csrf_field() }}
            <fieldset>
                <legend>
                    Create Tag
                </legend>

                <div class="form-group">
                    <label for="name" class="form-label mt-4">Name</label>
                    <div id="name" class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Enter new name (optional)" autofocus>
                    </div>
                    @if ($errors->has('name'))
                    <span class="error">
                        {{ $errors->first('name') }}
                    </span>
                    @endif
                </div>
                <div id="description" class="form-group">
                    <label for="description" class="form-label mt-4">Description</label>
                    <div class="input-group">
                        <textarea name="description" class="form-control" placeholder="Enter new description" required></textarea>
                    </div>
                    @if ($errors->has('description'))
                    <span class="error">
                        {{ $errors->first('description') }}
                    </span>
                    @endif
                </div>

                <div class="form-group pt-3">
                    <button type="submit" class="btn btn-primary" aria-label="Save Changes">
                        <i class="bi bi-check-circle"></i> Create Tag
                    </button>
                </div>
        </form>
    </div>
</div>
@endsection
