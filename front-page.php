<?php
/**
 * The template for displaying the front page — v4.2 Matthieu Givelet Animated Engine
 *
 * @package AE_Softwares
 */

get_header();
?>

<!-- 1. HERO: OVERSIZED SEAMLESS REVEAL -->
<section class="hero-container">
  <div class="hero-main-title">
    <div class="mask-line"><span>Helping your</span></div>
    <div class="mask-line" style="transition-delay:0.12s;"><span>business work</span></div>
    <div class="mask-line" style="transition-delay:0.24s;"><span style="color:var(--accent-highlight);">better.</span></div>
  </div>

  <div class="hero-description-grid" data-reveal>
    <div class="hero-meta-col delay-1" data-reveal>
      [ Focus ]<br>
      Business Systems &amp; Operations
      <br>
      <span style="display:inline-block;padding:2px 8px;border:1px solid var(--border-strong);border-radius:4px;margin-top:8px;color:var(--text);font-weight:500;">
        Est. 2024 &middot; Small Business Consulting
      </span>
    </div>

    <p class="hero-desc-lead delay-2" data-reveal>
      We partner with small businesses to untangle daily workflows, build clear customer-facing websites, and put practical systems in place that free up your time.
    </p>

    <div class="hero-meta-col delay-3" style="text-align:right;" data-reveal>
      [ Location ]<br>
      Chambersburg, PA<br>
      Serving nationwide
    </div>
  </div>
</section>

<!-- 2. EDITORIAL STATEMENT / PHILOSOPHY -->
<section class="approach-section" id="approach">
  <div style="font-family:var(--font-mono);font-size:0.85rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-secondary);" data-reveal>
    [ Approach ]
  </div>
  
  <div style="font-size:clamp(1.5rem, 2.8vw, 2.4rem);font-weight:400;line-height:1.3;letter-spacing:-0.03em;" class="delay-1" data-reveal>
    01 &nbsp; Always looking for <strong>simple, thoughtful solutions</strong> that fit the way your business actually operates and feel good to use. <span style="color:var(--text-secondary);">No unnecessary complexity or bloated software.</span>
  </div>

  <div style="display:flex;flex-direction:column;gap:16px;" class="delay-2" data-reveal>
    <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" style="font-size:0.88rem;font-weight:600;text-decoration:none;color:var(--text);" class="statement-aside-link">
      &rarr; More about us
    </a>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" style="font-size:0.88rem;font-weight:600;text-decoration:none;color:var(--text);" class="statement-aside-link">
      &rarr; Book a discovery
    </a>
  </div>
</section>

<!-- 3. 2-COLUMN SELECTED SOLUTIONS SHOWCASE (Matthieu Grid with Animated Cards) -->
<section class="works-section" id="work">
  <div class="works-header" data-reveal>
    <h2 style="font-size:clamp(2rem, 4vw, 3.2rem);font-weight:700;letter-spacing:-0.03em;text-transform:uppercase;">
      Selected Work &amp; Systems
    </h2>
    <a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" style="font-family:var(--font-mono);font-size:0.85rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text);text-decoration:none;">
      [ See All Work &rarr; ]
    </a>
  </div>

  <div class="works-grid">
    <!-- Project 01: Home Services Platform -->
    <a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" class="work-card delay-1" data-reveal="scale">
      <div class="work-preview-frame">
        <div style="width:90%;height:84%;background:#FFFFFF;border:1px solid var(--border-strong);border-radius:6px;overflow:hidden;box-shadow:0 12px 36px rgba(0,0,0,0.06);display:flex;flex-direction:column;">
          <div style="background:#F2F2EF;padding:8px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;gap:5px;">
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
            </div>
            <span style="font-family:var(--font-mono);font-size:0.65rem;color:var(--text-secondary);letter-spacing:0.04em;">Apex Home Services</span>
          </div>
          <div style="flex:1;position:relative;overflow:hidden;background:#F9F9F7;">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/template-previews/home-services.jpg' ); ?>" alt="Home Services Website Project Preview" style="width:100%;height:100%;object-fit:cover;object-position:top;" loading="lazy">
          </div>
        </div>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-top:18px;">
        <div style="display:flex;align-items:baseline;gap:12px;">
          <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">01</span>
          <h3 style="font-size:1.35rem;font-weight:700;letter-spacing:-0.02em;">Apex Home Services &amp; Dispatch</h3>
        </div>
        <span class="work-arrow" style="font-size:1.2rem;">&rarr;</span>
      </div>
      <p style="font-size:0.9rem;color:var(--text-secondary);margin-top:6px;padding-left:32px;line-height:1.5;">
        Local service brand architecture, automated estimate booking, and real-time mobile customer intake.
      </p>
    </a>

    <!-- Project 02: Professional Legal Practice -->
    <a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" class="work-card delay-2" data-reveal="scale">
      <div class="work-preview-frame" style="background:#EFEFEA;">
        <div style="width:90%;height:84%;background:#FFFFFF;border:1px solid var(--border-strong);border-radius:6px;overflow:hidden;box-shadow:0 12px 36px rgba(0,0,0,0.06);display:flex;flex-direction:column;">
          <div style="background:#F2F2EF;padding:8px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;gap:5px;">
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
            </div>
            <span style="font-family:var(--font-mono);font-size:0.65rem;color:var(--text-secondary);letter-spacing:0.04em;">Vanguard Legal Counsel</span>
          </div>
          <div style="flex:1;position:relative;overflow:hidden;background:#F9F9F7;">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/template-previews/law-firm.jpg' ); ?>" alt="Law Firm Practice Website Preview" style="width:100%;height:100%;object-fit:cover;object-position:top;" loading="lazy">
          </div>
        </div>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-top:18px;">
        <div style="display:flex;align-items:baseline;gap:12px;">
          <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">02</span>
          <h3 style="font-size:1.35rem;font-weight:700;letter-spacing:-0.02em;">Vanguard Legal Counsel</h3>
        </div>
        <span class="work-arrow" style="font-size:1.2rem;">&rarr;</span>
      </div>
      <p style="font-size:0.9rem;color:var(--text-secondary);margin-top:6px;padding-left:32px;line-height:1.5;">
        Editorial legal platform focused on trust, practice area navigation, and confidential client consultation intake.
      </p>
    </a>

    <!-- Project 03: Modern Dental Practice -->
    <a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" class="work-card delay-1" data-reveal="scale" style="margin-top:20px;">
      <div class="work-preview-frame">
        <div style="width:90%;height:84%;background:#FFFFFF;border:1px solid var(--border-strong);border-radius:6px;overflow:hidden;box-shadow:0 12px 36px rgba(0,0,0,0.06);display:flex;flex-direction:column;">
          <div style="background:#F2F2EF;padding:8px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;gap:5px;">
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
            </div>
            <span style="font-family:var(--font-mono);font-size:0.65rem;color:var(--text-secondary);letter-spacing:0.04em;">Lumina Dental Care</span>
          </div>
          <div style="flex:1;position:relative;overflow:hidden;background:#F9F9F7;">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/template-previews/dental-practice.jpg' ); ?>" alt="Dental Clinic Website Preview" style="width:100%;height:100%;object-fit:cover;object-position:top;" loading="lazy">
          </div>
        </div>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-top:18px;">
        <div style="display:flex;align-items:baseline;gap:12px;">
          <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">03</span>
          <h3 style="font-size:1.35rem;font-weight:700;letter-spacing:-0.02em;">Lumina Dental Studio</h3>
        </div>
        <span class="work-arrow" style="font-size:1.2rem;">&rarr;</span>
      </div>
      <p style="font-size:0.9rem;color:var(--text-secondary);margin-top:6px;padding-left:32px;line-height:1.5;">
        Calm, patient-friendly web portal with online appointment scheduling, team bios, and service outlines.
      </p>
    </a>

    <!-- Project 04: Real Estate & Property Group -->
    <a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" class="work-card delay-2" data-reveal="scale" style="margin-top:20px;">
      <div class="work-preview-frame" style="background:#EAECE8;">
        <div style="width:90%;height:84%;background:#FFFFFF;border:1px solid var(--border-strong);border-radius:6px;overflow:hidden;box-shadow:0 12px 36px rgba(0,0,0,0.06);display:flex;flex-direction:column;">
          <div style="background:#F2F2EF;padding:8px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;gap:5px;">
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
              <span style="width:7px;height:7px;border-radius:50%;background:#D0D0CB;"></span>
            </div>
            <span style="font-family:var(--font-mono);font-size:0.65rem;color:var(--text-secondary);letter-spacing:0.04em;">Meridian Real Estate</span>
          </div>
          <div style="flex:1;position:relative;overflow:hidden;background:#F9F9F7;">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/template-previews/real-estate-agent.jpg' ); ?>" alt="Real Estate Website Preview" style="width:100%;height:100%;object-fit:cover;object-position:top;" loading="lazy">
          </div>
        </div>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-top:18px;">
        <div style="display:flex;align-items:baseline;gap:12px;">
          <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">04</span>
          <h3 style="font-size:1.35rem;font-weight:700;letter-spacing:-0.02em;">Meridian Property Group</h3>
        </div>
        <span class="work-arrow" style="font-size:1.2rem;">&rarr;</span>
      </div>
      <p style="font-size:0.9rem;color:var(--text-secondary);margin-top:6px;padding-left:32px;line-height:1.5;">
        Property showcase architecture featuring interactive listings, agent profiles, and direct lead capture.
      </p>
    </a>
  </div>
</section>

<!-- 4. CORE SERVICES / CAPABILITIES -->
<section style="padding:80px 40px 120px;max-width:1400px;margin:0 auto;border-top:1px solid var(--border);" id="services">
  <div class="works-header" data-reveal>
    <h2 style="font-size:clamp(2rem, 4vw, 3.2rem);font-weight:700;letter-spacing:-0.03em;text-transform:uppercase;">
      How We Help
    </h2>
    <span style="font-family:var(--font-mono);font-size:0.85rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-secondary);">
      [ 04 Core Services ]
    </span>
  </div>

  <div style="display:flex;flex-direction:column;">
    <div class="cap-item-row delay-1" data-reveal>
      <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">01</span>
      <h3 style="font-size:1.6rem;font-weight:700;letter-spacing:-0.02em;">Business Review &amp; Audit</h3>
      <p style="font-size:0.95rem;color:var(--text-secondary);line-height:1.5;padding-right:20px;">
        We analyze your daily operations, find bottlenecks slowing down your team, and outline a straightforward plan of action.
      </p>
      <span style="text-align:right;font-family:var(--font-mono);font-size:0.8rem;color:var(--text);font-weight:600;">[ Plan ]</span>
    </div>

    <div class="cap-item-row delay-2" data-reveal>
      <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">02</span>
      <h3 style="font-size:1.6rem;font-weight:700;letter-spacing:-0.02em;">Workflow Automation</h3>
      <p style="font-size:0.95rem;color:var(--text-secondary);line-height:1.5;padding-right:20px;">
        Connect disjointed apps, eliminate manual copy-pasting, and ensure quotes and follow-ups happen automatically.
      </p>
      <span style="text-align:right;font-family:var(--font-mono);font-size:0.8rem;color:var(--text);font-weight:600;">[ Streamline ]</span>
    </div>

    <div class="cap-item-row delay-3" data-reveal>
      <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-tertiary);">03</span>
      <h3 style="font-size:1.6rem;font-weight:700;letter-spacing:-0.02em;">Modern Websites</h3>
      <p style="font-size:0.95rem;color:var(--text-secondary);line-height:1.5;padding-right:20px;">
        Fast, accessible, and clean web design that represents your expertise and converts visitors into conversations.
      </p>
      <span style="text-align:right;font-family:var(--font-mono);font-size:0.8rem;color:var(--text);font-weight:600;">[ Build ]</span>
    </div>

    <div class="cap-item-row delay-4" data-reveal>
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
