<?php
/**
 * The template for displaying the front page — v4.0 Matthieu Givelet Inspired
 *
 * @package AE_Softwares
 */

get_header();
?>

<!-- 1. HERO: OVERSIZED SEAMLESS REVEAL -->
<section class="hero-container">
  <div class="hero-main-title">
    <span style="display:block;">Helping your</span>
    <span style="display:block;">business work</span>
    <span style="display:block;color:var(--accent-highlight);">better.</span>
  </div>

  <div class="hero-description-grid">
    <div class="hero-meta-col">
      [ Focus ]<br>
      Business Systems &amp; Operations
      <br>
      <span style="display:inline-block;padding:2px 8px;border:1px solid var(--border-strong);border-radius:4px;margin-top:8px;color:var(--text);font-weight:500;">
        Est. 2024 &middot; Veteran-Owned
      </span>
    </div>

    <p class="hero-desc-lead">
      We partner with small businesses to untangle daily workflows, build clear customer-facing websites, and put practical systems in place that free up your time.
    </p>

    <div class="hero-meta-col" style="text-align:right;">
      [ Location ]<br>
      Chambersburg, PA<br>
      Serving nationwide
    </div>
  </div>
</section>

<!-- 2. EDITORIAL STATEMENT / PHILOSOPHY -->
<section style="padding:100px 40px;max-width:1400px;margin:0 auto;display:grid;grid-template-columns:280px 1fr 200px;gap:60px;align-items:flex-start;border-top:1px solid var(--border);" id="approach">
  <div style="font-family:var(--font-mono);font-size:0.85rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-secondary);">
    [ Approach ]
  </div>
  
  <div style="font-size:clamp(1.5rem, 2.8vw, 2.4rem);font-weight:400;line-height:1.3;letter-spacing:-0.03em;">
    01 &nbsp; Always looking for <strong>simple, thoughtful solutions</strong> that fit the way your business actually operates and feel good to use. <span style="color:var(--text-secondary);">No unnecessary complexity or bloated software.</span>
  </div>

  <div style="display:flex;flex-direction:column;gap:16px;">
    <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" style="font-size:0.88rem;font-weight:600;text-decoration:none;color:var(--text);">
      &rarr; More about us
    </a>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" style="font-size:0.88rem;font-weight:600;text-decoration:none;color:var(--text);">
      &rarr; Book a discovery
    </a>
  </div>
</section>

<!-- 3. 2-COLUMN SELECTED SOLUTIONS SHOWCASE (Matthieu Grid) -->
<section class="works-section" id="work">
  <div class="works-header">
    <h2 style="font-size:clamp(2rem, 4vw, 3.2rem);font-weight:700;letter-spacing:-0.03em;text-transform:uppercase;">
      Selected Solutions
    </h2>
    <span style="font-family:var(--font-mono);font-size:0.85rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-secondary);">
      [ 03 Case Systems ]
    </span>
  </div>

  <div class="works-grid">
    <!-- Work 01 -->
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="work-card">
      <div class="work-preview-frame">
        <div style="width:85%;height:75%;background:#FFFFFF;border:1px solid var(--border-strong);border-radius:8px;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,0.04);display:flex;flex-direction:column;justify-content:space-between;">
          <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--border);padding-bottom:12px;">
            <div style="display:flex;gap:6px;">
              <span style="width:8px;height:8px;border-radius:50%;background:var(--border-strong);"></span>
              <span style="width:8px;height:8px;border-radius:50%;background:var(--border-strong);"></span>
              <span style="width:8px;height:8px;border-radius:50%;background:var(--border-strong);"></span>
            </div>
            <span style="font-family:var(--font-mono);font-size:0.7rem;color:var(--text-tertiary);">CLIENT PORTAL &amp; WORKFLOW</span>
          </div>
          <div style="padding:16px 0;">
            <div style="height:10px;background:var(--surface-subtle);border-radius:4px;margin-bottom:10px;width:75%;"></div>
            <div style="height:10px;background:var(--surface-subtle);border-radius:4px;margin-bottom:10px;width:50%;"></div>
            <div style="display:flex;gap:16px;margin-top:16px;">
              <div style="flex:1;padding:12px;background:var(--surface-subtle);border-radius:4px;font-family:var(--font-mono);font-size:0.75rem;">
                <div>Weekly Hours Saved</div>
                <div style="font-weight:700;font-size:1.1rem;color:var(--text);">14.5 hrs</div>
              </div>
              <div style="flex:1;padding:12px;background:var(--surface-subtle);border-radius:4px;font-family:var(--font-mono);font-size:0.75rem;">
                <div>Inquiry Response</div>
                <div style="font-weight:700;font-size:1.1rem;color:var(--text);">&lt; 3 mins</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-top:18px;">
        <div style="display:flex;align-items:baseline;gap:12px;">
          <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">01</span>
          <h3 style="font-size:1.35rem;font-weight:700;letter-spacing:-0.02em;">Operational Workflow Redesign</h3>
        </div>
        <span style="font-size:1.2rem;">&rarr;</span>
      </div>
      <p style="font-size:0.9rem;color:var(--text-secondary);margin-top:6px;padding-left:32px;line-height:1.5;">
        Centralized inquiry tracking and follow-up automation for service contractors.
      </p>
    </a>

    <!-- Work 02 -->
    <a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" class="work-card">
      <div class="work-preview-frame" style="background:#EAECE8;">
        <div style="width:85%;height:75%;background:#FFFFFF;border:1px solid rgba(42,69,53,0.2);border-radius:8px;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,0.04);display:flex;flex-direction:column;justify-content:space-between;">
          <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--border);padding-bottom:12px;">
            <div style="display:flex;gap:6px;">
              <span style="width:8px;height:8px;border-radius:50%;background:var(--accent-highlight);"></span>
              <span style="width:8px;height:8px;border-radius:50%;background:var(--border-strong);"></span>
            </div>
            <span style="font-family:var(--font-mono);font-size:0.7rem;color:var(--accent-highlight);">CUSTOM WEB ARCHITECTURE</span>
          </div>
          <div style="padding:16px 0;">
            <div style="height:10px;background:var(--surface-subtle);border-radius:4px;margin-bottom:10px;width:90%;"></div>
            <div style="height:10px;background:var(--surface-subtle);border-radius:4px;margin-bottom:10px;width:40%;"></div>
            <div style="display:flex;gap:16px;margin-top:16px;">
              <div style="flex:1;padding:12px;background:#FFFFFF;border-radius:4px;font-family:var(--font-mono);font-size:0.75rem;">
                <div>Speed Score</div>
                <div style="font-weight:700;font-size:1.1rem;color:var(--text);">99 / 100</div>
              </div>
              <div style="flex:1;padding:12px;background:#FFFFFF;border-radius:4px;font-family:var(--font-mono);font-size:0.75rem;">
                <div>Lead Conversion</div>
                <div style="font-weight:700;font-size:1.1rem;color:var(--text);">+34%</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-top:18px;">
        <div style="display:flex;align-items:baseline;gap:12px;">
          <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">02</span>
          <h3 style="font-size:1.35rem;font-weight:700;letter-spacing:-0.02em;">Bespoke Brand &amp; Web Platform</h3>
        </div>
        <span style="font-size:1.2rem;">&rarr;</span>
      </div>
      <p style="font-size:0.9rem;color:var(--text-secondary);margin-top:6px;padding-left:32px;line-height:1.5;">
        High-performance editorial web presence with direct booking and customer intake.
      </p>
    </a>
  </div>
</section>

<!-- 4. CORE SERVICES / CAPABILITIES -->
<section style="padding:80px 40px 120px;max-width:1400px;margin:0 auto;border-top:1px solid var(--border);" id="services">
  <div class="works-header">
    <h2 style="font-size:clamp(2rem, 4vw, 3.2rem);font-weight:700;letter-spacing:-0.03em;text-transform:uppercase;">
      How We Help
    </h2>
    <span style="font-family:var(--font-mono);font-size:0.85rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-secondary);">
      [ 04 Core Services ]
    </span>
  </div>

  <div style="display:flex;flex-direction:column;">
    <div style="border-bottom:1px solid var(--border);padding:32px 0;display:grid;grid-template-columns:80px 1.5fr 2fr 100px;align-items:center;">
      <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">01</span>
      <h3 style="font-size:1.6rem;font-weight:700;letter-spacing:-0.02em;">Business Review &amp; Audit</h3>
      <p style="font-size:0.95rem;color:var(--text-secondary);line-height:1.5;padding-right:20px;">
        We analyze your daily operations, find bottlenecks slowing down your team, and outline a straightforward plan of action.
      </p>
      <span style="text-align:right;font-family:var(--font-mono);font-size:0.8rem;color:var(--text);font-weight:600;">[ Plan ]</span>
    </div>

    <div style="border-bottom:1px solid var(--border);padding:32px 0;display:grid;grid-template-columns:80px 1.5fr 2fr 100px;align-items:center;">
      <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">02</span>
      <h3 style="font-size:1.6rem;font-weight:700;letter-spacing:-0.02em;">Workflow Automation</h3>
      <p style="font-size:0.95rem;color:var(--text-secondary);line-height:1.5;padding-right:20px;">
        Connect disjointed apps, eliminate manual copy-pasting, and ensure quotes and follow-ups happen automatically.
      </p>
      <span style="text-align:right;font-family:var(--font-mono);font-size:0.8rem;color:var(--text);font-weight:600;">[ Streamline ]</span>
    </div>

    <div style="border-bottom:1px solid var(--border);padding:32px 0;display:grid;grid-template-columns:80px 1.5fr 2fr 100px;align-items:center;">
      <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">03</span>
      <h3 style="font-size:1.6rem;font-weight:700;letter-spacing:-0.02em;">Modern Websites</h3>
      <p style="font-size:0.95rem;color:var(--text-secondary);line-height:1.5;padding-right:20px;">
        Fast, accessible, and clean web design that represents your expertise and converts visitors into conversations.
      </p>
      <span style="text-align:right;font-family:var(--font-mono);font-size:0.8rem;color:var(--text);font-weight:600;">[ Build ]</span>
    </div>

    <div style="border-bottom:1px solid var(--border);padding:32px 0;display:grid;grid-template-columns:80px 1.5fr 2fr 100px;align-items:center;">
      <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">04</span>
      <h3 style="font-size:1.6rem;font-weight:700;letter-spacing:-0.02em;">CRM &amp; Systems Setup</h3>
      <p style="font-size:0.95rem;color:var(--text-secondary);line-height:1.5;padding-right:20px;">
        Organize leads, client history, and documents into a simple, reliable hub tailored to your staff.
      </p>
      <span style="text-align:right;font-family:var(--font-mono);font-size:0.8rem;color:var(--text);font-weight:600;">[ Support ]</span>
    </div>
  </div>
</section>

<?php
get_footer();
