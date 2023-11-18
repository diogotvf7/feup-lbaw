<article class="question-prev card border-primary mb-3"  data-id="{{$question->id}}" style="max-width: 20rem;">
    <a href="/questions/{{ $question->id }}">
        <header class="card-header">
            <h3>{{$question->user->username}}</h3>
            <h3>{{$question->firstVersion}}</h3>
        </header>
        <div class="card-body">
            <h4 class="card-title">{{$question->title}}</h4>
            <div class="votes">
                {{$question->votes}}
            <div>
            <p class="card-text">
                {{$question->updatedVersion}}
            </p>
        <div>
    <a>
</article>



