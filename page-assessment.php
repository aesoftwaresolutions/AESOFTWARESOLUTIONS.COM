<?php /* Template Name: Assessment */ get_header(); ?>

<div class="assessment-page">

    <div class="assessment-hero">
        <div class="assessment-hero__inner">
            <span class="assessment-hero__eyebrow">Business Intelligence Discovery</span>
            <h1 class="assessment-hero__headline">Your operational<br><em>assessment</em><br>starts here.</h1>
            <p class="assessment-hero__sub" data-reveal-text>15–30 minutes. 10 structured phases. A complete operational intelligence report — compliance risks, bottlenecks, automation opportunities, and a prioritized roadmap — delivered at the end.</p>
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
                    <span class="assessment-phase__num">06&ndash;10</span>
                    <span class="assessment-phase__label">Intelligence Report</span>
                </div>
            </div>
        </div>
    </div>

    <div class="assessment-chat-wrap">
        <ae-assessment
            endpoint="<?php echo esc_url( get_rest_url( null, 'aesoftwares/v1/assessment' ) ); ?>"
        ></ae-assessment>
    </div>

    <div class="assessment-trust-bar">
        <div class="assessment-trust-bar__inner">
            <div class="assessment-trust-item js-fade" style="--delay:0s">
                <span class="assessment-trust-item__icon">&#9670;</span>
                <span>Your responses are confidential</span>
            </div>
            <div class="assessment-trust-item js-fade" style="--delay:0.08s">
                <span class="assessment-trust-item__icon">&#9670;</span>
                <span>No account required</span>
            </div>
            <div class="assessment-trust-item js-fade" style="--delay:0.16s">
                <span class="assessment-trust-item__icon">&#9670;</span>
                <span>Full report delivered in-chat</span>
            </div>
            <div class="assessment-trust-item js-fade" style="--delay:0.24s">
                <span class="assessment-trust-item__icon">&#9670;</span>
                <span>Human follow-up available</span>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
