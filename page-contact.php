<?php /* Template Name: Contact */ get_header(); ?>

<div class="page-hero contact-page-hero">
    <div class="page-hero__eyebrow">Get in touch</div>
    <h1 class="page-hero__headline">Let's build<br><em>something great.</em></h1>
    <p class="page-hero__subtitle">Tell us about your project and we'll get back to you within 24 hours with a clear, personalised plan — no templates, no pressure.</p>
</div>

<div class="contact-layout">

    <div class="contact-form-col">
        <form class="contact-form" action="mailto:anthony@aesoftwaresolutions.com" method="post" enctype="text/plain">

            <div class="form-row">
                <div class="form-field">
                    <label for="contact-name">Name</label>
                    <input type="text" id="contact-name" name="name" placeholder="Your full name" required>
                </div>
                <div class="form-field">
                    <label for="contact-email">Email</label>
                    <input type="email" id="contact-email" name="email" placeholder="you@company.com" required>
                </div>
            </div>

            <div class="form-field">
                <label for="contact-company">Company <span style="color:var(--text-muted); font-weight:400;">(optional)</span></label>
                <input type="text" id="contact-company" name="company" placeholder="Your company name">
            </div>

            <div class="form-field">
                <label for="contact-service">What are you looking for?</label>
                <select id="contact-service" name="service" required>
                    <option value="" disabled selected>Select a service</option>
                    <option value="ai-chatbot">Custom AI Chatbot</option>
                    <option value="workflow-automation">Workflow Automation</option>
                    <option value="data-dashboard">Data Dashboard</option>
                    <option value="web-app-dev">Web &amp; App Development</option>
                    <option value="ai-strategy">AI Strategy &amp; Consulting</option>
                    <option value="api-integration">API &amp; Systems Integration</option>
                    <option value="other">Something else</option>
                </select>
            </div>

            <div class="form-field">
                <label for="contact-budget">Approximate budget</label>
                <select id="contact-budget" name="budget">
                    <option value="" disabled selected>Select a range</option>
                    <option value="under-5k">Under $5,000</option>
                    <option value="5k-15k">$5,000 – $15,000</option>
                    <option value="15k-50k">$15,000 – $50,000</option>
                    <option value="50k-plus">$50,000+</option>
                    <option value="not-sure">Not sure yet</option>
                </select>
            </div>

            <div class="form-field">
                <label for="contact-message">Tell us about your project</label>
                <textarea id="contact-message" name="message" placeholder="Describe the problem you're trying to solve, your current tools, and any timeline or constraints." required></textarea>
            </div>

            <button type="submit" class="btn btn--primary">Send message &rarr;</button>
            <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.75rem;">We respond to every inquiry within 24 hours. Your information is never shared.</p>

        </form>
    </div>

    <aside class="contact-sidebar">

        <div class="contact-sidebar__badge">
            <strong>24-hour response</strong>
            <p>We get back to every inquiry within one business day — personally, not with a bot.</p>
        </div>

        <div class="contact-sidebar__info">
            <p class="contact-sidebar__label">Email us directly</p>
            <a href="mailto:anthony@aesoftwaresolutions.com" class="contact-sidebar__email">anthony@aesoftwaresolutions.com</a>
        </div>

        <div class="contact-sidebar__info">
            <p class="contact-sidebar__label">What happens next</p>
            <ol class="contact-sidebar__next">
                <li>We review your submission and do some initial research on your business and industry.</li>
                <li>You receive a personalised response — not a template — within 24 hours.</li>
                <li>We schedule a free 30-minute discovery call to scope your project and answer questions.</li>
            </ol>
        </div>

        <div class="contact-sidebar__info">
            <p class="contact-sidebar__label">Free consultation</p>
            <p style="font-size:0.875rem; color:var(--text-muted); line-height:1.6;">No commitment required. The discovery call is free and there's no pressure to proceed.</p>
        </div>

    </aside>

</div>

<section class="faq-section">
    <h2 class="faq-section__title">Frequently asked questions</h2>

    <div class="faq-item">
        <h3 class="faq-item__q">How long does a typical project take?</h3>
        <p class="faq-item__a">Most projects run 6–10 weeks from kickoff to launch. Simpler automations can be live in as few as 2 weeks; larger multi-integration builds take 3–4 months.</p>
    </div>

    <div class="faq-item">
        <h3 class="faq-item__q">What do you need from us to get started?</h3>
        <p class="faq-item__a">Just a conversation. After the discovery call we'll send a scoping document. From there, access to your tools and a primary point of contact is all we need to begin.</p>
    </div>

    <div class="faq-item">
        <h3 class="faq-item__q">Do you work with businesses outside the US?</h3>
        <p class="faq-item__a">Yes — we work with clients globally. All discovery calls and demos are conducted remotely via video call.</p>
    </div>

    <div class="faq-item faq-item--last">
        <h3 class="faq-item__q">Do you offer ongoing support after launch?</h3>
        <p class="faq-item__a">All projects include a 30-day post-launch support window. Monthly retainers are available for ongoing optimisation, monitoring, and feature additions.</p>
    </div>
</section>

<?php get_footer(); ?>
