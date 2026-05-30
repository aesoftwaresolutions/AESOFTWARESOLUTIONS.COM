<?php /* Template Name: Services */ get_header(); ?>

<div class="page-hero services-page-hero">
    <div class="page-hero__eyebrow">What we offer</div>
    <h1 class="page-hero__headline">Operational intelligence<br><em>built around</em><br>your compliance.</h1>
    <p class="page-hero__subtitle" data-reveal-text>Three service tiers — from automated discovery to full deployment. Every engagement starts with understanding your business before touching a single line of code.</p>
</div>

<!-- TIER 1 -->
<section class="service-tier-section" id="tier-1">
    <div class="service-tier-inner">
        <div class="service-tier-label js-fade" style="--delay:0s">
            <span class="service-tier-label__num">01</span>
            <span class="service-tier-label__tag">Assessment</span>
        </div>
        <div class="service-tier-content js-fade" style="--delay:0.1s">
            <h2 class="service-tier-title">Operational Discovery</h2>
            <p class="service-tier-desc">Our AI-guided Business Intelligence Discovery Interview walks through 10 structured phases — mapping your workflows, technology stack, compliance requirements, and financial leaks. You receive a complete operational intelligence report at the end.</p>
            <div class="service-tier-cols">
                <div class="service-tier-col">
                    <h4 class="service-tier-col__title">What we analyze</h4>
                    <ul class="service-tier-list">
                        <li>Business profile &amp; operational complexity</li>
                        <li>Customer journey &amp; sales process</li>
                        <li>Daily, weekly, and monthly operations</li>
                        <li>Technology stack &amp; integration gaps</li>
                        <li>Compliance requirements &amp; risk exposure</li>
                        <li>Knowledge management &amp; SOPs</li>
                        <li>Revenue and cost leakage points</li>
                        <li>Automation opportunities across all workflows</li>
                    </ul>
                </div>
                <div class="service-tier-col">
                    <h4 class="service-tier-col__title">What you receive</h4>
                    <ul class="service-tier-list">
                        <li>Executive summary &amp; operational risk report</li>
                        <li>Compliance risk register</li>
                        <li>Automation opportunity matrix with ROI estimates</li>
                        <li>Technology recommendations</li>
                        <li>Operational Intelligence Score (6 dimensions)</li>
                        <li>Phased implementation roadmap</li>
                    </ul>
                </div>
            </div>
            <a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" class="btn btn--primary">Start Your Free Assessment</a>
        </div>
    </div>
</section>

<!-- TIER 2 -->
<section class="service-tier-section service-tier-section--alt" id="tier-2">
    <div class="service-tier-inner">
        <div class="service-tier-label js-fade" style="--delay:0s">
            <span class="service-tier-label__num">02</span>
            <span class="service-tier-label__tag">Optimization</span>
        </div>
        <div class="service-tier-content js-fade" style="--delay:0.1s">
            <h2 class="service-tier-title">Process Intelligence</h2>
            <p class="service-tier-desc">Anthony personally reviews your assessment findings, conducts follow-up interviews, and produces a comprehensive optimization package — process maps, automation architecture, and a costed implementation plan ranked by ROI.</p>
            <div class="service-tier-cols">
                <div class="service-tier-col">
                    <h4 class="service-tier-col__title">Deliverables</h4>
                    <ul class="service-tier-list">
                        <li>End-to-end process maps for all core workflows</li>
                        <li>Automation architecture design</li>
                        <li>Compliance control recommendations</li>
                        <li>SOP templates &amp; documentation framework</li>
                        <li>ROI projections &amp; payback analysis</li>
                        <li>Phased roadmap: 30 / 90 / 365 days</li>
                    </ul>
                </div>
                <div class="service-tier-col">
                    <h4 class="service-tier-col__title">Best for</h4>
                    <ul class="service-tier-list">
                        <li>Businesses with 5–50 employees</li>
                        <li>Regulated industries with compliance burden</li>
                        <li>Operations with high manual administrative load</li>
                        <li>Teams preparing for growth or acquisition</li>
                    </ul>
                </div>
            </div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--outline">Request a Proposal</a>
        </div>
    </div>
</section>

<!-- TIER 3 -->
<section class="service-tier-section" id="tier-3">
    <div class="service-tier-inner">
        <div class="service-tier-label js-fade" style="--delay:0s">
            <span class="service-tier-label__num">03</span>
            <span class="service-tier-label__tag">Implementation</span>
        </div>
        <div class="service-tier-content js-fade" style="--delay:0.1s">
            <h2 class="service-tier-title">Intelligent Deployment</h2>
            <p class="service-tier-desc">We build and deploy the actual systems — n8n workflow automation, AI agents trained on your business, compliance monitoring dashboards, and full integrations across your tech stack. You get a fully operational platform, not a slide deck.</p>
            <div class="service-tier-cols">
                <div class="service-tier-col">
                    <h4 class="service-tier-col__title">What we build</h4>
                    <ul class="service-tier-list">
                        <li>n8n automated workflow pipelines</li>
                        <li>AI agents for sales, support &amp; operations</li>
                        <li>Compliance monitoring &amp; alerting systems</li>
                        <li>CRM, ERP &amp; third-party integrations</li>
                        <li>Document automation &amp; generation</li>
                        <li>Real-time operational dashboards</li>
                        <li>Knowledge base &amp; SOP automation</li>
                    </ul>
                </div>
                <div class="service-tier-col">
                    <h4 class="service-tier-col__title">Technology stack</h4>
                    <ul class="service-tier-list">
                        <li>n8n for workflow orchestration</li>
                        <li>Ollama / Claude for AI agents</li>
                        <li>PostgreSQL + pgvector for knowledge graphs</li>
                        <li>Custom REST APIs &amp; webhooks</li>
                        <li>Your existing CRM, ERP &amp; communication tools</li>
                    </ul>
                </div>
            </div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--primary">Start a Project</a>
        </div>
    </div>
</section>

<!-- INDUSTRIES -->
<section class="inner-section-pad">
    <div class="inner-section-header">
        <span class="section-eyebrow">Where we specialize</span>
        <h2 class="section-title">Built for regulated<br>industries.</h2>
    </div>
    <div class="industries-grid-small">
        <a href="<?php echo esc_url( home_url( '/industries/#mortgage' ) ); ?>" class="industry-card-small js-fade" style="--delay:0s">
            <h4>Mortgage &amp; Lending</h4>
            <p>RESPA, TILA, HMDA compliance automation, audit trail management, disclosure workflows.</p>
        </a>
        <a href="<?php echo esc_url( home_url( '/industries/#insurance' ) ); ?>" class="industry-card-small js-fade" style="--delay:0.08s">
            <h4>Insurance</h4>
            <p>Policy documentation, claims workflow automation, state filing tracking, E&amp;O compliance.</p>
        </a>
        <a href="<?php echo esc_url( home_url( '/industries/#healthcare' ) ); ?>" class="industry-card-small js-fade" style="--delay:0.16s">
            <h4>Healthcare</h4>
            <p>HIPAA-aware automation, billing and coding workflows, patient intake, credentialing.</p>
        </a>
        <a href="<?php echo esc_url( home_url( '/industries/#construction' ) ); ?>" class="industry-card-small js-fade" style="--delay:0.24s">
            <h4>Construction &amp; Trades</h4>
            <p>Permit tracking, lien waiver management, subcontractor compliance, job costing automation.</p>
        </a>
    </div>
    <div style="text-align:center;margin-top:2.5rem;">
        <a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>" class="btn btn--ghost">View all industries</a>
    </div>
</section>

<section class="inner-section-pad">
    <div class="inner-section-header">
        <span class="section-eyebrow">What clients say</span>
        <h2 class="section-title">Results that<br>speak for themselves.</h2>
    </div>
    <ae-testimonials></ae-testimonials>
</section>

<ae-cta></ae-cta>

<?php get_footer(); ?>
