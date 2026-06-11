<?php
/**
 * Template Name: Aria Marketplace
 * Description: Public marketplace for AI-generated research and intelligence reports created by Aria
 *
 * @package AESoftwaresTheme
 */

get_header(); ?>

<!-- ── Hero ─────────────────────────────────────────────────────────────── -->
<div class="arm-hero">
    <div class="arm-hero__inner">
        <div class="arm-hero__badge">Powered by Aria · Autonomous AI Agent</div>
        <h1 class="arm-hero__title">Intelligence Reports<br><span>Built by an AI. Ready for You.</span></h1>
        <p class="arm-hero__sub">
            Aria runs continuously — researching markets, scoring prospects, mapping competitors,
            and packaging her findings into actionable PDF reports. Every product here was
            researched, written, and published without human authorship.
        </p>
        <div class="arm-hero__stats">
            <div class="arm-hero__stat"><span id="arm-product-count">—</span><em>Reports Live</em></div>
            <div class="arm-hero__stat"><span>24/7</span><em>Aria Runs</em></div>
            <div class="arm-hero__stat"><span>PA</span><em>Market Focus</em></div>
        </div>
    </div>
</div>

<!-- ── What Aria Does ────────────────────────────────────────────────────── -->
<section class="arm-about">
    <div class="arm-about__inner">
        <div class="arm-about__text">
            <h2>What Aria Researches</h2>
            <p>
                Aria is AE Software Solutions' autonomous intelligence agent. She monitors law firms,
                home services contractors, and adjacent markets across Pennsylvania — tracking
                automation gaps, pricing signals, and decision-maker activity around the clock.
            </p>
            <p>
                When she accumulates enough signal on a topic, she synthesizes it into a structured
                report and publishes it here. Each report includes market sizing, competitive
                positioning, ROI entry points, and in some cases ranked prospect lists with
                contact data.
            </p>
            <ul class="arm-about__list">
                <li><strong>Market Intelligence</strong> — vertical-specific automation opportunity maps with ROI benchmarks</li>
                <li><strong>Competitive Intelligence</strong> — gap analysis across competitors and positioning by service tier</li>
                <li><strong>Prospect Intelligence</strong> — scored and ranked lists of high-fit prospects with decision-maker signals</li>
                <li><strong>Playbooks</strong> — operational strategy docs covering outreach cadence, content calendars, and go-to-market</li>
            </ul>
        </div>
        <div class="arm-about__capabilities">
            <div class="arm-about__cap">
                <div class="arm-about__cap-icon">🔍</div>
                <div class="arm-about__cap-label">Market Scanning</div>
                <div class="arm-about__cap-desc">Monitors Pennsylvania law firms, HVAC, plumbing, and contractor markets for automation signals</div>
            </div>
            <div class="arm-about__cap">
                <div class="arm-about__cap-icon">📊</div>
                <div class="arm-about__cap-label">Competitive Analysis</div>
                <div class="arm-about__cap-desc">Maps competitor positioning, pricing, and service gaps across verticals</div>
            </div>
            <div class="arm-about__cap">
                <div class="arm-about__cap-icon">🎯</div>
                <div class="arm-about__cap-label">Prospect Scoring</div>
                <div class="arm-about__cap-desc">Identifies and ranks high-fit prospects by revenue potential and automation readiness</div>
            </div>
            <div class="arm-about__cap">
                <div class="arm-about__cap-icon">📄</div>
                <div class="arm-about__cap-label">Report Publishing</div>
                <div class="arm-about__cap-desc">Packages research into branded PDF reports with executive summaries and action items</div>
            </div>
        </div>
    </div>
</section>

<!-- ── Product Grid ──────────────────────────────────────────────────────── -->
<section class="arm-catalog">
    <div class="arm-catalog__inner">
        <div class="arm-catalog__header">
            <h2>Available Reports</h2>
            <p>All reports are instant PDF downloads. New reports publish automatically as Aria completes research cycles.</p>
        </div>

        <div class="arm-filters" role="tablist" aria-label="Filter by category">
            <button class="arm-filter active" data-category="all" role="tab" aria-selected="true">All</button>
            <button class="arm-filter" data-category="Market Intelligence" role="tab" aria-selected="false">Market Intelligence</button>
            <button class="arm-filter" data-category="Competitive Intelligence" role="tab" aria-selected="false">Competitive Intelligence</button>
            <button class="arm-filter" data-category="Prospect Intelligence" role="tab" aria-selected="false">Prospect Intelligence</button>
            <button class="arm-filter" data-category="Playbook" role="tab" aria-selected="false">Playbooks</button>
        </div>

        <div id="arm-grid" class="arm-grid" role="tabpanel">
            <div class="arm-loading">
                <div class="arm-loading__spinner"></div>
                <span>Loading reports...</span>
            </div>
        </div>
    </div>
</section>

<!-- ── CTA ───────────────────────────────────────────────────────────────── -->
<section class="arm-cta">
    <div class="arm-cta__inner">
        <h2>Need a Custom Report?</h2>
        <p>Aria can be directed to research a specific vertical, geography, or competitor set. Contact us to scope a custom intelligence package.</p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="arm-cta__btn">Request Custom Research</a>
    </div>
</section>

<style>
/* ── Design tokens ───────────────────────────────────────────────────────── */
.arm-hero, .arm-about, .arm-catalog, .arm-cta {
    --bg:        #07090f;
    --bg2:       #0d1221;
    --card:      #111827;
    --border:    #1e293b;
    --accent:    #00d4ff;
    --accent2:   #0099bb;
    --gold:      #c9a96e;
    --text:      #e2e8f0;
    --muted:     #64748b;
    --radius:    8px;
}

/* ── Hero ────────────────────────────────────────────────────────────────── */
.arm-hero {
    background: linear-gradient(160deg, #07090f 0%, #0d1a35 60%, #0a1520 100%);
    padding: 100px 24px 80px;
    text-align: center;
    border-bottom: 1px solid var(--border);
}
.arm-hero__inner { max-width: 760px; margin: 0 auto; }
.arm-hero__badge {
    display: inline-block;
    background: rgba(0,212,255,.08);
    border: 1px solid rgba(0,212,255,.25);
    color: var(--accent);
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding: 6px 16px;
    border-radius: 20px;
    margin-bottom: 28px;
}
.arm-hero__title {
    font-size: clamp(2.2rem, 5vw, 3.8rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
    margin: 0 0 24px;
}
.arm-hero__title span { color: var(--accent); }
.arm-hero__sub {
    font-size: 1.1rem;
    color: var(--muted);
    line-height: 1.7;
    max-width: 600px;
    margin: 0 auto 48px;
}
.arm-hero__stats {
    display: flex;
    justify-content: center;
    gap: 48px;
    flex-wrap: wrap;
}
.arm-hero__stat { text-align: center; }
.arm-hero__stat span {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent);
    line-height: 1;
}
.arm-hero__stat em {
    font-style: normal;
    font-size: .8rem;
    color: var(--muted);
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-top: 6px;
    display: block;
}

/* ── About ───────────────────────────────────────────────────────────────── */
.arm-about {
    background: var(--bg2);
    padding: 80px 24px;
    border-bottom: 1px solid var(--border);
}
.arm-about__inner {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 64px;
    align-items: start;
}
.arm-about__text h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 20px;
}
.arm-about__text p {
    color: var(--muted);
    line-height: 1.75;
    margin-bottom: 16px;
    font-size: .975rem;
}
.arm-about__list {
    list-style: none;
    padding: 0;
    margin: 24px 0 0;
}
.arm-about__list li {
    padding: 10px 0 10px 20px;
    border-bottom: 1px solid var(--border);
    color: var(--muted);
    font-size: .9rem;
    line-height: 1.6;
    position: relative;
}
.arm-about__list li::before {
    content: '›';
    position: absolute;
    left: 0;
    color: var(--accent);
    font-weight: 700;
}
.arm-about__list li strong { color: var(--text); }
.arm-about__capabilities {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.arm-about__cap {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
}
.arm-about__cap-icon { font-size: 1.6rem; margin-bottom: 10px; }
.arm-about__cap-label {
    font-size: .9rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: 6px;
}
.arm-about__cap-desc { font-size: .8rem; color: var(--muted); line-height: 1.5; }

/* ── Catalog ─────────────────────────────────────────────────────────────── */
.arm-catalog {
    background: var(--bg);
    padding: 80px 24px;
}
.arm-catalog__inner { max-width: 1200px; margin: 0 auto; }
.arm-catalog__header { text-align: center; margin-bottom: 48px; }
.arm-catalog__header h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 12px;
}
.arm-catalog__header p { color: var(--muted); font-size: .95rem; }

/* Filters */
.arm-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: center;
    margin-bottom: 40px;
}
.arm-filter {
    padding: 9px 20px;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--muted);
    border-radius: 24px;
    cursor: pointer;
    font-size: .875rem;
    font-weight: 500;
    transition: all .2s;
}
.arm-filter:hover { border-color: var(--accent); color: var(--accent); }
.arm-filter.active {
    background: var(--accent);
    border-color: var(--accent);
    color: #07090f;
    font-weight: 600;
}

/* Grid */
.arm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

/* Card */
.arm-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: border-color .2s, transform .2s, box-shadow .2s;
}
.arm-card:hover {
    border-color: var(--accent);
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,212,255,.1);
}
.arm-card__cover {
    width: 100%;
    aspect-ratio: 1200/630;
    object-fit: cover;
    display: block;
    background: #0d1221;
}
.arm-card__cover-placeholder {
    width: 100%;
    aspect-ratio: 1200/630;
    background: linear-gradient(135deg, #0d1221 0%, #111827 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--muted);
    font-size: .8rem;
    letter-spacing: .08em;
    text-transform: uppercase;
    border-bottom: 1px solid var(--border);
}
.arm-card__body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
.arm-card__cat {
    display: inline-block;
    background: rgba(0,212,255,.08);
    color: var(--accent);
    border: 1px solid rgba(0,212,255,.2);
    padding: 3px 10px;
    border-radius: 4px;
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: 12px;
    width: fit-content;
}
.arm-card__title {
    font-size: 1.05rem;
    font-weight: 600;
    color: #fff;
    margin: 0 0 10px;
    line-height: 1.4;
}
.arm-card__desc {
    font-size: .875rem;
    color: var(--muted);
    line-height: 1.65;
    flex: 1;
    margin: 0 0 20px;
}
.arm-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}
.arm-card__price {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--accent);
}
.arm-card__btn {
    padding: 9px 18px;
    background: var(--accent);
    color: #07090f;
    border-radius: 6px;
    font-size: .85rem;
    font-weight: 700;
    text-decoration: none;
    transition: background .2s, transform .15s;
    white-space: nowrap;
}
.arm-card__btn:hover { background: var(--accent2); transform: scale(1.04); }

/* Loading state */
.arm-loading {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}
.arm-loading__spinner {
    width: 32px; height: 32px;
    border: 3px solid var(--border);
    border-top-color: var(--accent);
    border-radius: 50%;
    animation: arm-spin .8s linear infinite;
}
@keyframes arm-spin { to { transform: rotate(360deg); } }

.arm-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
}
.arm-empty h3 { color: var(--text); margin-bottom: 8px; font-size: 1.1rem; }

/* ── CTA ─────────────────────────────────────────────────────────────────── */
.arm-cta {
    background: linear-gradient(135deg, #0d1a35 0%, #07090f 100%);
    padding: 80px 24px;
    text-align: center;
    border-top: 1px solid var(--border);
}
.arm-cta__inner { max-width: 600px; margin: 0 auto; }
.arm-cta h2 { font-size: 2rem; font-weight: 700; color: #fff; margin: 0 0 16px; }
.arm-cta p { color: var(--muted); line-height: 1.7; margin-bottom: 32px; }
.arm-cta__btn {
    display: inline-block;
    padding: 14px 32px;
    background: transparent;
    border: 2px solid var(--accent);
    color: var(--accent);
    border-radius: 6px;
    font-weight: 700;
    font-size: .95rem;
    text-decoration: none;
    transition: background .2s, color .2s;
}
.arm-cta__btn:hover { background: var(--accent); color: #07090f; }

/* ── Responsive ──────────────────────────────────────────────────────────── */
@media (max-width: 900px) {
    .arm-about__inner { grid-template-columns: 1fr; gap: 40px; }
}
@media (max-width: 600px) {
    .arm-hero { padding: 64px 20px 48px; }
    .arm-hero__stats { gap: 28px; }
    .arm-about__capabilities { grid-template-columns: 1fr; }
    .arm-grid { grid-template-columns: 1fr; }
}
</style>

<script>
(function () {
    'use strict';

    const grid        = document.getElementById('arm-grid');
    const filters     = document.querySelectorAll('.arm-filter');
    const countEl     = document.getElementById('arm-product-count');
    let   allProducts = [];
    let   activeCategory = 'all';

    /* ── fetch ─────────────────────────────────────────────────────────── */
    async function loadProducts() {
        try {
            const res  = await fetch(ae_aria_settings.products_url);
            if (!res.ok) throw new Error(res.status);
            const data = await res.json();
            allProducts = Array.isArray(data) ? data : (data.products || []);
            if (countEl) countEl.textContent = allProducts.length;
            render();
        } catch (err) {
            grid.innerHTML = '<div class="arm-empty"><h3>Unable to load reports</h3><p>Please try again shortly.</p></div>';
        }
    }

    /* ── render ────────────────────────────────────────────────────────── */
    function render() {
        const list = activeCategory === 'all'
            ? allProducts
            : allProducts.filter(p => p.category === activeCategory);

        if (!list.length) {
            grid.innerHTML = '<div class="arm-empty"><h3>No reports in this category yet</h3><p>Aria publishes new research automatically — check back soon.</p></div>';
            return;
        }

        grid.innerHTML = list.map(product => {
            const cover = product.image
                ? `<img class="arm-card__cover" src="${esc(product.image)}" alt="${esc(product.title)} cover" loading="lazy">`
                : `<div class="arm-card__cover-placeholder">Intelligence Report</div>`;

            const price = parseFloat(product.price) % 1 === 0
                ? `$${parseInt(product.price)}`
                : `$${parseFloat(product.price).toFixed(2)}`;

            return `
<article class="arm-card">
  ${cover}
  <div class="arm-card__body">
    <div class="arm-card__cat">${esc(product.category || 'Report')}</div>
    <h3 class="arm-card__title">${esc(product.title)}</h3>
    <p class="arm-card__desc">${esc(product.description)}</p>
    <div class="arm-card__footer">
      <div class="arm-card__price">${price}</div>
      <a href="${esc(product.url)}" class="arm-card__btn">View Report</a>
    </div>
  </div>
</article>`;
        }).join('');
    }

    /* ── filter tabs ───────────────────────────────────────────────────── */
    filters.forEach(btn => {
        btn.addEventListener('click', function () {
            filters.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            activeCategory = this.dataset.category;
            render();
        });
    });

    /* ── util ──────────────────────────────────────────────────────────── */
    function esc(str) {
        const d = document.createElement('div');
        d.textContent = String(str || '');
        return d.innerHTML;
    }

    /* ── init ──────────────────────────────────────────────────────────── */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadProducts);
    } else {
        loadProducts();
    }
})();
</script>

<?php get_footer(); ?>
