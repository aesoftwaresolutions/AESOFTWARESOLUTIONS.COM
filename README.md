# AE Softwares Theme

Custom WordPress theme for [AE Software Solutions LLC](https://aesoftwaresolutions.com). Handles all frontend templating, script/style enqueuing, admin settings, and AI-powered tooling.

---

## Theme Structure

| File | Purpose |
|---|---|
| `functions.php` | Core: enqueue scripts, admin settings, REST API routes |
| `page-rewriter.php` | Template: AI Content Rewriter tool page |
| `assets/js/ae-rewriter.js` | Frontend JS for the rewriter UI |
| `assets/js/ae-assistant.js` | Frontend JS for the site AI assistant chat |
| `header.php` | Global site header |
| `footer.php` | Global site footer |
| `front-page.php` | Homepage template with cinematic GSAP stack |
| `style.css` | Theme stylesheet + metadata |

---

## AI Rewriter Tool

### Overview

A free AI content rewriter available at `/rewriter/`. Users paste text, choose a rewrite mode (Standard / Academic / Casual), and receive a human-sounding rewritten version engineered to pass AI detection tools (CopyLeaks, GPTZero, Originality.ai).

### Architecture

```
Browser
  └─> POST /wp-json/aesoftwares/v1/rewrite  (ae-rewriter.js)
        └─> functions.php: aesoftwares_handle_rewrite()
              ├─ PRIMARY:  Ollama (qwen3:8b) via VPS Nginx proxy  [PENDING commit]
              └─ FALLBACK: Anthropic Claude API
```

### Primary Model: Ollama on Hostinger VPS

Ollama runs inside the `open-webui` Docker container (image: `ghcr.io/open-webui/open-webui:ollama`) on the Hostinger VPS.

**Installed models:**
| Model | Size |
|---|---|
| `qwen3:8b` | 5.2 GB — **primary rewriter model** |
| `qwen3:8b-q4_K_M` | 5.2 GB |
| `qwen3:4b` | 2.6 GB |
| `qwen3:4b-q4_K_M` | 2.6 GB |

### VPS Nginx Proxy Setup

Ollama is exposed externally through the existing **Nginx Proxy Manager** Docker container with secret-token authentication.

**Configuration:**
- Proxy listens on `VPS_IP:11435`
- All requests require header: `x-ollama-token: <secret>`
- Requests without the token receive `HTTP 403`
- Requests with valid token receive `HTTP 200` — proxied to Ollama
- Proxy config file: `/root/data/npm/nginx/custom/http.conf`
- `OLLAMA_HOST=0.0.0.0` set in `open-webui` service so Ollama binds to all interfaces inside the container
- Port `11435:11435` added to `nginx-proxy-manager` service in `/root/docker-compose.yml`

**Verified working** — tested from VPS terminal:
```
No token:   HTTP 403  (blocked)
With token: HTTP 200  (proxied to Ollama successfully)
```

**Nginx proxy block (`/root/data/npm/nginx/custom/http.conf`):**
```nginx
server {
    listen 11435;
    server_name _;

    location /api/ {
        if ($http_x_ollama_token != "<SECRET_TOKEN>") {
            return 403;
        }
        proxy_pass http://open-webui:11434;
        proxy_http_version 1.1;
        proxy_set_header Host localhost;
        proxy_set_header Connection "";
        proxy_read_timeout 300s;
        proxy_send_timeout 300s;
    }
}
```

> **Note:** The secret token value is stored in WP Admin > AE Settings. Do not commit it to source control.

### WordPress Settings (AE Settings)

Navigate to **WP Admin > AE Settings** to configure:

| Field | Option Key | Description |
|---|---|---|
| n8n Webhook URL | `aesoftwares_n8n_webhook_url` | Used by the site AI assistant chat |
| Ollama Proxy URL | `aesoftwares_ollama_proxy_url` | Full URL to VPS proxy, e.g. `http://145.79.2.62:11435` |
| Ollama Secret Token | `aesoftwares_ollama_token` | The `x-ollama-token` header value |
| Ollama Model | `aesoftwares_ollama_model` | Model name, default: `qwen3:8b` |
| Anthropic API Key | `aesoftwares_anthropic_api_key` | Fallback key — used if Ollama proxy URL is not set |

> **Note:** The new Ollama fields and updated `aesoftwares_handle_rewrite()` logic are **pending commit** to `functions.php`.

### Rewriter REST Endpoint

**Route:** `POST /wp-json/aesoftwares/v1/rewrite`

**Request body:**
```json
{
  "text": "Your text here...",
  "mode": "standard"
}
```
`mode` options: `standard` | `academic` | `casual`

**Response:**
```json
{
  "rewritten": "Rewritten text..."
}
```

**Handler logic (`aesoftwares_handle_rewrite`):**
1. If `aesoftwares_ollama_proxy_url` is set → POST to `{proxy_url}/api/chat` with `model: qwen3:8b`, passing `x-ollama-token` header. Timeout: 300s
2. If Ollama proxy URL is empty → fall back to Anthropic `claude-haiku-4-5-20251001`. Timeout: 60s
3. Word limit: 2,000 words max
4. Modes: Standard (natural/conversational), Academic (formal with human rhythm), Casual (blog/friendly)

---

## Site AI Assistant

The floating chat assistant proxies messages through `POST /wp-json/aesoftwares/v1/chat` → n8n webhook URL (set in AE Settings).

---

## Cinematic Stack (Front Page Only)

Loads GSAP + ScrollTrigger + Lenis for the animated homepage. Looks for local vendor files first (`assets/js/vendor/gsap.min.js`), falls back to CDN if not present.

---

## Docker Compose Changes (VPS)

File: `/root/docker-compose.yml` on the Hostinger VPS.

### Changes made 2026-05-03:

**1. `nginx-proxy-manager` service — added port `11435:11435`:**
```yaml
ports:
  - 80:80
  - 443:443
  - 81:81
  - 11435:11435  # <-- added for Ollama proxy
```

**2. `openwebui` service — added `OLLAMA_HOST=0.0.0.0`:**
```yaml
environment:
  - OLLAMA_HOST=0.0.0.0  # <-- added so Ollama binds all interfaces in container
```

Both containers recreated with:
```bash
docker compose up -d --no-deps nginx-proxy-manager
docker compose up -d --no-deps openwebui
```

### Why `OLLAMA_HOST=0.0.0.0` is required

By default, Ollama inside the `open-webui:ollama` all-in-one image binds only to `127.0.0.1:11434` inside the container — unreachable from the NPM container on the shared Docker network. Setting `OLLAMA_HOST=0.0.0.0` makes it bind to all interfaces, allowing NPM to proxy to `http://open-webui:11434` by container name.

---

## Pending (not yet committed)

### `functions.php` — Ollama handler + new AE Settings fields

The following changes to `functions.php` are designed but **not yet committed**:

**1. New AE Settings admin fields:**
- `aesoftwares_ollama_proxy_url` — VPS proxy URL input
- `aesoftwares_ollama_token` — secret token password input
- `aesoftwares_ollama_model` — model name input (default: `qwen3:8b`)
- Existing `aesoftwares_anthropic_api_key` kept as fallback

**2. Updated `aesoftwares_handle_rewrite()` function:**
```php
// Pseudo-code for the updated handler:
$ollama_url   = get_option('aesoftwares_ollama_proxy_url');
$ollama_token = get_option('aesoftwares_ollama_token');
$ollama_model = get_option('aesoftwares_ollama_model') ?: 'qwen3:8b';

if ( ! empty( $ollama_url ) ) {
    // PRIMARY: call Ollama via proxy
    $response = wp_remote_post( $ollama_url . '/api/chat', [
        'headers' => [
            'Content-Type'    => 'application/json',
            'x-ollama-token'  => $ollama_token,
        ],
        'body'    => json_encode([
            'model'    => $ollama_model,
            'messages' => [ ['role' => 'user', 'content' => $prompt] ],
            'stream'   => false,
        ]),
        'timeout' => 300,
    ]);
    // parse $response['message']['content']
} else {
    // FALLBACK: Anthropic Claude
    // ... existing Anthropic logic ...
}
```

---

## Setup / Deployment

1. Install theme in WordPress (`/wp-content/themes/aesoftwares-theme/`)
2. Activate in WP Admin > Appearance > Themes
3. Go to **AE Settings** and configure:
   - Ollama Proxy URL (`http://YOUR_VPS_IP:11435`)
   - Ollama Secret Token (from `/root/data/npm/nginx/custom/http.conf` on VPS)
   - Ollama Model (`qwen3:8b` recommended)
   - Anthropic API Key (optional fallback)
   - n8n Webhook URL (for AI assistant)
4. Create a WordPress page and assign template **Rewriter** for the rewriter tool
5. For the GSAP cinematic homepage, download GSAP files to `assets/js/vendor/` (see SETUP.md)

---

## Changelog

### 2026-05-03 — Ollama VPS Integration Session

**VPS Infrastructure (completed):**
- Audited Hostinger VPS Docker stack — identified `open-webui:ollama` all-in-one container as Ollama host
- Confirmed 4 Ollama models installed: `qwen3:8b` (primary), `qwen3:8b-q4_K_M`, `qwen3:4b`, `qwen3:4b-q4_K_M`
- Confirmed existing `nginx-proxy-manager` Docker container on VPS (port 80/443/81)
- Removed erroneously installed system `nginx` package (`apt-get purge nginx nginx-common`)
- Added port `11435:11435` to `nginx-proxy-manager` service in `/root/docker-compose.yml`
- Added `OLLAMA_HOST=0.0.0.0` to `openwebui` service in `/root/docker-compose.yml`
- Created `/root/data/npm/nginx/custom/http.conf` — Nginx server block proxying port 11435 to `open-webui:11434` with `x-ollama-token` secret header auth
- Generated 64-char hex secret token via `openssl rand -hex 32`
- Recreated both Docker containers with `docker compose up -d --no-deps`
- Verified proxy: `HTTP 403` without token, `HTTP 200` with valid token

**GitHub / Documentation (completed):**
- Expanded README from 3 lines to full documentation (theme structure, rewriter architecture, VPS setup, AE Settings table, REST endpoint schema, docker-compose changes, setup guide, changelog)
- Committed README: `docs: expand README with Ollama VPS proxy setup and rewriter architecture`

**WordPress / functions.php (pending commit):**
- New AE Settings fields: `aesoftwares_ollama_proxy_url`, `aesoftwares_ollama_token`, `aesoftwares_ollama_model`
- Updated `aesoftwares_handle_rewrite()` to call Ollama via proxy as primary, Anthropic as fallback
- Existing `aesoftwares_anthropic_api_key` retained for fallback

### Initial
- Theme scaffolding, enqueue system, n8n assistant proxy, cinematic front-page stack
- Added AI Content Rewriter tool (`page-rewriter.php`, `ae-rewriter.js`) with Anthropic Claude backend
