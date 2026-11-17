<?php /* Template Name: Assessment */ get_header(); ?>

<div class="assessment-page">

    <div class="assessment-hero">
        <div class="assessment-hero__inner">
            <span class="assessment-hero__eyebrow">Business Intelligence Discovery</span>
            <h1 class="assessment-hero__headline">Your operational<br><em>assessment</em><br>starts here.</h1>
            <p class="assessment-hero__sub" data-reveal-text>About 15 to 30 minutes. 10 structured phases. At the end you get an operational report covering risks, bottlenecks, automation opportunities, and a prioritized roadmap.</p>
            <div class="assessment-phases">
                <div class="assessment-phase js-fade" style="--delay:0s">
                    <span class="assessment-phase__num">01</span>
                    <span class="assessment-phase__label">Business Profile</span>
                </div>
                <div class="assessment-phase js-fade" style="--delay:0.06s">
                    <span class="assessment-phase__num">02</span>
                    <span class="assessment-phase__label">Customer Journey</span>
                </div>
                <div class="assessment-phase js-fade" style="--delay:0.12s">
                    <span class="assessment-phase__num">03</span>
                    <span class="assessment-phase__label">Operations</span>
                </div>
                <div class="assessment-phase js-fade" style="--delay:0.18s">
                    <span class="assessment-phase__num">04</span>
                    <span class="assessment-phase__label">Technology</span>
                </div>
                <div class="assessment-phase js-fade" style="--delay:0.24s">
                    <span class="assessment-phase__num">05</span>
                    <span class="assessment-phase__label">Compliance</span>
                </div>
                <div class="assessment-phase js-fade" style="--delay:0.30s">
                    <span class="assessment-phase__num">06 to 10</span>
                    <span class="assessment-phase__label">Intelligence Report</span>
                </div>
            </div>
            <p class="assessment-hero__sub assessment-addon">Looking for the paid AI Tools Assessment? It is $999 one time and is offered only as an add on to the Missed-Lead Audit, which is $250 and credited in full if you go ahead.</p>
        </div>
    </div>

    <div class="assessment-chat-wrap">
        <ae-assessment
            endpoint="https://admin.aesoftwaresolutions.com/api/widget/chat"
        ></ae-assessment>
    </div>

    <div class="assessment-trust-bar">
        <div class="assessment-trust-bar__inner">
            <div class="assessment-trust-item js-fade" style="--delay:0.08s">
                <span class="assessment-trust-item__icon">&#9670;</span>
                <span>No account required</span>
            </div>
            <div class="assessment-trust-item js-fade" style="--delay:0.16s">
                <span class="assessment-trust-item__icon">&#9670;</span>
                <span>Full report delivered in the chat</span>
            </div>
            <div class="assessment-trust-item js-fade" style="--delay:0.24s">
                <span class="assessment-trust-item__icon">&#9670;</span>
                <span>Human follow up available</span>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
