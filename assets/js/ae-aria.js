/**
 * ae-aria.js — Aria dashboard panel for the admin console.
 * Polls /wp-json/aesoftwares/v1/aria/status every 5s.
 * Renders: status card, phase bars, heartbeat detail rows.
 */
(function () {
  'use strict';

  const STATUS_URL = (window.ae_aria_settings && window.ae_aria_settings.status_url)
    ? window.ae_aria_settings.status_url
    : '/wp-json/aesoftwares/v1/aria/status';

  const PHASE_ORDER = ['B','A','C','D','S','E','F','G','H'];
  const PHASE_NAMES = {
    B:'Market Intel', A:'Infrastructure', C:'Prospect Lists',
    D:'Product Dev', S:'Social Presence', E:'Outreach',
    F:'Finance', G:'Account Setup', H:'Synthesis'
  };

  let pollTimer = null;

  // ── Bootstrap ────────────────────────────────────────────────────────────
  function init() {
    const panel = document.getElementById('panel-aria');
    if (!panel) return;

    panel.innerHTML = buildSkeleton();
    poll();
    pollTimer = setInterval(poll, 5000);

    // Stop polling when tab is hidden
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) { clearInterval(pollTimer); pollTimer = null; }
      else if (!pollTimer) { pollTimer = setInterval(poll, 5000); poll(); }
    });
  }

  // ── Fetch & render ────────────────────────────────────────────────────────
  function poll() {
    fetch(STATUS_URL, { credentials: 'include' })
      .then(r => r.json())
      .then(render)
      .catch(() => renderOffline());
  }

  function render(data) {
    setLastUpdated(data.pushed_at);

    // ── Status card ───────────────────────────────────────────────────────
    const online  = data.online !== false;
    const paused  = !!data.paused;
    const waiting = !!data.waiting_approval;
    const genning = (data.active_requests || 0) > 0;

    let dotCls, statusText;
    if (!online)       { dotCls = 'aria-dot--offline'; statusText = 'OFFLINE'; }
    else if (paused)   { dotCls = 'aria-dot--paused';  statusText = 'PAUSED'; }
    else if (waiting)  { dotCls = 'aria-dot--waiting'; statusText = 'WAITING'; }
    else if (genning)  { dotCls = 'aria-dot--gen';     statusText = 'GENERATING'; }
    else               { dotCls = 'aria-dot--idle';    statusText = 'IDLE'; }

    setEl('aria-status-dot',  '', dotCls, true);
    setEl('aria-status-text', statusText);
    setEl('aria-model',  data.model   ? data.model                    : '—');
    setEl('aria-vram',   data.vram_gb ? data.vram_gb.toFixed(1)+' GB VRAM' : '');
    setEl('aria-reqs',   data.active_requests + ' active request' + (data.active_requests === 1 ? '' : 's'));

    // ── Current task ──────────────────────────────────────────────────────
    const phase = data.current_phase || '?';
    setEl('aria-phase-label', phase !== '?' ? 'Phase ' + phase + '  ·  ' + (PHASE_NAMES[phase] || '') : '—');
    setEl('aria-task-text',   data.current_task || 'No active task.');

    // ── Heartbeat rows ────────────────────────────────────────────────────
    let stateStr = data.paused ? 'PAUSED' : (data.waiting_approval ? 'waiting approval · phase ' + (data.waiting_for_phase || '?') : 'running');
    if (data.last_message_scan) {
      try {
        const d = new Date(parseFloat(data.last_message_scan) * 1000);
        stateStr += '  ·  last scan ' + d.toLocaleTimeString();
      } catch(e) {}
    }
    setEl('aria-hb-action',  data.pending_instruction || '—');
    setEl('aria-hb-state',   stateStr);
    const browser = (data.browser_task && data.browser_step)
      ? data.browser_task + '  →  ' + data.browser_step
      : (data.browser_task || '—');
    setEl('aria-hb-browser', browser);
    setEl('aria-hb-waiting', data.waiting_for_reply ? '"' + data.waiting_for_reply + '"' : '—',
          data.waiting_for_reply ? 'aria-hb-val--warn' : '', !!data.waiting_for_reply);

    // ── Phase progress bars ───────────────────────────────────────────────
    const stats = data.phase_stats || {};
    PHASE_ORDER.forEach(ph => {
      const s   = stats[ph] || { done:0, doing:0, todo:0 };
      const tot = s.done + s.doing + s.todo;
      const pct = tot > 0 ? Math.round((s.done / tot) * 100) : 0;
      const col = s.done === tot && tot > 0 ? '#3fb950'
                : s.doing > 0              ? '#d29922'
                :                            '#58a6ff';
      const bar = document.getElementById('aria-bar-' + ph);
      if (bar) { bar.style.width = pct + '%'; bar.style.background = col; }
      setEl('aria-pct-' + ph, tot > 0 ? s.done + '/' + tot + '  ' + pct + '%' : '—');
    });
  }

  function renderOffline() {
    setEl('aria-status-dot', '', 'aria-dot--offline', true);
    setEl('aria-status-text', 'UNREACHABLE');
  }

  // ── Skeleton HTML ─────────────────────────────────────────────────────────
  function buildSkeleton() {
    const phaseBars = PHASE_ORDER.map(ph => `
      <div class="aria-phase-row">
        <span class="aria-phase-name">${ph}&nbsp;&nbsp;${PHASE_NAMES[ph] || ''}</span>
        <div class="aria-bar-bg"><div class="aria-bar-fill" id="aria-bar-${ph}"></div></div>
        <span class="aria-pct" id="aria-pct-${ph}">—</span>
      </div>`).join('');

    return `
<div class="aria-panel">

  <div class="aria-status-card">
    <div class="aria-status-row">
      <span class="aria-dot aria-dot--offline" id="aria-status-dot"></span>
      <span class="aria-status-text" id="aria-status-text">CONNECTING</span>
      <span class="aria-model-name" id="aria-model">—</span>
      <span class="aria-vram-label" id="aria-vram"></span>
    </div>
    <div class="aria-metrics" id="aria-reqs">0 active requests</div>
  </div>

  <div class="aria-section-label">CURRENT TASK</div>
  <div class="aria-task-card">
    <div class="aria-task-phase" id="aria-phase-label">—</div>
    <div class="aria-task-text" id="aria-task-text">Waiting for first heartbeat push…</div>
  </div>

  <div class="aria-section-label">HEARTBEAT DETAIL</div>
  <div class="aria-hb-card">
    ${hbRow('aria-hb-action',  'Action')}
    ${hbRow('aria-hb-state',   'State')}
    ${hbRow('aria-hb-browser', 'Browser')}
    ${hbRow('aria-hb-waiting', 'Waiting')}
  </div>

  <div class="aria-section-label">PHASE PROGRESS</div>
  <div class="aria-phases-card">${phaseBars}</div>

  <div class="aria-footer">
    Last push: <span id="aria-last-updated">—</span>
  </div>

</div>`;
  }

  function hbRow(id, label) {
    return `<div class="aria-hb-row">
      <span class="aria-hb-lbl">${label}</span>
      <span class="aria-hb-val" id="${id}">—</span>
    </div>`;
  }

  // ── Helpers ───────────────────────────────────────────────────────────────
  function setEl(id, text, cls, replaceClass) {
    const el = document.getElementById(id);
    if (!el) return;
    if (text !== undefined) el.textContent = text;
    if (cls !== undefined) {
      if (replaceClass) {
        // Remove all aria-dot--* / aria-hb-val--* classes then add new one
        el.className = el.className.replace(/\b(aria-dot|aria-hb-val)--\S+/g, '').trim();
        if (cls) el.classList.add(...cls.split(' ').filter(Boolean));
      } else if (cls) {
        el.classList.add(cls);
      }
    }
  }

  function setLastUpdated(ts) {
    const el = document.getElementById('aria-last-updated');
    if (!el || !ts) return;
    const d   = new Date(ts * 1000);
    el.textContent = d.toLocaleTimeString();
  }

  // ── Wait for DOM ──────────────────────────────────────────────────────────
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
