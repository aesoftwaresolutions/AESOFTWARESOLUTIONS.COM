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
              ├─ PRIMARY:  Ollama (qwen3:8b) via VPS Nginx proxy
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
- Proxy config file: `/root/data/npm/nginx/custom/http.conf`
- `OLLAMA_HOST=0.0.0.0` set in `open-webui` service so Ollama binds to all interfaces inside the container
- Port `11435:11435` added to `nginx-proxy-manager` service in `/root/docker-compose.yml`

**Nginx proxy block (inside `http.conf`):**
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

### WordPress Settings (AE Settings)

Navigate to **WP Admin > AE Settings** to configure:

| Field | Option Key | Description |
|---|---|---|
| n8n Webhook URL | `aesoftwares_n8n_webhook_url` | Used by the site AI assistant chat |
| Ollama Proxy URL | `aesoftwares_ollama_proxy_url` | Full URL to VPS proxy, e.g. `http://145.79.2.62:11435` |
| Ollama Secret Token | `aesoftwares_ollama_token` | The `x-ollama-token` header value |
| Ollama Model | `aesoftwares_ollama_model` | Model name, default: `qwen3:8b` |
| Anthropic API Key | `aesoftwares_anthropic_api_key` | Fallback key — used if Ollama proxy URL is not set |

### Rewriter REST Endpoint

**Route:** `POST /wp-json/aesoftwares/v1/rewrite`

**Request body:**
```json
{
  "text": "Your text here...",
  "mode": "standard" // standard | academic | casual
}
```

**Response:**
```json
{
  "rewritten": "Rewritten text..."
}
```

**Logic:**
1. If `aesoftwares_ollama_proxy_url` is set in WP options → calls Ollama via `POST {proxy_url}/api/chat` with model `qwen3:8b` (or configured model), passes secret token in `x-ollama-token` header
2. If Ollama proxy URL is empty → falls back to Anthropic `claude-haiku-4-5-20251001`
3. Word limit: 2,000 words max
4. Timeout: 300s (to allow for larger documents on the local model)

---

## Site AI Assistant

The floating chat assistant proxies messages through `POST /wp-json/aesoftwares/v1/chat` → n8n webhook URL (set in AE Settings).

---

## Cinematic Stack (Front Page Only)

Loads GSAP + ScrollTrigger + Lenis for the animated homepage. Looks for local vendor files first (`assets/js/vendor/gsap.min.js`), falls back to CDN if not present.

---

## Docker Compose Changes (VPS)

The following changes were made to `/root/docker-compose.yml` on the Hostinger VPS to enable the Ollama proxy:

1. **`nginx-proxy-manager` service** — added port mapping `11435:11435`
2. **`openwebui` service** — added environment variable `OLLAMA_HOST=0.0.0.0`

```yaml
# nginx-proxy-manager service — added port:
- 11435:11435

# openwebui service — added environment:
environment:
  - OLLAMA_HOST=0.0.0.0
```

Both containers were recreated with `docker compose up -d --no-deps <service>` after the changes.

---

## Setup / Deployment

1. Install theme in WordPress (`/wp-content/themes/aesoftwares-theme/`)
2. Activate in WP Admin > Appearance > Themes
3. Go to **AE Settings** and configure:
   - Ollama Proxy URL (`http://YOUR_VPS_IP:11435`)
   - Ollama Secret Token (from VPS nginx config)
   - Ollama Model (`qwen3:8b` recommended)
   - Anthropic API Key (optional fallback)
   - n8n Webhook URL (for AI assistant)
4. Create a WordPress page and assign template **Rewriter** for the rewriter tool
5. For the GSAP cinematic homepage, download GSAP files to `assets/js/vendor/` (see SETUP.md)

---

## Changelog

### 2026-05-03
- Added AI Content Rewriter tool (`page-rewriter.php`, `ae-rewriter.js`)
- Connected rewriter to Ollama `qwen3:8b` running on Hostinger VPS via Nginx Proxy Manager
- Added secret-token auth layer on VPS port 11435 (HTTP 403 on missing/invalid token)
- Added Anthropic Claude fallback in `aesoftwares_handle_rewrite()`
- Updated AE Settings admin page with Ollama proxy URL, token, and model fields
- Set `OLLAMA_HOST=0.0.0.0` in `open-webui` Docker container
- Added port `11435` to `nginx-proxy-manager` Docker service

### Initial
- Theme scaffolding, enqueue system, n8n assistant proxy, cinematic front-page stack
