# AE Softwares Theme

Custom WordPress theme for [AE Software Solutions LLC](https://aesoftwaresolutions.com). Handles all frontend templating, script/style enqueuing, admin settings, and AI-powered tooling.

---

## Theme Structure

| File | Purpose |
|---|---|
| `functions.php` | Enqueues, AE Settings, REST endpoints, Anthropic helper |
| `header.php` / `footer.php` | Site chrome, loader, transition panel, mobile menu markup |
| `front-page.php` | Home page (v5 editorial layout) |
| `page-*.php` | Inner pages and free-tool pages (template per page slug) |
| `assets/js/ae-motion.js` | All site motion: loader, text reveals, page transitions, menu |
| `assets/js/ae-widget.js` | Floating "Ava" chat widget (talks to aeCRM) |
| `assets/js/ae-cta.js` | `<ae-cta>` closing call-to-action component |
| `assets/refresh/ae-chrome.css` | v4 base tokens, header and grid styles |
| `assets/refresh/ae-fixes.css` | Bug-fix layer: contrast, mobile menu, small screens |
| `assets/refresh/ae-v5.css` | v5 design layer, loaded last: palette, type, motion, light inner pages |
| `assets/img/work/` | Screenshots of client sites used on Home, About and Work |
| `style.css` | Theme stylesheet + metadata |

---

## AI tools

The free tools and the assessment call the Anthropic API from `functions.php` through one helper, `aesoftwares_anthropic_chat()` (model `claude-haiku-4-5-20251001`). Each public endpoint is rate-limited per IP.

| Tool | Page template | Endpoint |
|---|---|---|
| Rewriter | `page-rewriter.php` | `POST /wp-json/aesoftwares/v1/rewrite` (+ `/validate`) |
| Email drafter | `page-email-drafter.php` | `POST /wp-json/aesoftwares/v1/email` |
| Summariser | `page-summarizer.php` | `POST /wp-json/aesoftwares/v1/summarize` |
| Assessment | `page-assessment.php` | `POST /wp-json/aesoftwares/v1/assessment` |
| Transcriber | `page-transcriber.php` | `POST /wp-json/aesoftwares/v1/transcribe` (Speaches/Whisper, own URL + token) |

Rewriter request: `{ "text": "...", "mode": "standard" | "academic" | "casual" }`, response `{ "rewritten": "..." }`, 2,000-word limit.

### AE Settings

| Field | Option key | Used by |
|---|---|---|
| Anthropic API Key | `aesoftwares_anthropic_api_key` | Free tools and assessment |
| n8n Webhook URL | `aesoftwares_n8n_webhook_url` | `/chat` proxy (nonce-protected; not used by the live widget) |
| Speaches URL / token | `aesoftwares_speaches_url`, `aesoftwares_speaches_token` | Transcriber |

---

## Site chat widget (Ava)

`assets/js/ae-widget.js` posts to `https://admin.aesoftwaresolutions.com/api/widget/chat`. That endpoint lives in the **aeCRM** repo (`routes/widget_routes.py`), runs on the VPS behind nginx-proxy-manager, and calls OpenRouter's free models with `OPENROUTER_API_KEY` from aeCRM's `.env`. Captured leads go into aeCRM's `crm_contacts` table. The contact form posts to aeCRM's `/api/business/webhook/inbound-lead`.

---

## Animations

All motion lives in `assets/js/ae-motion.js` and `assets/refresh/ae-v5.css`: first-visit loader that tracks real loading, line-by-line text reveals (`data-split`), drawn hairlines (`.ae-rule`), image reveals (`.ae-media`), page transitions, and the mobile menu. Everything switches off for `prefers-reduced-motion`.

---

## Setup / Deployment

Pushing to `master` rebuilds the theme-only `deploy/theme` branch (`.github/workflows/build-theme-branch.yml`), which hPanel Git deploys to `wp-content/themes/aesoftwares-theme/`.

After deploying, open **WP Admin > AE Settings** and set the Anthropic API key (and Speaches URL for the transcriber).

---

## Changelog

### 2026-10-07 — v5 editorial pass

- Removed the retired Ollama/Gemma router: AE Settings fields, stored options and all code paths. Free tools and the assessment use the Anthropic API key only.
- See the commit message for the full design and motion changes.

### 2026-10-06 — v4.3 review fixes

- Aria REST routes now register on `rest_api_init`; registering them at load time is a fatal error on a stock WordPress install
- Mobile menu works again (hamburger was hidden by an inline style and the overlay markup was missing)
- Fixed unreadable text sitewide where the old dark theme and the v4 light tokens overlapped (footer, page heroes, primary buttons, cards, section titles) via `assets/refresh/ae-fixes.css`
- Home page no longer scrolls sideways on phones; contact page stacks on phones
- Removed invented testimonials from About and Services, and the unused `ae-testimonials`, `ae-greetings`, `ae-products`, `ae-assistant` and `cinematic.js` scripts
- Legacy `template-contact/about/services.php` now render the current pages (the old contact template showed a (555) number)
- `/chat` proxy requires a nonce, is rate-limited and forwards only known fields; public AI tool endpoints are rate-limited per IP
- Page loader shows once per visit, is skipped for reduced motion, and no longer hides the site when JavaScript is off

### 2026-05-03 — Ollama VPS proxy (retired 2026-10)

- Added a token-protected Ollama proxy on the VPS as the primary model for the free tools. Retired in 2026-10; the tools now call the Anthropic API directly.

### Initial
- Theme scaffolding, enqueue system, n8n assistant proxy, cinematic front-page stack
- Added AI Content Rewriter tool (`page-rewriter.php`, `ae-rewriter.js`) with Anthropic Claude backend

