<?php /* Template Name: Work */ get_header(); ?>

<div class="page-hero work-page-hero">
    <div class="page-hero__eyebrow">Work</div>
    <h1 class="page-hero__headline">Recent<br><em>websites.</em></h1>
    <p class="page-hero__subtitle" data-reveal-text>A selection of websites I have designed and built, plus examples of the automations and tools I set up for small businesses.</p>
</div>

<section class="work-section">
    <div class="work-grid">

        <!-- 01: Monarch MVC -->
        <article class="work-card work-card--featured js-fade" style="--delay:0s">
            <div class="work-card__header">
                <span class="work-card__num">01</span>
                <span class="work-card__tag">Health Tech Platform</span>
            </div>
            <a href="https://monarchmvc.com" target="_blank" rel="noopener" aria-label="Visit monarchmvc.com">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/work/monarch-mvc.jpg' ); ?>" alt="Monarch MVC home page" loading="lazy" decoding="async" width="1440" height="900" class="work-card__shot">
            </a>
            <h2 class="work-card__title">Monarch MVC</h2>
            <p class="work-card__desc">A men's health platform with a custom patient portal and provider dashboard, covering patient onboarding, blood testing and treatment plans.</p>
            <ul class="work-card__outcomes">
                <li>Custom patient onboarding and assessment flow</li>
                <li>Provider portal and patient management</li>
                <li>Integrated lab ordering and results dashboard</li>
                <li>Subscription billing with FSA/HSA eligibility</li>
            </ul>
            <div class="work-card__meta">
                <span class="work-card__tech">Web App &middot; Patient Portal &middot; Health Tech</span>
                <a href="https://monarchmvc.com" target="_blank" rel="noopener" class="work-card__link">
                    monarchmvc.com <span aria-hidden="true">&#8599;</span>
                </a>
            </div>
        </article>

        <!-- 02: Anthony Pioquinto -->
        <article class="work-card js-fade" style="--delay:0.08s">
            <div class="work-card__header">
                <span class="work-card__num">02</span>
                <span class="work-card__tag">My own site</span>
            </div>
            <a href="https://anthonypioquinto.com" target="_blank" rel="noopener" aria-label="Visit anthonypioquinto.com">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/work/anthony-pioquinto.jpg' ); ?>" alt="Anthony Pioquinto portfolio home page" loading="lazy" decoding="async" width="1440" height="900" class="work-card__shot">
            </a>
            <h2 class="work-card__title">Anthony Pioquinto</h2>
            <p class="work-card__desc">My own portfolio site, not a client project. Dark editorial design, built to show my real estate lending work, digital projects, and consulting services.</p>
            <ul class="work-card__outcomes">
                <li>Editorial dark theme design with custom typography</li>
                <li>Services and projects showcase sections</li>
                <li>Lead capture and direct contact integration</li>
                <li>Written for real estate and professional audiences</li>
            </ul>
            <div class="work-card__meta">
                <span class="work-card__tech">Portfolio Site &middot; WordPress &middot; Custom Theme</span>
                <a href="https://anthonypioquinto.com" target="_blank" rel="noopener" class="work-card__link">
                    anthonypioquinto.com <span aria-hidden="true">&#8599;</span>
                </a>
            </div>
        </article>

        <!-- 03: Learn English with Evie -->
        <article class="work-card js-fade" style="--delay:0.16s">
            <div class="work-card__header">
                <span class="work-card__num">03</span>
                <span class="work-card__tag">Creator & Education Brand</span>
            </div>
            <a href="https://learnenglishwithevie.com" target="_blank" rel="noopener" aria-label="Visit learnenglishwithevie.com">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/work/learn-english-with-evie.jpg' ); ?>" alt="Learn English with Evie home page" loading="lazy" decoding="async" width="1440" height="900" class="work-card__shot">
            </a>
            <h2 class="work-card__title">Learn English with Evie</h2>
            <p class="work-card__desc">A custom built brand and marketing site for a UK based English educator, turning YouTube viewers and podcast listeners into paying students. Fast, responsive pages with a clear path from free content to memberships and courses.</p>
            <ul class="work-card__outcomes">
                <li>Custom design system tuned to the creator's brand</li>
                <li>Funnel from video content to membership signups</li>
                <li>Newsletter and lead capture integration</li>
                <li>Mobile first, performance optimised build</li>
            </ul>
            <div class="work-card__meta">
                <span class="work-card__tech">Custom Build &middot; Responsive Design &middot; Education</span>
                <a href="https://learnenglishwithevie.com" target="_blank" rel="noopener" class="work-card__link">
                    learnenglishwithevie.com <span aria-hidden="true">&#8599;</span>
                </a>
            </div>
        </article>

        <!-- 04: n8n Workflow Integrations -->
        <article class="work-card js-fade" style="--delay:0.24s">
            <div class="work-card__header">
                <span class="work-card__num">04</span>
                <span class="work-card__tag">Example: Workflow Automation</span>
            </div>
            <h2 class="work-card__title">n8n Automation Workflows</h2>
            <p class="work-card__desc">Workflow automations built on n8n that connect CRMs, payment processors, communication tools, and internal databases, so fewer handoffs happen by hand.</p>
            <ul class="work-card__outcomes">
                <li>CRM to email sequences triggered by sales events</li>
                <li>Invoice generation and payment reconciliation pipelines</li>
                <li>Lead routing and assignment across multiple sales reps</li>
                <li>Slack and Teams alerts for business critical events</li>
                <li>Webhook driven integrations between the tools you already use</li>
            </ul>
            <div class="work-card__meta">
                <span class="work-card__tech">n8n &middot; API Integration &middot; Workflow Automation</span>
            </div>
        </article>

        <!-- 05: Text Reminders -->
        <article class="work-card js-fade" style="--delay:0.32s">
            <div class="work-card__header">
                <span class="work-card__num">05</span>
                <span class="work-card__tag">Example: SMS Automation</span>
            </div>
            <h2 class="work-card__title">Text Reminder System</h2>
            <p class="work-card__desc">An SMS automation system that sends personalised, time sensitive reminders based on customer behaviour and appointment data, without manual follow up from the team.</p>
            <ul class="work-card__outcomes">
                <li>Appointment and deadline reminders via SMS</li>
                <li>Behaviour triggered follow up sequences</li>
                <li>Two way messaging with automatic routing to human agents</li>
                <li>Opt in and opt out handling</li>
            </ul>
            <div class="work-card__meta">
                <span class="work-card__tech">Twilio &middot; SMS &middot; Automation &middot; CRM Integration</span>
            </div>
        </article>

        <!-- 06: AI Chatbots (general showcase) -->
        <article class="work-card js-fade" style="--delay:0.40s">
            <div class="work-card__header">
                <span class="work-card__num">06</span>
                <span class="work-card__tag">Example: Conversational AI</span>
            </div>
            <h2 class="work-card__title">Custom AI Assistants</h2>
            <p class="work-card__desc">AI chatbots trained on a business's own knowledge base and added to its website, portal, or inbox. Each assistant answers common questions and hands off to a person when one is needed.</p>
            <ul class="work-card__outcomes">
                <li>Training on your own documents and FAQs</li>
                <li>Live chat widget and email inbox integrations</li>
                <li>Handoff logic for complex or sensitive queries</li>
                <li>Analytics dashboard for questions answered and handed off</li>
            </ul>
            <div class="work-card__meta">
                <span class="work-card__tech">OpenAI &middot; LangChain &middot; RAG &middot; Chat Widget</span>
            </div>
        </article>

        <!-- 07: Data Dashboards -->
        <article class="work-card js-fade" style="--delay:0.48s">
            <div class="work-card__header">
                <span class="work-card__num">07</span>
                <span class="work-card__tag">Example: Data & Analytics</span>
            </div>
            <h2 class="work-card__title">Business Intelligence Dashboards</h2>
            <p class="work-card__desc">Data dashboards that pull from sources like Stripe, Salesforce, Google Analytics, and internal databases and show the metrics that drive decisions. Built for founders and operators who need clarity, not noise.</p>
            <ul class="work-card__outcomes">
                <li>Multiple source data ingestion and cleanup</li>
                <li>Live KPI tracking with trends</li>
                <li>Role based access for teams and stakeholders</li>
                <li>Automated weekly PDF reports via email</li>
            </ul>
            <div class="work-card__meta">
                <span class="work-card__tech">Python &middot; PostgreSQL &middot; Retool &middot; API Aggregation</span>
            </div>
        </article>

    </div>
</section>

<section class="work-cta-bar">
    <div class="work-cta-bar__inner">
        <p class="work-cta-bar__text">Have a project in mind? Tell me about it.</p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--primary">Start a conversation &rarr;</a>
    </div>
</section>

<?php get_footer(); ?>