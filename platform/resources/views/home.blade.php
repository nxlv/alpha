@extends('layouts.app')

@section('content')
    <header>
        <h1>Dashboard</h1>
    </header>

    <section>
        <div class="alpha__wizard">
            <div class="alpha__wizard-inner">
                <header class="alpha__wizard-intro">
                    <h3>Let's Find The Right Solution</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas pharetra, neque eu auctor bibendum, nulla urna efficitur mi, vulputate condimentum felis orci ac magna. Aliquam erat volutpat. Aliquam sit amet sollicitudin neque. Vivamus sit amet ipsum quis augue molestie posuere. Aliquam ultrices, orci ut aliquam tincidunt, tortor odio mollis mauris.</p>
                </header>

                <div class="alpha__wizard-steps">
                    <header class="alpha__wizard-steps-title">
                        <h4>What is your financial goal?</h4>

                        <div class="alpha__wizard-steps-indicator">
                            <div>
                                <span><small>Step</small> 1 <em>of</em></span>
                            </div>
                            <div>
                                <span>5</span>
                            </div>
                        </div>
                    </header>

                    <div class="alpha__wizard-steps-content">
                        <div class="alpha__wizard-steps-questions">
                            <a href="#" class="alpha__wizard-steps-question" data-question="s1o1" data-value="1">
                                <p><strong>Growth</strong></p>
                                <p>I want to grow my investment for a while.</p>
                                <p><small>Additional text or description can go here.<br />Additional text or description can go here.</small></p>
                            </a>

                            <a href="#" class="alpha__wizard-steps-question" data-question="s1o2" data-value="2">
                                <p><strong>Income</strong></p>
                                <p>I want my investment to provide stable income.</p>
                                <p><small>Additional text or description can go here.<br />Additional text or description can go here.</small></p>
                            </a>
                        </div>

                        <aside class="alpha__wizard-steps-video">
                            <div class="alpha__wizard-steps-video-content">
                                <header class="alpha__wizard-steps-video-content-intro">
                                    <h5>Confused? Not Sure?</h5>
                                    <p>Watch our video by clicking the play button below.</p>
                                </header>

                                <a href="#" class="alpha__wizard-steps-video-content-button">Play Video</a>
                            </div>

                            <div class="alpha__wizard-steps-video-contact">
                                <p><small class="smaller"><strong>Need more help?</strong><br />Call us at <a href="tel:888.555.1212"><strong>888.555.1212</strong></a></small></p>
                            </div>
                        </aside>
                    </div>
                </div>

                <footer class="alpha__wizard-buttons">
                    <a href="#" class="alpha__wizard-button alpha__wizard-button--forward">Next Step <i class="fal fa-arrow-circle-right" aria-hidden="true"></i></a>
                    <a href="#" class="alpha__wizard-button alpha__wizard-button--back"><i class="fal fa-arrow-circle-left" aria-hidden="true"></i> Go Back</a>
                </footer>
            </div>
        </div>
    </section>
@endsection
