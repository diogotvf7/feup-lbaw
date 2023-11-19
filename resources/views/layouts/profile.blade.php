@extends('layouts.app')

@section('content')
<section id="profile">

  <ul class="nav nav-tabs flex-d" style="background-color: var(--bs-blue);" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" data-bs-toggle="tab" href="#informations" aria-selected="true" role="tab">Information</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" data-bs-toggle="tab" href="#questions" aria-selected="false" role="tab" tabindex="-1">My Questions</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" data-bs-toggle="tab" href="#answers" aria-selected="false" tabindex="-1" role="tab">My Answers</a>
    </li>
  </ul>
  
  <div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active show" id="informations" role="tabpanel">
      <section id="profile-informations">
        @yield('informations')
      </section>
    </div>
    <div class="tab-pane fade" id="questions" role="tabpanel">
      <section id="profile-questions">
        @yield('questions')
      </section>
    </div>
    <div class="tab-pane fade" id="answers" role="tabpanel">
      <section id="profile-answers">
        @yield('answers')
      </section>
    </div>
  </div>
  
</section>
@endsection


