@extends('layouts.app')

@section('content')

<div class="col align-middle">
    <div class="row justify-content-center p-3">
        <form method="POST" action="{{ route('question.store') }}" class="col-4 border border-primary border-2 rounded p-3">
            {{ csrf_field() }}
            
            <fieldset>
                <legend>
                    <h1>
                        Ask a Question
                    </h1>
                </legend>   

                <hr>

                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. How to fix a lamp" required autofocus>
                    @if ($errors->has('title'))
                        <span class="error">
                            {{ $errors->first('title') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="body" class="form-label">Body</label>
                    <textarea name="body" class="form-control" rows="10" required></textarea>
                    @if ($errors->has('body'))
                        <span class="error">
                            {{ $errors->first('body') }}
                        </span>
                    @endif
                </div>
                
                <div class="form-group pt-3">
                    <button type="submit" class="btn btn-primary">
                    Submit Question
                    </button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
@endsection 