@extends('layouts.app')

@section('content')

<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <form method="POST" action="{{ route('question.store') }}" class="scroll-container overflow-y-scroll w-100 p-5">
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
                <input type="text" name="title" class="form-control" placeholder="e.g. How to fix a lamp" value="{{ old('title') }}" required autofocus>
                @if ($errors->has('title'))
                    <span class="text-danger">
                        {{ $errors->first('title') }}
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="body" class="form-label">Body</label>
                <textarea name="body" class="form-control" rows="10" value="{{ old('body') }}" required></textarea>
                @if ($errors->has('body'))
                    <span class="text-danger">
                        {{ $errors->first('body') }}
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="tags" class="form-label">Tags</label>
                <input id="tag-input" type="text" name="tags" class="form-control">
            </div>

            <div class="d-flex justify-content-between align-content-end my-3">
                <div class="form-group pt-3">
                    <button type="submit" class="btn btn-primary">
                        Submit Question
                    </button>
                </div>
                <div class="d-flex flex-column align-items-end">                     
                    <p class="m-0">Don't find an appropriate tag?</p>
                    <button id="open-modal" type="button" class="btn btn-link text-decoration-none">Request the creation of a tag</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>

@include('partials.createTag')

<button type="button" class="btn btn-primary rounded" id="back-top">
    <i class="bi bi-arrow-up"></i>
</button>
@endsection 