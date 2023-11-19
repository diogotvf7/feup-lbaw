@extends('layouts.app')

@section('content')

<nav class="navbar navbar-expand-lg bg-light" data-bs-theme="light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarColor03">
            <ul class="w-100 mx-auto navbar-nav">
                <li class="nav-item flex-fill">
                    <a href=>Information</a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="mx-auto" href=>My Questions</a>
                </li>
                <li class="nav-item flex-fill">
                    <a href=>My Answers</a>
                </li>
            </ul>
        </div>
        <div>
</nav>

<div class= "main-part d-flex justify-content-evenly align-items-baseline pt-4">
    <div class="card text-white bg-info mb-3" style=" width: 20em; max-width: 20em;" >
        <div class="card-body d-flex  align-items-center flex-column">
            <div class="profile-pic">
            </div>
            <h4 class="username">{{Auth::user()->username}} </h4>
            <div id="additional-info">
                <p>Level: {{Auth::user()->experience}} </p> <!-- todo: level calculator function -->
                <p>Kleos: {{Auth::user()->score}} </p>
            </div>
        </div>
    </div>

    <section class="card text-white bg-info mb-3" style="width: 50em;max-width: 60em;">
        <div class="card-body align-items-center flex-column">
            @each('partials.questionPreview', $questions, 'question')
        </div>
    </section>
</div>

@endsection


<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" data-bs-toggle="tab" href="#home" aria-selected="true" role="tab">Home</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" data-bs-toggle="tab" href="#profile" aria-selected="false" role="tab" tabindex="-1">Profile</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link disabled" href="#" aria-selected="false" tabindex="-1" role="tab">Disabled</a>
  </li>
</ul>
<div id="myTabContent" class="tab-content">
  <div class="tab-pane fade active show" id="home" role="tabpanel">
    <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel">
    <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
  </div>
  <div class="tab-pane fade" id="dropdown1">
    <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.</p>
  </div>
  <div class="tab-pane fade" id="dropdown2">
    <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater.</p>
  </div>
</div>