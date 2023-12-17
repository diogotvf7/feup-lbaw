@extends('layouts.app')

@section('content')
<div class="d-flex flex-fill overflow-hidden">
    @include('layouts.sidebar')
    <section class="overflow-y-scroll w-100 p-5">
        <div class="d-flex gap-5 align-items-center">
            <div>
                <h2>Welcome to Geras, Where Wisdom Meets Youth</h2>
                <p>
                    In the realm of Greek mythology, Geras, the god of aging, 
                    stands as a symbol of the passage of time and the wisdom 
                    that comes with it. Similarly, our platform embraces the 
                    journey of young adults entering the complexities of adulthood. 
                    Just as Geras imparts his timeless wisdom, 
                    <a href="/" class="text-decoration-none">Geras</a> 
                    is a space for the exchange of experiences, questions, and insights, 
                    fostering a community that recognizes the value in the journey 
                    of growing up.
                </h5>
            </div>
            <img src="{{ asset('images/logo.svg') }}" alt="Geras Logo" class="d-inline-block img-thumbnail">
        </div>

        <hr class="my-5">

        <div>
            <h2>Explore, Learn, Grow!</h2>
            <p>
                At Geras, we've crafted a dynamic and engaging platform tailored to meet 
                the unique needs of young adults embarking on the journey of adulthood. 
                Here's a glimpse of the exciting features that await you:
            </p>
            <div class="container">
                <div class="row row-cols-2 g-2">
                    <div class="col p-3">
                        <h4>Ask and Answer Questions:</h4> 
                        <p class="m-0">
                            Pose burning questions on any topic related to adulthood, from 
                            career dilemmas to personal growth and share your expertise by 
                            providing insightful answers to help others navigate their challenges.
                        </p>
                    </div>
                    <div class="col p-3">
                        <h4>Vibrant Community Interaction:</h4> 
                        <p class="m-0">
                            Engage in lively discussions through comments, fostering a 
                            supportive and collaborative environment and connect with 
                            like-minded individuals, exchanging ideas and experiences.
                        </p>
                    </div>
                    <div class="col p-3">
                        <h4>Follow Questions and Users:</h4> 
                        <p class="m-0">
                            Stay updated on topics that matter to you by following 
                            specific questions of interest while building your network 
                            by following users whose perspectives resonate with you.
                        </p>
                    </div>
                    <div class="col p-3">
                        <h4>Tagging for Precision:</h4> 
                        <p class="m-0">
                            Enhance discoverability by adding relevant 
                            tags to your posts, ensuring they reach the right audience 
                            and seamlessly browse through content based on tags, making it 
                            easy to find discussions aligned with your interests.
                        </p>
                    </div>
                    <div class="col p-3">
                        <h4>Browse by Tags, Posts, and More:</h4> 
                        <p class="m-0">
                            Explore a vast array of topics using our intuitive tag system, 
                            designed to simplify content discovery and navigate through posts 
                            effortlessly, whether you're seeking advice, inspiration, or 
                            shared experiences.
                        </p>
                    </div>
                    <div class="col p-3">
                        <h4>Personalized Profiles:</h4> 
                        <p class="m-0">
                            Unlock a personalized digital identity as you earn experience 
                            points, collect badges of honor, and build your virtual fame with 
                            Kleos. You can keep track of your activity, contributions, and 
                            interactions on a personalized dashboard.
                        </p>
                    </div>
                    <div class="col p-3">
                        <h4>Notifications and Updates:</h4> 
                        <p class="m-0">
                            Receive timely notifications about new responses, comments, 
                            and activities related to your questions and posts that will 
                            let you stay informed about the latest discussions and updates 
                            within the community.
                        </p>
                    </div>
                    <div class="col p-3">
                        <h4>Join us!</h4>
                        <p>
                            Geras is more than just a forum; it's a vibrant community where 
                            curiosity meets wisdom, and every interaction contributes to the 
                            collective growth of our members. Join us on this exciting journey 
                            of exploration, learning, and personal development. Together, let's 
                            make adulthood a shared adventure!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div>
            <h2>Contacts</h2>
            <div class="d-flex">
                <p>
                    Dear Geras Community, <br/>
                    We cherish the thoughts and insights you bring to Geras. 
                    Whether you have questions, suggestions, or simply want to connect, 
                    don't hesitate to reach out. Your feedback plays a crucial role in shaping 
                    the Geras experience. Feel free to contact us via email at info@gerasforum.com 
                    or through our social media channels. Your voice matters, and we're here to 
                    listen. Let's build a vibrant community together! <br/>
                    Warm regards, <br/>
                    The Geras Team
                </p>
                <div class="d-flex text-center gap-3">
                    <div>
                        <i class="bi bi-telephone-fill text-primary" style="height: 100px;"></i>
                        <p>Call us at:</p>
                        <div class="ms-2 text-nowrap">+1 (514) 123-4567</div>
                        <div class="ms-2 text-nowrap">+1 (514) 765-4321</div>
                        <div class="ms-2 text-nowrap">+1 (514) 987-6543</div>
                    </div>
                    <div>
                        <i class="bi bi-envelope-fill text-primary"></i>
                        <div class="ms-2">
                            <p>Send us an email at:</p>
                            <a href="mailto:info@gerasforum.com" class="text-decoration-none">
                            info@gerasforum.com
                            </a>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
