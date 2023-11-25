@extends('layouts.app')

@section('title', 'Questions')
@section('content')
<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <section class="overflow-y-scroll w-100 p-3">
        <header class="d-flex justify-content-between align-items-center p-3">
            <h1>
                <?= ucfirst(isset($_GET['filter']) ? $_GET['filter'] : 'all') . ' Questions' ?>
            </h1>
            <a href="/questions/create" class="btn btn-primary">Ask Question</a>
        </header>
        <hr>
        <div id="questions-container" class="w-100">
            <div id="loader" class="invisible">Loading...</div> 
        </div>
    </section>
</div>
@endsection 