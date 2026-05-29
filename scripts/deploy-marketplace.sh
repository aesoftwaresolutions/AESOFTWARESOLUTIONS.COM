#!/usr/bin/env bash
# =============================================================================
# deploy-marketplace.sh
# Sets up the Facebook Marketplace scraper on Hostinger shared hosting.
# Runs as the SSH user (u257353593) — no root needed.
#
# Installs to: ~/ae-marketplace/
# Runs on:     port 8001 (kept alive via cron)
# =============================================================================

set -euo pipefail

HOME_DIR="${HOME:-/home/u257353593}"
INSTALL_DIR="$HOME_DIR/ae-marketplace"
PORT=8001
PID_FILE="$INSTALL_DIR/server.pid"
LOG_FILE="$INSTALL_DIR/server.log"

echo ""
echo "══════════════════════════════════════════════════"
echo "  AE Marketplace Scraper — Hostinger Deploy"
echo "══════════════════════════════════════════════════"
echo ""

# ── 1. Find python3 ────────────────────────────────────────────────────────
echo "[1/6] Locating Python 3…"
PYTHON=""
for candidate in python3 python3.11 python3.10 python3.9 python; do
    if command -v "$candidate" &>/dev/null; then
        ver=$("$candidate" -c "import sys; print(sys.version_info.major)" 2>/dev/null || echo "0")
        if [ "$ver" = "3" ]; then
            PYTHON="$candidate"
            echo "  → Found: $PYTHON ($("$PYTHON" --version 2>&1))"
            break
        fi
    fi
done

if [ -z "$PYTHON" ]; then
    echo "  ✗ Python 3 not found. Contact Hostinger support to enable Python 3."
    exit 1
fi

# ── 2. Clone / update the scraper repo ─────────────────────────────────────
echo "[2/6] Setting up scraper code in $INSTALL_DIR…"
if [ -d "$INSTALL_DIR/.git" ]; then
    echo "  → Updating existing clone"
    git -C "$INSTALL_DIR" pull --quiet 2>&1 || echo "  ⚠ git pull failed, continuing"
elif [ -d "$INSTALL_DIR" ]; then
    echo "  → Directory exists but not a git repo — removing and re-cloning"
    rm -rf "$INSTALL_DIR"
    git clone --quiet https://github.com/passivebot/facebook-marketplace-scraper.git "$INSTALL_DIR"
else
    echo "  → Cloning passivebot/facebook-marketplace-scraper"
    git clone --quiet https://github.com/passivebot/facebook-marketplace-scraper.git "$INSTALL_DIR"
fi

# ── 3. Install Python packages (user-local, no venv needed) ────────────────
echo "[3/6] Installing Python packages…"

# Ensure ~/.local/bin is on PATH so installed scripts are found
export PATH="$HOME/.local/bin:$PATH"

# Upgrade pip first (quietly, ignore errors if pip isn't upgradable)
"$PYTHON" -m pip install --quiet --upgrade pip --user 2>/dev/null || true

echo "  → Installing fastapi, uvicorn, playwright, beautifulsoup4…"
"$PYTHON" -m pip install --quiet --user fastapi "uvicorn[standard]" playwright beautifulsoup4 requests lxml

echo "  → Installing Playwright Chromium (may take a minute)…"
"$PYTHON" -m playwright install chromium 2>&1 | tail -5 || {
    echo "  ⚠ Playwright Chromium install failed — may need manual setup on the server."
    echo "    Continuing anyway; scraping will fail until Chromium is available."
}

# ── 4. Generate a service token ─────────────────────────────────────────────
echo "[4/6] Generating service token…"
SERVICE_TOKEN=$(python -c "import secrets; print(secrets.token_hex(32))")

# ── 5. Write the wrapper ─────────────────────────────────────────────────────
echo "[5/6] Writing credential wrapper…"
cat > "$INSTALL_DIR/ae_wrapper.py" << 'PYEOF'
"""
ae_wrapper.py — wraps the Facebook Marketplace scraper with:
  - Token auth via X-Marketplace-Token header
  - FB credentials injected per-request via X-FB-Email / X-FB-Password
"""
import os, sys, importlib
sys.path.insert(0, os.path.dirname(__file__))

from fastapi import FastAPI, Request, HTTPException, Depends
from fastapi.responses import JSONResponse

SERVICE_TOKEN = os.environ.get("MARKETPLACE_TOKEN", "")

wrapper = FastAPI(title="AE Marketplace")

async def verify_token(request: Request):
    if SERVICE_TOKEN and request.headers.get("X-Marketplace-Token", "") != SERVICE_TOKEN:
        raise HTTPException(status_code=401, detail="Invalid token")

@wrapper.get("/crawl_facebook_marketplace", dependencies=[Depends(verify_token)])
async def crawl(request: Request, city: str = "new york", query: str = "", max_price: int = None):
    import app as scraper_module
    fb_email    = request.headers.get("X-FB-Email",    os.environ.get("FB_EMAIL", ""))
    fb_password = request.headers.get("X-FB-Password", os.environ.get("FB_PASSWORD", ""))
    if fb_email:    scraper_module.EMAIL    = fb_email
    if fb_password: scraper_module.PASSWORD = fb_password
    try:
        from app import get_listings
        listings = await get_listings(city=city, query=query, max_price=max_price)
        return listings
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@wrapper.get("/health")
async def health():
    return {"status": "ok"}
PYEOF

# ── 6. Start the server (kill old one first) ────────────────────────────────
echo "[6/6] Starting server on port $PORT…"

# Kill existing process if running
if [ -f "$PID_FILE" ]; then
    OLD_PID=$(cat "$PID_FILE" 2>/dev/null || echo "")
    if [ -n "$OLD_PID" ] && kill -0 "$OLD_PID" 2>/dev/null; then
        kill "$OLD_PID" && echo "  → Stopped old server (PID $OLD_PID)"
        sleep 2
    fi
fi

# Write startup script (cron uses this to restart on reboot)
cat > "$INSTALL_DIR/start.sh" << STARTEOF
#!/usr/bin/env bash
export PATH="\$HOME/.local/bin:\$PATH"
export MARKETPLACE_TOKEN="$SERVICE_TOKEN"
cd "$INSTALL_DIR"
nohup uvicorn ae_wrapper:wrapper --host 127.0.0.1 --port $PORT --workers 1 \\
    >> "$LOG_FILE" 2>&1 &
echo \$! > "$PID_FILE"
STARTEOF
chmod +x "$INSTALL_DIR/start.sh"

# Start it
bash "$INSTALL_DIR/start.sh"
sleep 4

# Health check
if curl -sf "http://127.0.0.1:$PORT/health" > /dev/null 2>&1; then
    echo ""
    echo "══════════════════════════════════════════════════"
    echo "  ✓ Server is running on port $PORT"
    echo ""
    echo "  SERVICE_TOKEN: $SERVICE_TOKEN"
    echo ""
    echo "  → WP Admin → AE Settings:"
    echo "     Marketplace Service URL:   http://127.0.0.1:$PORT"
    echo "     Marketplace Service Token: $SERVICE_TOKEN"
    echo "══════════════════════════════════════════════════"
else
    echo "  ⚠ Server started but health check failed."
    echo "  Last log lines:"
    tail -10 "$LOG_FILE" 2>/dev/null || echo "  (no log yet)"
    echo ""
    echo "  SERVICE_TOKEN (save this): $SERVICE_TOKEN"
fi

# ── Auto-configure WordPress options via WP-CLI ─────────────────────────────
WP_PATH="/home/u257353593/domains/aesoftwaresolutions.com/public_html"
WP_CMD="wp --allow-root --path=$WP_PATH"
if command -v wp &>/dev/null && [ -d "$WP_PATH" ]; then
    echo "  → Saving URL and token to WordPress via WP-CLI…"
    $WP_CMD option update aesoftwares_marketplace_url "http://127.0.0.1:$PORT" 2>/dev/null && echo "     ✓ Marketplace URL set"  || echo "     ⚠ Failed to set URL"
    $WP_CMD option update aesoftwares_marketplace_token "$SERVICE_TOKEN"          2>/dev/null && echo "     ✓ Marketplace token set" || echo "     ⚠ Failed to set token"
    echo "  → WordPress auto-configured. Only Facebook credentials remain."
else
    echo "  ⚠ WP-CLI not found — set these in WP Admin → AE Settings manually:"
    echo "     Marketplace Service URL:   http://127.0.0.1:$PORT"
    echo "     Marketplace Service Token: (see above)"
fi

# ── Cron for auto-restart on reboot ─────────────────────────────────────────
CRON_CMD="@reboot bash $INSTALL_DIR/start.sh"
( crontab -l 2>/dev/null | grep -v "ae-marketplace\|ae_wrapper"; echo "$CRON_CMD # ae-marketplace" ) | crontab - 2>/dev/null \
  && echo "  → Cron @reboot entry added" \
  || echo "  ⚠ crontab unavailable — use Hostinger cron panel to run: bash ~/ae-marketplace/start.sh"
