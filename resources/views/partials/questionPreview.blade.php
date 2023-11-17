<a href="/questions/{{ $question->id }}">
    <article class="question-prev" data-id="{{$question->id}}">
        <header>
            <h2>{{$question->title}}</h2> 
            <h2>{{$question->user()->username}}</h2>  
            <h3>{{$question->firstVersion()->date}}</h3>
        </header>
        <div class="votes">
            <!-- todo: get question score-->
        <div>
        <p>
            {{$question->updatedVersion()->body}}
        </p>
    </article>
    <a>