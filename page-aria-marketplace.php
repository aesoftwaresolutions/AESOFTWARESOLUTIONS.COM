<?php
/**
 * Template Name: Research Reports
 * Description: Aria-generated market intelligence reports — public listing + request form
 *
 * @package AESoftwaresTheme
 */

get_header(); ?>

<!-- HERO -->
<div class="page-hero reports-page-hero">
    <div class="page-hero__eyebrow">Market Intelligence</div>
    <h1 class="page-hero__headline">Research<br><em>by Aria.</em></h1>
    <p class="page-hero__subtitle" data-reveal-text>Deep market intelligence reports on PA industries — law firms, home services, mortgage, and more. Researched and written by our autonomous AI agent.</p>
</div>

<!-- REPORTS GRID -->
<section class="reports-section">
    <div class="reports-section__inner">
        <div class="reports-grid" id="reports-grid">
            <div class="reports-loading">Loading reports&hellip;</div>
        </div>
    </div>
</section>

<!-- REQUEST FORM -->
<section class="report-request-section">
    <div class="report-request-inner">
        <div class="report-request-header">
            <span class="section-eyebrow">Custom Research</span>
            <h2 class="report-request-title">Need a specific report?</h2>
            <p class="report-request-sub">Tell us your industry, market, or question. Aria will research it and deliver a custom intelligence report within 48 hours.</p>
        </div>
        <form class="report-request-form" id="report-request-form" novalidate>
            <div class="form-row form-row--2">
                <div class="form-field">
                    <label for="rr-name">Your Name</label>
                    <input type="text" id="rr-name" name="name" placeholder="Jane Smith" required>
                </div>
                <div class="form-field">
                    <label for="rr-email">Email Address</label>
                    <input type="email" id="rr-email" name="email" placeholder="jane@company.com" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label for="rr-business">Business / Company</label>
                    <input type="text" id="rr-business" name="business" placeholder="Smith Law Group" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label for="rr-topic">What do you want researched?</label>
                    <textarea id="rr-topic" name="topic" rows="4" placeholder="e.g. AI automation adoption among Pennsylvania personal injury law firms — competitors, pricing, and gap analysis" required></textarea>
                </div>
            </div>
            <div class="form-submit-row">
                <button type="submit" class="report-request-btn" id="rr-submit">
                    <span class="btn-label">Request Report</span>
                    <span class="btn-sending" hidden>Sending&hellip;</span>
                </button>
            </div>
            <div class="form-feedback" id="rr-feedback" hidden></div>
        </form>
    </div>
</section>

<style>
/* ── Reports Hero ──────────────────────────────────────────────────────────── */
.reports-page-hero {
    padding-top: clamp(100px, 16vw, 160px);
    padding-bottom: clamp(48px, 6vw, 96px);
    padding-left: clamp(20px, 5vw, 64px);
    padding-right: clamp(20px, 5vw, 64px);
    max-width: var(--max-width);
    margin: 0 auto;
}

/* ── Reports Grid ──────────────────────────────────────────────────────────── */
.reports-section {
    padding: clamp(48px, 5vw, 80px) clamp(20px, 5vw, 64px);
    background: var(--bg-surface);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

.reports-section__inner {
    max-width: var(--max-width);
    margin: 0 auto;
}

.reports-loading {
    color: var(--text-muted);
    font-size: 0.95rem;
    padding: 40px 0;
}

.reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.report-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 28px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: border-color var(--transition), box-shadow var(--transition);
}

.report-card:hover {
    border-color: var(--border-hover);
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
}

.report-card__tag {
    display: inline-block;
    font-size: 0.7rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--accent);
    font-weight: 600;
}

.report-card__title {
    font-size: 1.05rem;
    font-weight: 500;
    color: var(--text);
    line-height: 1.4;
}

.report-card__date {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-top: auto;
}

.report-card__link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: var(--accent);
    font-weight: 500;
    margin-top: 4px;
    transition: opacity 0.2s;
}

.report-card__link:hover { opacity: 0.75; }

.reports-empty {
    color: var(--text-muted);
    font-size: 0.95rem;
    padding: 40px 0;
}

/* ── Request Form ──────────────────────────────────────────────────────────── */
.report-request-section {
    padding: clamp(64px, 7vw, 112px) clamp(20px, 5vw, 64px);
}

.report-request-inner {
    max-width: 680px;
    margin: 0 auto;
}

.report-request-header {
    margin-bottom: 2.5rem;
}

.report-request-title {
    font-family: var(--font-editorial);
    font-size: clamp(1.75rem, 3.5vw, 2.75rem);
    font-weight: 400;
    color: var(--text);
    margin-bottom: 0.75rem;
}

.report-request-sub {
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.6;
}

.report-request-form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.form-row { display: flex; gap: 1.25rem; }
.form-row--2 > .form-field { flex: 1; }

.form-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
}

.form-field label {
    font-size: 0.8rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--text-muted);
    font-weight: 500;
}

.form-field input,
.form-field textarea {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    color: var(--text);
    font-size: 0.95rem;
    padding: 12px 16px;
    transition: border-color var(--transition);
    resize: vertical;
}

.form-field input:focus,
.form-field textarea:focus {
    outline: none;
    border-color: var(--accent);
}

.form-field input::placeholder,
.form-field textarea::placeholder { color: var(--text-muted); opacity: 0.6; }

.form-submit-row { margin-top: 0.5rem; }

.report-request-btn {
    display: inline-block;
    background: var(--accent);
    color: #080808;
    border: none;
    border-radius: var(--radius);
    padding: 14px 36px;
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    cursor: pointer;
    transition: background var(--transition), opacity var(--transition);
}

.report-request-btn:hover { background: var(--accent-dark); }
.report-request-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.form-feedback {
    padding: 14px 18px;
    border-radius: var(--radius);
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.form-feedback.is-success {
    background: rgba(201, 169, 110, 0.1);
    border: 1px solid rgba(201, 169, 110, 0.3);
    color: var(--accent);
}

.form-feedback.is-error {
    background: rgba(239, 68, 68, 0.08);
    border: 1px solid rgba(239, 68, 68, 0.25);
    color: #f87171;
}

@media (max-width: 600px) {
    .form-row--2 { flex-direction: column; }
}
</style>

<script>
(function () {
    // ── Load reports from REST endpoint ────────────────────────────────────
    const grid = document.getElementById('reports-grid');

    fetch(<?php echo json_encode( get_rest_url( null, 'aesoftwares/v1/aria/reports' ) ); ?>)
        .then(r => r.ok ? r.json() : Promise.reject(r.status))
        .then(reports => {
            if (!reports || reports.length === 0) {
                grid.innerHTML = '<p class="reports-empty">No reports published yet — check back soon.</p>';
                return;
            }
            grid.innerHTML = reports.map(r => `
                <div class="report-card">
                    <span class="report-card__tag">${escHtml(r.industry || 'Research')}</span>
                    <h3 class="report-card__title">${escHtml(r.title)}</h3>
                    <span class="report-card__date">${escHtml(r.date)}</span>
                    <a class="report-card__link" href="${escHtml(r.url)}" target="_blank" rel="noopener">
                        Download PDF &rarr;
                    </a>
                </div>
            `).join('');
        })
        .catch(() => {
            grid.innerHTML = '<p class="reports-empty">Reports temporarily unavailable.</p>';
        });

    // ── Request form ────────────────────────────────────────────────────────
    const form     = document.getElementById('report-request-form');
    const feedback = document.getElementById('rr-feedback');
    const submitBtn = document.getElementById('rr-submit');
    const btnLabel  = submitBtn.querySelector('.btn-label');
    const btnSending = submitBtn.querySelector('.btn-sending');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        feedback.hidden = true;
        feedback.className = 'form-feedback';

        const name     = form.querySelector('[name="name"]').value.trim();
        const email    = form.querySelector('[name="email"]').value.trim();
        const business = form.querySelector('[name="business"]').value.trim();
        const topic    = form.querySelector('[name="topic"]').value.trim();

        if (!name || !email || !business || !topic) {
            showFeedback('Please fill in all fields.', 'error');
            return;
        }

        submitBtn.disabled = true;
        btnLabel.hidden = true;
        btnSending.hidden = false;

        try {
            const res = await fetch(<?php echo json_encode( get_rest_url( null, 'aesoftwares/v1/aria/report-request' ) ); ?>, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, email, business, topic }),
            });
            const data = await res.json();
            if (res.ok) {
                showFeedback('Request received — Aria will begin research shortly. Expect your report within 48 hours.', 'success');
                form.reset();
            } else {
                showFeedback(data.message || 'Something went wrong. Please try again.', 'error');
            }
        } catch {
            showFeedback('Network error — please try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            btnLabel.hidden = false;
            btnSending.hidden = true;
        }
    });

    function showFeedback(msg, type) {
        feedback.textContent = msg;
        feedback.className = 'form-feedback is-' + type;
        feedback.hidden = false;
    }

    function escHtml(str) {
        const d = document.createElement('div');
        d.textContent = String(str);
        return d.innerHTML;
    }
})();
</script>

<?php get_footer(); ?>
