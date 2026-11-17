<?php /* Template Name: Software */ get_header(); ?>

<!-- HERO -->
<div class="page-hero software-page-hero">
    <div class="page-hero__eyebrow">Paid Software</div>
    <h1 class="page-hero__headline">Tools we built<br>for businesses that<br><em>think operationally.</em></h1>
    <p class="page-hero__subtitle" data-reveal-text>Software products we built from the same operational thinking we use with clients, made into self serve tools.</p>
</div>

<!-- PRODUCT 01: aeCRM -->
<section class="product-section" id="aecrm">
    <div class="product-section__inner">

        <div class="product-section__meta js-fade" style="--delay:0s">
            <span class="product-section__num">01</span>
            <span class="product-section__tag">CRM &amp; Operations Platform</span>
        </div>

        <div class="product-section__head js-fade" style="--delay:0.1s">
            <h2 class="product-section__title">aeCRM</h2>
            <p class="product-section__pitch">A CRM built for solo operators and small teams. Self hosted and AI assisted, for businesses that outgrew their spreadsheets but can't justify Salesforce.</p>
        </div>

        <div class="product-section__body">

            <div class="product-section__copy js-fade" style="--delay:0.2s">
                <div class="product-feature-list-block">
                    <h3 class="product-feature-list__label">What's included</h3>
                    <ul class="product-feature-list">
                        <li>Contact &amp; deal pipeline management with stage automation</li>
                        <li>Built in AI agent that drafts emails, summarizes calls, and suggests next actions</li>
                        <li>Activity timeline with an audit trail</li>
                        <li>Email integration with automated follow up sequences</li>
                        <li>Document storage with metadata search</li>
                        <li>Reporting dashboard with custom metrics</li>
                        <li>Role based access and team permissions</li>
                        <li>REST API for custom integrations</li>
                    </ul>
                </div>

                <div class="product-section__chips">
                    <span class="product-chip">Next.js</span>
                    <span class="product-chip">PostgreSQL</span>
                    <span class="product-chip">Self hosted</span>
                    <span class="product-chip">AI agent</span>
                    <span class="product-chip">Audit trail</span>
                </div>

                <div class="product-section__cta-row">
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--primary">Request a demo</a>
                </div>
            </div>

            <div class="product-section__visual js-fade" style="--delay:0.25s">
                <!-- Inline SVG product mockup: CRM dashboard preview -->
                <svg viewBox="0 0 480 320" xmlns="http://www.w3.org/2000/svg" class="product-mockup">
                    <defs>
                        <linearGradient id="aecrm-bg" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#141414"/>
                            <stop offset="100%" stop-color="#0a0a0a"/>
                        </linearGradient>
                    </defs>
                    <!-- Window frame -->
                    <rect x="8" y="8" width="464" height="304" rx="10" fill="url(#aecrm-bg)" stroke="rgba(255,255,255,0.08)"/>
                    <!-- Toolbar -->
                    <rect x="8" y="8" width="464" height="32" fill="#0a0a0a"/>
                    <circle cx="22" cy="24" r="4" fill="#3a3a3a"/>
                    <circle cx="38" cy="24" r="4" fill="#3a3a3a"/>
                    <circle cx="54" cy="24" r="4" fill="#3a3a3a"/>
                    <rect x="80" y="18" width="120" height="12" rx="3" fill="rgba(255,255,255,0.04)"/>
                    <!-- Sidebar -->
                    <rect x="8" y="40" width="100" height="272" fill="rgba(0,0,0,0.4)"/>
                    <rect x="22" y="60" width="72" height="10" rx="2" fill="rgba(201,169,110,0.7)"/>
                    <rect x="22" y="82" width="60" height="8" rx="2" fill="rgba(255,255,255,0.18)"/>
                    <rect x="22" y="98" width="56" height="8" rx="2" fill="rgba(255,255,255,0.18)"/>
                    <rect x="22" y="114" width="64" height="8" rx="2" fill="rgba(255,255,255,0.18)"/>
                    <rect x="22" y="130" width="50" height="8" rx="2" fill="rgba(255,255,255,0.18)"/>
                    <rect x="22" y="146" width="58" height="8" rx="2" fill="rgba(255,255,255,0.18)"/>
                    <!-- Main: pipeline columns -->
                    <rect x="120" y="52" width="80" height="14" rx="2" fill="rgba(255,255,255,0.5)"/>
                    <rect x="120" y="80" width="106" height="60" rx="6" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.06)"/>
                    <rect x="130" y="90" width="60" height="6" rx="1" fill="rgba(201,169,110,0.7)"/>
                    <rect x="130" y="102" width="80" height="5" rx="1" fill="rgba(255,255,255,0.25)"/>
                    <rect x="130" y="114" width="54" height="5" rx="1" fill="rgba(255,255,255,0.18)"/>
                    <rect x="130" y="124" width="34" height="10" rx="3" fill="rgba(201,169,110,0.18)"/>

                    <rect x="120" y="150" width="106" height="60" rx="6" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.06)"/>
                    <rect x="130" y="160" width="50" height="6" rx="1" fill="rgba(255,255,255,0.4)"/>
                    <rect x="130" y="172" width="74" height="5" rx="1" fill="rgba(255,255,255,0.22)"/>
                    <rect x="130" y="184" width="48" height="5" rx="1" fill="rgba(255,255,255,0.18)"/>

                    <rect x="234" y="80" width="106" height="60" rx="6" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.06)"/>
                    <rect x="244" y="90" width="70" height="6" rx="1" fill="rgba(255,255,255,0.4)"/>
                    <rect x="244" y="102" width="80" height="5" rx="1" fill="rgba(255,255,255,0.22)"/>
                    <rect x="244" y="114" width="40" height="5" rx="1" fill="rgba(255,255,255,0.18)"/>

                    <rect x="234" y="150" width="106" height="60" rx="6" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.06)"/>
                    <rect x="244" y="160" width="56" height="6" rx="1" fill="rgba(201,169,110,0.6)"/>
                    <rect x="244" y="172" width="80" height="5" rx="1" fill="rgba(255,255,255,0.22)"/>
                    <rect x="244" y="184" width="48" height="5" rx="1" fill="rgba(255,255,255,0.18)"/>

                    <!-- AI agent panel right -->
                    <rect x="350" y="52" width="114" height="252" rx="6" fill="rgba(201,169,110,0.05)" stroke="rgba(201,169,110,0.18)"/>
                    <circle cx="365" cy="74" r="5" fill="rgba(201,169,110,0.85)"/>
                    <rect x="378" y="70" width="60" height="8" rx="2" fill="rgba(201,169,110,0.7)"/>
                    <rect x="362" y="92" width="92" height="6" rx="1" fill="rgba(255,255,255,0.28)"/>
                    <rect x="362" y="104" width="84" height="6" rx="1" fill="rgba(255,255,255,0.22)"/>
                    <rect x="362" y="116" width="80" height="6" rx="1" fill="rgba(255,255,255,0.2)"/>
                    <rect x="362" y="142" width="92" height="32" rx="4" fill="rgba(0,0,0,0.4)" stroke="rgba(201,169,110,0.15)"/>
                    <rect x="370" y="152" width="60" height="5" rx="1" fill="rgba(255,255,255,0.45)"/>
                    <rect x="370" y="162" width="44" height="5" rx="1" fill="rgba(255,255,255,0.25)"/>
                    <rect x="362" y="184" width="92" height="32" rx="4" fill="rgba(0,0,0,0.4)" stroke="rgba(201,169,110,0.15)"/>
                    <rect x="370" y="194" width="50" height="5" rx="1" fill="rgba(255,255,255,0.45)"/>
                    <rect x="370" y="204" width="64" height="5" rx="1" fill="rgba(255,255,255,0.25)"/>
                </svg>
            </div>

        </div>
    </div>
</section>

<!-- PRODUCT 02: Cash Flow Analyzer -->
<section class="product-section product-section--alt" id="cashflow">
    <div class="product-section__inner">

        <div class="product-section__meta js-fade" style="--delay:0s">
            <span class="product-section__num">02</span>
            <span class="product-section__tag">Personal Finance &middot; Cash Flow</span>
        </div>

        <div class="product-section__head js-fade" style="--delay:0.1s">
            <h2 class="product-section__title">Cash Flow Intelligence</h2>
            <p class="product-section__pitch">Trough analysis to help prevent overdrafts. Upload a bank CSV, detect every recurring bill and paycheck, and see exactly how much you can safely spend today without bouncing a future payment.</p>
        </div>

        <div class="product-section__body">

            <div class="product-section__visual js-fade" style="--delay:0.2s">
                <!-- Inline SVG mockup: cash flow projection dashboard -->
                <svg viewBox="0 0 480 320" xmlns="http://www.w3.org/2000/svg" class="product-mockup product-mockup--light">
                    <defs>
                        <linearGradient id="cf-bg" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#ffffff"/>
                            <stop offset="100%" stop-color="#f5f5f3"/>
                        </linearGradient>
                    </defs>
                    <rect x="8" y="8" width="464" height="304" rx="10" fill="url(#cf-bg)" stroke="rgba(0,0,0,0.08)"/>

                    <!-- Header -->
                    <rect x="24" y="24" width="180" height="14" rx="2" fill="#1c1c1c"/>
                    <rect x="24" y="44" width="120" height="8" rx="1" fill="rgba(0,0,0,0.35)"/>
                    <!-- Balance card right -->
                    <rect x="340" y="20" width="120" height="42" rx="8" fill="#eaf2ff" stroke="#cbdcfb"/>
                    <rect x="352" y="30" width="76" height="7" rx="1" fill="#1d4ed8"/>
                    <rect x="352" y="44" width="60" height="12" rx="1" fill="#1e3a8a"/>

                    <!-- Safe to spend card -->
                    <rect x="24" y="76" width="200" height="80" rx="10" fill="#ecfdf5" stroke="#a7f3d0"/>
                    <rect x="40" y="90" width="100" height="7" rx="1" fill="#047857"/>
                    <rect x="40" y="106" width="120" height="24" rx="2" fill="#065f46"/>
                    <rect x="40" y="138" width="160" height="5" rx="1" fill="rgba(0,0,0,0.3)"/>

                    <!-- Lowest balance card -->
                    <rect x="236" y="76" width="220" height="80" rx="10" fill="#f8fafc" stroke="rgba(0,0,0,0.07)"/>
                    <rect x="252" y="90" width="120" height="7" rx="1" fill="rgba(0,0,0,0.5)"/>
                    <rect x="252" y="106" width="80" height="18" rx="2" fill="#1c1c1c"/>
                    <rect x="252" y="132" width="140" height="5" rx="1" fill="rgba(0,0,0,0.3)"/>

                    <!-- Timeline rows -->
                    <rect x="24" y="170" width="120" height="8" rx="1" fill="#1c1c1c"/>

                    <!-- Row 1 -->
                    <rect x="24" y="190" width="432" height="30" rx="6" fill="#ffffff" stroke="rgba(0,0,0,0.05)"/>
                    <rect x="40" y="200" width="22" height="12" rx="2" fill="#1c1c1c"/>
                    <rect x="74" y="200" width="80" height="6" rx="1" fill="#1c1c1c"/>
                    <rect x="74" y="210" width="60" height="5" rx="1" fill="rgba(0,0,0,0.4)"/>
                    <rect x="380" y="200" width="60" height="6" rx="1" fill="#10b981"/>
                    <rect x="380" y="210" width="40" height="5" rx="1" fill="rgba(0,0,0,0.35)"/>

                    <!-- Row 2 -->
                    <rect x="24" y="224" width="432" height="30" rx="6" fill="#ffffff" stroke="rgba(0,0,0,0.05)"/>
                    <rect x="40" y="234" width="22" height="12" rx="2" fill="#1c1c1c"/>
                    <rect x="74" y="234" width="60" height="6" rx="1" fill="#1c1c1c"/>
                    <rect x="74" y="244" width="80" height="5" rx="1" fill="rgba(0,0,0,0.4)"/>
                    <rect x="380" y="234" width="48" height="6" rx="1" fill="#ef4444"/>
                    <rect x="380" y="244" width="38" height="5" rx="1" fill="rgba(0,0,0,0.35)"/>

                    <!-- Row 3: lowest point highlight -->
                    <rect x="24" y="258" width="432" height="34" rx="6" fill="#fffbeb" stroke="#fbcfa1"/>
                    <rect x="40" y="270" width="22" height="12" rx="2" fill="#1c1c1c"/>
                    <rect x="74" y="270" width="50" height="6" rx="1" fill="#1c1c1c"/>
                    <rect x="130" y="270" width="60" height="10" rx="5" fill="#fde68a"/>
                    <rect x="138" y="273" width="44" height="4" rx="1" fill="#b45309"/>
                    <rect x="74" y="282" width="74" height="5" rx="1" fill="rgba(0,0,0,0.4)"/>
                    <rect x="380" y="270" width="48" height="6" rx="1" fill="#ef4444"/>
                    <rect x="380" y="280" width="50" height="5" rx="1" fill="rgba(0,0,0,0.4)"/>
                </svg>
            </div>

            <div class="product-section__copy js-fade" style="--delay:0.25s">
                <div class="product-feature-list-block">
                    <h3 class="product-feature-list__label">Built into the product</h3>
                    <ul class="product-feature-list">
                        <li>Bank statement CSV upload with automatic detection of recurring patterns</li>
                        <li>Trough analysis engine that finds the day your balance will hit its lowest</li>
                        <li>"Safe to spend today" calculation that accounts for every upcoming bill</li>
                        <li>Shortfall alerts with the deposit amount needed to avoid overdraft</li>
                        <li>Manual rule editor for weekly, biweekly, and monthly transactions</li>
                        <li>Interactive timeline showing running balance after every event</li>
                        <li>45 day forecast horizon, configurable</li>
                        <li>No bank login or third party connection required</li>
                    </ul>
                </div>

                <div class="product-section__chips">
                    <span class="product-chip">React</span>
                    <span class="product-chip">CSV intelligence</span>
                    <span class="product-chip">Browser only</span>
                    <span class="product-chip">No bank login</span>
                    <span class="product-chip">Privacy first</span>
                </div>

                <div class="product-section__cta-row">
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--primary">Get access</a>
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?topic=cashflow-demo" class="btn btn--ghost">Request a demo</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- BOTTOM CTA -->
<section class="software-cta">
    <div class="software-cta__inner js-fade" style="--delay:0s">
        <span class="software-cta__eyebrow">Need something different?</span>
        <h2 class="software-cta__title">Custom builds<br>on the same foundation.</h2>
        <p class="software-cta__sub">If you have a recurring operational problem worth turning into a tool, tell us about it and we will tell you if we can build it.</p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--primary">Start a conversation &rarr;</a>
    </div>
</section>

<?php get_footer(); ?>
