/**
 * <ae-cashflow> — Cash Flow Intelligence web component
 *
 * Port of the React CashFlowAnalyzer to vanilla JS + Shadow DOM,
 * themed to match the AE Softwares dark editorial palette.
 *
 * Features:
 *   - CSV upload with auto-detection of recurring weekly/biweekly/monthly rules
 *   - Trough analysis: pinpoints lowest-balance day in forecast window
 *   - Safe-to-spend / shortfall calculation
 *   - Manual rule add/remove
 *   - Interactive timeline with running balance
 */
(function () {
  'use strict';

  const tpl = document.createElement('template');
  tpl.innerHTML = `
  <style>
    :host { display: block; width: 100%; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; color: #EDEDE9; }
    * { box-sizing: border-box; }

    .wrap { display: grid; grid-template-columns: 1fr 1.4fr; gap: 1.25rem; }
    @media (max-width: 900px) { .wrap { grid-template-columns: 1fr; } }

    .card {
      background: #141414;
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 8px;
      padding: 1.5rem;
    }
    .card + .card { margin-top: 1.25rem; }

    .card-title {
      font-size: 0.75rem;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: #888884;
      font-weight: 600;
      margin: 0 0 1.25rem 0;
    }

    /* ── Balance bar ─────────────────────────────────────────────────────── */
    .balance-bar {
      display: flex; align-items: center; justify-content: space-between;
      gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.25rem;
      background: #141414; border: 1px solid rgba(201,169,110,0.18);
      border-radius: 8px; padding: 1.25rem 1.5rem;
    }
    .balance-bar h2 { font-size: 1.1rem; font-weight: 500; margin: 0; color: #EDEDE9; }
    .balance-bar p  { font-size: 0.78rem; color: #888884; margin: 0.2rem 0 0; }
    .balance-input-wrap {
      display: flex; align-items: center; gap: 0.5rem;
      background: rgba(201,169,110,0.08); border: 1px solid rgba(201,169,110,0.3);
      border-radius: 6px; padding: 0.55rem 0.9rem;
    }
    .balance-input-wrap span { color: #C9A96E; font-weight: 600; }
    .balance-input-wrap input {
      background: transparent; border: 0; outline: 0;
      color: #C9A96E; font-size: 1.5rem; font-weight: 300;
      width: 120px; font-family: inherit;
      letter-spacing: -0.02em;
    }

    /* ── Upload zone ──────────────────────────────────────────────────────── */
    .upload-zone {
      border: 1.5px dashed rgba(201,169,110,0.35);
      border-radius: 6px; padding: 1.25rem 1rem;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; transition: background 0.2s, border-color 0.2s;
      text-align: center;
    }
    .upload-zone:hover { background: rgba(201,169,110,0.05); border-color: rgba(201,169,110,0.55); }
    .upload-zone span { font-size: 0.85rem; color: #C9A96E; font-weight: 500; letter-spacing: 0.04em; }
    .upload-desc { font-size: 0.78rem; color: #888884; margin: 0 0 0.85rem; line-height: 1.55; }
    input[type=file] { display: none; }

    /* ── Rules list ──────────────────────────────────────────────────────── */
    .rules-list { display: flex; flex-direction: column; gap: 0.5rem; max-height: 380px; overflow-y: auto; padding-right: 4px; }
    .rules-list::-webkit-scrollbar { width: 4px; }
    .rules-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 2px; }

    .rule-row {
      display: flex; justify-content: space-between; align-items: center;
      padding: 0.75rem 0.85rem; background: #1a1a1a;
      border: 1px solid rgba(255,255,255,0.06); border-radius: 6px;
    }
    .rule-row__name { font-size: 0.85rem; font-weight: 600; color: #EDEDE9; }
    .rule-row__meta { font-size: 0.7rem; color: #888884; margin-top: 0.15rem; text-transform: capitalize; }
    .rule-row__amount { font-weight: 700; font-size: 0.9rem; margin-right: 0.5rem; font-variant-numeric: tabular-nums; }
    .rule-row__amount--pos { color: #6ee7b7; }
    .rule-row__amount--neg { color: #fda4af; }
    .rule-row__remove {
      background: transparent; border: 0; color: #6a6a66; cursor: pointer;
      padding: 0.25rem; transition: color 0.2s;
    }
    .rule-row__remove:hover { color: #fda4af; }
    .rule-row__right { display: flex; align-items: center; }

    /* ── Add rule form ───────────────────────────────────────────────────── */
    .add-form { margin-top: 1rem; display: grid; gap: 0.5rem; grid-template-columns: 1fr 1fr; }
    .add-form .full { grid-column: 1 / -1; }
    .add-form input, .add-form select {
      background: #0e0e0e; border: 1px solid rgba(255,255,255,0.1);
      color: #EDEDE9; padding: 0.55rem 0.75rem; font-size: 0.85rem;
      border-radius: 5px; font-family: inherit; outline: none;
      width: 100%;
    }
    .add-form input:focus, .add-form select:focus { border-color: rgba(201,169,110,0.5); }
    .date-row { display: flex; gap: 0.5rem; align-items: center; }
    .date-row label { font-size: 0.7rem; color: #888884; letter-spacing: 0.08em; text-transform: uppercase; white-space: nowrap; }
    .add-btn {
      background: #C9A96E; color: #080808; border: 0; padding: 0.7rem;
      border-radius: 5px; font-weight: 600; font-size: 0.85rem; cursor: pointer;
      letter-spacing: 0.04em; transition: background 0.2s; font-family: inherit;
    }
    .add-btn:hover { background: #a8853d; }
    .add-btn:disabled { opacity: 0.4; cursor: not-allowed; }

    /* ── Forecast horizon ────────────────────────────────────────────────── */
    .horizon-row {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 1.5rem;
    }
    .horizon-row input[type=date] {
      background: #0e0e0e; border: 1px solid rgba(255,255,255,0.1);
      color: #EDEDE9; padding: 0.5rem 0.75rem; border-radius: 5px;
      font-size: 0.85rem; font-family: inherit; outline: none;
    }
    .horizon-row input[type=date]:focus { border-color: rgba(201,169,110,0.5); }

    /* ── Insight cards ───────────────────────────────────────────────────── */
    .insights { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
    @media (max-width: 600px) { .insights { grid-template-columns: 1fr; } }

    .insight {
      padding: 1.25rem; border-radius: 8px;
      border: 1.5px solid;
      display: flex; flex-direction: column; gap: 0.4rem;
    }
    .insight--safe { background: rgba(110,231,183,0.06); border-color: rgba(110,231,183,0.25); }
    .insight--alert { background: rgba(253,164,175,0.06); border-color: rgba(253,164,175,0.3); }
    .insight--neutral { background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08); }

    .insight__label {
      font-size: 0.65rem; letter-spacing: 0.18em; text-transform: uppercase;
      color: #888884; font-weight: 600;
    }
    .insight__value {
      font-size: 1.85rem; font-weight: 300; letter-spacing: -0.025em;
      font-variant-numeric: tabular-nums;
    }
    .insight--safe .insight__value { color: #6ee7b7; }
    .insight--alert .insight__value { color: #fda4af; }
    .insight--neutral .insight__value { color: #EDEDE9; }
    .insight__note { font-size: 0.72rem; color: #888884; line-height: 1.55; margin-top: 0.15rem; }

    /* ── Timeline ────────────────────────────────────────────────────────── */
    .timeline-head {
      font-size: 0.72rem; letter-spacing: 0.16em; text-transform: uppercase;
      color: #888884; font-weight: 600; padding-bottom: 0.75rem;
      border-bottom: 1px solid rgba(255,255,255,0.06); margin-bottom: 0.5rem;
    }
    .timeline { max-height: 360px; overflow-y: auto; padding-right: 4px; }
    .timeline::-webkit-scrollbar { width: 4px; }
    .timeline::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 2px; }

    .event {
      display: flex; align-items: center; justify-content: space-between;
      padding: 0.7rem 0.5rem; border-radius: 6px; border: 1px solid transparent;
      transition: background 0.15s;
    }
    .event:hover { background: rgba(255,255,255,0.02); }
    .event--lowest { background: rgba(251,191,36,0.06); border-color: rgba(251,191,36,0.25); }

    .event__left { display: flex; align-items: center; gap: 1rem; }
    .event__date {
      width: 44px; text-align: center; flex-shrink: 0;
      padding: 0.25rem 0; border-right: 1px solid rgba(255,255,255,0.08);
    }
    .event__date .m {
      font-size: 0.62rem; font-weight: 700; color: #6a6a66;
      letter-spacing: 0.1em; text-transform: uppercase; line-height: 1;
    }
    .event__date .d {
      font-size: 1.1rem; font-weight: 400; color: #EDEDE9; margin-top: 0.15rem; line-height: 1;
    }
    .event__name { font-size: 0.85rem; font-weight: 500; color: #EDEDE9; }
    .lowest-tag {
      display: inline-block; margin-left: 0.5rem; font-size: 0.6rem;
      background: rgba(251,191,36,0.25); color: #fbbf24;
      padding: 0.12rem 0.45rem; border-radius: 30px;
      letter-spacing: 0.08em; text-transform: uppercase; font-weight: 600;
      vertical-align: middle;
    }
    .event__right { text-align: right; }
    .event__amount {
      font-weight: 600; font-size: 0.85rem; font-variant-numeric: tabular-nums;
    }
    .event__amount--pos { color: #6ee7b7; }
    .event__amount--neg { color: #fda4af; }
    .event__balance {
      font-size: 0.7rem; color: #888884; margin-top: 0.15rem;
      font-variant-numeric: tabular-nums;
    }
    .empty { font-size: 0.85rem; color: #888884; font-style: italic; text-align: center; padding: 2rem 0; }
  </style>

  <div class="balance-bar">
    <div>
      <h2>Cash Flow Intelligence</h2>
      <p>Trough analysis for overdraft prevention</p>
    </div>
    <div class="balance-input-wrap">
      <span>$</span>
      <input type="number" id="balance" step="0.01">
    </div>
  </div>

  <div class="wrap">
    <!-- LEFT: rules -->
    <div>
      <div class="card">
        <h3 class="card-title">Scan Bank Statement</h3>
        <p class="upload-desc">Upload a CSV. We analyse transaction dates to auto-detect weekly, biweekly, and monthly patterns.</p>
        <label class="upload-zone" for="csv-input">
          <span>Select CSV file</span>
          <input id="csv-input" type="file" accept=".csv">
        </label>
      </div>

      <div class="card">
        <h3 class="card-title">Cash Flow Rules</h3>
        <div class="rules-list" id="rules-list"></div>
        <div class="add-form">
          <input type="text" id="new-name" placeholder="Name" class="full">
          <input type="number" id="new-amount" placeholder="Amount" step="0.01">
          <select id="new-type"><option value="expense">Expense (-)</option><option value="income">Income (+)</option></select>
          <select id="new-freq" class="full">
            <option value="weekly">Weekly</option>
            <option value="biweekly">Bi-weekly</option>
            <option value="monthly" selected>Monthly</option>
          </select>
          <div class="date-row full">
            <label>Next:</label>
            <input type="date" id="new-date" style="flex:1;">
          </div>
          <button class="add-btn full" id="add-btn">+ Add Rule</button>
        </div>
      </div>
    </div>

    <!-- RIGHT: forecast -->
    <div>
      <div class="card">
        <div class="horizon-row">
          <h3 class="card-title" style="margin:0;">Forecast Horizon</h3>
          <input type="date" id="horizon">
        </div>
        <div class="insights" id="insights"></div>
        <div class="timeline-head">Future Transaction Timeline</div>
        <div class="timeline" id="timeline"></div>
      </div>
    </div>
  </div>
  `;

  /* ── Date helpers ──────────────────────────────────────────────────────── */
  function ymd(d) { return d.toISOString().split('T')[0]; }
  function inDays(n) { const d = new Date(); d.setDate(d.getDate() + n); return ymd(d); }
  function advance(date, freq) {
    if (freq === 'monthly') date.setMonth(date.getMonth() + 1);
    else if (freq === 'biweekly') date.setDate(date.getDate() + 14);
    else if (freq === 'weekly') date.setDate(date.getDate() + 7);
  }
  function fmtMoney(n) {
    const abs = Math.abs(n);
    return abs.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }
  function fmtDate(d, opts) {
    return d.toLocaleDateString(undefined, opts || { month: 'short', day: 'numeric' });
  }

  class AECashflow extends HTMLElement {
    constructor() {
      super();
      this.attachShadow({ mode: 'open' });
      this.shadowRoot.appendChild(tpl.content.cloneNode(true));

      this.state = {
        balance: 1500,
        targetDate: inDays(45),
        items: [
          { id: 1, name: 'Rent',          amount: -1200, frequency: 'monthly',  nextDate: inDays(1),  type: 'expense' },
          { id: 2, name: 'Paycheck',      amount:  1800, frequency: 'biweekly', nextDate: inDays(5),  type: 'income' },
          { id: 3, name: 'Auto Insurance',amount: -110,  frequency: 'monthly',  nextDate: inDays(14), type: 'expense' }
        ]
      };

      this.$ = (sel) => this.shadowRoot.querySelector(sel);
    }

    connectedCallback() {
      this.$('#balance').value  = this.state.balance;
      this.$('#horizon').value  = this.state.targetDate;
      this.$('#new-date').value = inDays(1);

      this.$('#balance').addEventListener('input', (e) => {
        this.state.balance = parseFloat(e.target.value) || 0; this.recompute();
      });
      this.$('#horizon').addEventListener('change', (e) => {
        this.state.targetDate = e.target.value; this.recompute();
      });
      this.$('#csv-input').addEventListener('change', (e) => this.handleCsv(e));
      this.$('#add-btn').addEventListener('click', () => this.addRule());

      this.renderRules();
      this.recompute();
    }

    /* ── CSV intake ────────────────────────────────────────────────────── */
    handleCsv(e) {
      const file = e.target.files[0]; if (!file) return;
      const reader = new FileReader();
      reader.onload = (ev) => this.parseCsv(ev.target.result);
      reader.readAsText(file);
    }

    parseCsv(text) {
      const lines = text.split('\n').filter(l => l.trim());
      const txns = [];
      const splitRow = (s) => {
        const re = /(?:^|,)(?=[^"]|")(?:"([^"]*)"|([^",]*))/g;
        const row = []; let m;
        while ((m = re.exec(s)) !== null) row.push(m[1] !== undefined ? m[1] : (m[2] || ''));
        return row;
      };
      const cleanDesc = (d) => d.toUpperCase()
        .replace(/[0-9]/g, '')
        .replace(/ACH|POS|DBT|PAYMENT|WEB|TRNSFR/g, '')
        .replace(/[^A-Z]/g, ' ')
        .trim().split(/\s+/).slice(0, 2).join(' ');

      for (let i = 1; i < lines.length; i++) {
        const cols = splitRow(lines[i]);
        let date = null, amount = null, desc = '';
        cols.forEach(c => {
          const v = c.trim();
          if (!date && !isNaN(Date.parse(v)) && (v.includes('/') || v.includes('-'))) date = new Date(v);
          else if (!amount && /^[-$]?\d+(\.\d{2})?$/.test(v.replace(/,/g, ''))) amount = parseFloat(v.replace(/[$,]/g, ''));
          else if (v.length > desc.length && !/^[-$]?\d/.test(v)) desc = v;
        });
        if (date && amount !== null && desc) txns.push({ date, amount, desc: cleanDesc(desc) });
      }
      if (!txns.length) { alert('Could not parse this CSV format.'); return; }

      const groups = {};
      txns.forEach(t => { if (t.desc.length < 3) return; (groups[t.desc] = groups[t.desc] || []).push(t); });

      const detected = []; let idc = Date.now();
      Object.keys(groups).forEach(k => {
        const g = groups[k].sort((a, b) => a.date - b.date);
        if (g.length < 2) return;
        let total = 0;
        for (let i = 1; i < g.length; i++) total += (g[i].date - g[i-1].date) / 86400000;
        const avg = total / (g.length - 1);
        let freq = null;
        if (avg >= 25 && avg <= 35) freq = 'monthly';
        else if (avg >= 12 && avg <= 16) freq = 'biweekly';
        else if (avg >= 6 && avg <= 8) freq = 'weekly';
        if (!freq) return;
        const avgAmt = g.reduce((s, t) => s + t.amount, 0) / g.length;
        let next = new Date(g[g.length - 1].date);
        advance(next, freq);
        const today = new Date(); today.setHours(0,0,0,0);
        if (next < today) next = today;
        detected.push({
          id: idc++, name: (k || 'Unknown') + ' (Auto)',
          amount: parseFloat(avgAmt.toFixed(2)), frequency: freq,
          nextDate: ymd(next), type: avgAmt >= 0 ? 'income' : 'expense'
        });
      });

      if (!detected.length) { alert('No clear recurring patterns found in this CSV.'); return; }
      this.state.items = this.state.items.concat(detected);
      this.renderRules(); this.recompute();
      alert(`Auto-detected ${detected.length} recurring items.`);
    }

    /* ── Add / remove rules ────────────────────────────────────────────── */
    addRule() {
      const name = this.$('#new-name').value.trim();
      const amt  = parseFloat(this.$('#new-amount').value);
      const date = this.$('#new-date').value;
      if (!name || isNaN(amt) || !date) return;
      const type = this.$('#new-type').value;
      const signed = type === 'expense' ? -Math.abs(amt) : Math.abs(amt);
      this.state.items.push({
        id: Date.now(), name, amount: signed, type,
        frequency: this.$('#new-freq').value, nextDate: date
      });
      this.$('#new-name').value = ''; this.$('#new-amount').value = '';
      this.renderRules(); this.recompute();
    }
    removeRule(id) {
      this.state.items = this.state.items.filter(i => i.id !== id);
      this.renderRules(); this.recompute();
    }

    /* ── Render rules ──────────────────────────────────────────────────── */
    renderRules() {
      const list = this.$('#rules-list');
      list.innerHTML = '';
      this.state.items.forEach(it => {
        const row = document.createElement('div');
        row.className = 'rule-row';
        const neg = it.amount < 0;
        row.innerHTML = `
          <div>
            <div class="rule-row__name">${this.esc(it.name)}</div>
            <div class="rule-row__meta">${it.frequency} · Next: ${fmtDate(new Date(it.nextDate + 'T00:00:00'))}</div>
          </div>
          <div class="rule-row__right">
            <span class="rule-row__amount ${neg ? 'rule-row__amount--neg' : 'rule-row__amount--pos'}">
              ${neg ? '-' : '+'}$${fmtMoney(it.amount)}
            </span>
            <button class="rule-row__remove" data-id="${it.id}" aria-label="Remove">✕</button>
          </div>`;
        row.querySelector('.rule-row__remove').addEventListener('click', () => this.removeRule(it.id));
        list.appendChild(row);
      });
    }

    /* ── Trough analysis ───────────────────────────────────────────────── */
    recompute() {
      const today = new Date(); today.setHours(0,0,0,0);
      const end = new Date(this.state.targetDate); end.setHours(23,59,59,999);
      if (end < today) { this.renderInsights(null); this.renderTimeline([]); return; }

      const tl = [];
      this.state.items.forEach(it => {
        const cur = new Date(it.nextDate + 'T00:00:00'); cur.setHours(0,0,0,0);
        while (cur < today) advance(cur, it.frequency);
        while (cur <= end) {
          tl.push({ date: new Date(cur), name: it.name, amount: it.amount });
          advance(cur, it.frequency);
        }
      });
      tl.sort((a, b) => a.date - b.date);

      let bal = parseFloat(this.state.balance) || 0;
      let lowestBal = bal, lowestDate = today;
      const enriched = tl.map(ev => {
        bal += ev.amount;
        if (bal < lowestBal) { lowestBal = bal; lowestDate = new Date(ev.date); }
        return Object.assign({}, ev, { runningBalance: bal });
      });

      const projection = {
        finalBalance: bal,
        lowestBalance: lowestBal,
        lowestDate,
        shortfall: lowestBal < 0 ? Math.abs(lowestBal) : 0,
        safeToSpend: lowestBal > 0 ? lowestBal : 0
      };
      this.renderInsights(projection);
      this.renderTimeline(enriched, projection);
    }

    /* ── Render insights ───────────────────────────────────────────────── */
    renderInsights(p) {
      const ins = this.$('#insights');
      if (!p) { ins.innerHTML = ''; return; }
      const target = new Date(this.state.targetDate);
      const safeCard = p.shortfall > 0
        ? { cls: 'insight--alert', label: 'Account Shortfall', value: '$' + fmtMoney(p.shortfall),
            note: `Deposit this to avoid overdrafting on ${fmtDate(p.lowestDate, { month:'short', day:'numeric' })}.` }
        : { cls: 'insight--safe',  label: 'Safe to Spend Today', value: '$' + fmtMoney(p.safeToSpend),
            note: `You can spend this much now and still cover all bills through ${fmtDate(target, { month:'short', day:'numeric' })}.` };
      ins.innerHTML = `
        <div class="insight ${safeCard.cls}">
          <span class="insight__label">${safeCard.label}</span>
          <span class="insight__value">${safeCard.value}</span>
          <span class="insight__note">${safeCard.note}</span>
        </div>
        <div class="insight insight--neutral">
          <span class="insight__label">Lowest Projected Balance</span>
          <span class="insight__value">$${fmtMoney(p.lowestBalance)}</span>
          <span class="insight__note">Hits on ${fmtDate(p.lowestDate, { weekday:'long', month:'short', day:'numeric' })}</span>
        </div>`;
    }

    /* ── Render timeline ───────────────────────────────────────────────── */
    renderTimeline(events, p) {
      const tl = this.$('#timeline');
      if (!events.length) {
        tl.innerHTML = '<div class="empty">No rules scheduled before the forecast end date.</div>';
        return;
      }
      const frag = document.createDocumentFragment();
      events.forEach(ev => {
        const row = document.createElement('div');
        const isLowest = p && ev.runningBalance === p.lowestBalance && ev.date.getTime() === p.lowestDate.getTime();
        row.className = 'event' + (isLowest ? ' event--lowest' : '');
        const neg = ev.amount < 0;
        row.innerHTML = `
          <div class="event__left">
            <div class="event__date">
              <div class="m">${fmtDate(ev.date, { month:'short' })}</div>
              <div class="d">${ev.date.getDate()}</div>
            </div>
            <div>
              <span class="event__name">${this.esc(ev.name)}</span>
              ${isLowest ? '<span class="lowest-tag">Lowest</span>' : ''}
            </div>
          </div>
          <div class="event__right">
            <div class="event__amount ${neg ? 'event__amount--neg' : 'event__amount--pos'}">
              ${neg ? '-' : '+'}$${fmtMoney(ev.amount)}
            </div>
            <div class="event__balance">Bal: $${fmtMoney(ev.runningBalance)}</div>
          </div>`;
        frag.appendChild(row);
      });
      tl.innerHTML = ''; tl.appendChild(frag);
    }

    esc(s) { return String(s).replace(/[&<>"]/g, c => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;' }[c])); }
  }

  customElements.define('ae-cashflow', AECashflow);
})();
