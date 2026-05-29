#!/usr/bin/env bash
# =============================================================================
# deploy-marketplace.sh
# Sets up the Facebook Marketplace scraper (FastAPI + Playwright) on the VPS.
#
# Usage:
#   bash scripts/deploy-marketplace.sh
#
# Run this on the KVM4 VPS as root or a sudoer.
# After it completes:
#   1. Copy the printed SERVICE_TOKEN into AE Settings → Marketplace Service Token
#   2. Set the Marketplace Service URL to http://127.0.0.1:8001
#   3. Set your Facebook Email and Password in AE Settings
# =============================================================================

set -euo pipefail

INSTALL_DIR="/opt/ae-marketplace"
SERVICE_NAME="ae-marketplace"
PORT=8001
PYTHON="/usr/bin/python3"
PIP="$PYTHON -m pip"
VENV="$INSTALL_DIR/venv"

echo ""
echo "══════════════════════════════════════════════════"
echo "  AE Marketplace Scraper — VPS Deploy"
echo "══════════════════════════════════════════════════"
echo ""

# ── 1. System dependencies ─────────────────────────────────────────────────
echo "[1/7] Installing system dependencies…"
if command -v apt-get &>/dev/null; then
    apt-get update -qq
    apt-get install -y -qq python3 python3-pip python3-venv curl git \
        libnss3 libatk-bridge2.0-0 libdrm2 libgbm1 libxcomposite1 \
        libxdamage1 libxfixes3 libxrandr2 libgtk-3-0 libpango-1.0-0 \
        libcairo2 libasound2 2>/dev/null || true
elif command -v dnf &>/dev/null; then
    # AlmaLinux / RHEL / CentOS Stream
    dnf install -y -q python3 python3-pip curl git \
        nss atk at-spi2-atk libdrm mesa-libgbm libXcomposite \
        libXdamage libXfixes libXrandr gtk3 pango cairo \
        alsa-lib cups-libs libXScrnSaver libXtst 2>/dev/null || true
elif command -v yum &>/dev/null; then
    yum install -y -q python3 python3-pip curl git 2>/dev/null || true
else
    echo "  ⚠ No recognised package manager — skipping system deps."
    echo "    Playwright may fail without Chromium system libraries."
fi

# ── 2. Clone / update the scraper repo ────────────────────────────────────
echo "[2/7] Setting up scraper code…"
if [ -d "$INSTALL_DIR/.git" ]; then
    echo "  → Pulling latest from existing clone"
    git -C "$INSTALL_DIR" pull --quiet
else
    echo "  → Cloning passivebot/facebook-marketplace-scraper"
    git clone --quiet https://github.com/passivebot/facebook-marketplace-scraper.git "$INSTALL_DIR"
fi

# ── 3. Python virtual environment ──────────────────────────────────────────
echo "[3/7] Creating Python venv…"
"$PYTHON" -m venv "$VENV"
source "$VENV/bin/activate"

# ── 4. Install Python dependencies ─────────────────────────────────────────
echo "[4/7] Installing Python packages…"
pip install --quiet --upgrade pip
pip install --quiet fastapi uvicorn playwright beautifulsoup4 requests lxml

# Install Playwright Chromium browser
echo "[4b] Installing Playwright Chromium…"
python -m playwright install chromium
python -m playwright install-deps chromium 2>/dev/null || true

# ── 5. Generate a random service token ────────────────────────────────────
echo "[5/7] Generating service token…"
SERVICE_TOKEN=$(python -c "import secrets; print(secrets.token_hex(32))")

# ── 6. Write the wrapper app that injects credentials from env vars ────────
echo "[6/7] Writing credential-injection wrapper…"
cat > "$INSTALL_DIR/ae_wrapper.py" << 'PYEOF'
"""
ae_wrapper.py — wraps the existing FastAPI scraper with:
  - Token auth via X-Marketplace-Token header
  - FB credentials injected from X-FB-Email / X-FB-Password headers
    (so WordPress passes them per-request without storing in the scraper)
  - Served on 127.0.0.1 only (Nginx or WP proxies in)
"""
import os
import importlib
import sys

from fastapi import FastAPI, Request, HTTPException, Depends
from fastapi.responses import JSONResponse

# Import the original scraper module
sys.path.insert(0, os.path.dirname(__file__))
import app as scraper_module

wrapper = FastAPI(title="AE Marketplace")

SERVICE_TOKEN = os.environ.get("MARKETPLACE_TOKEN", "")


async def verify_token(request: Request):
    token = request.headers.get("X-Marketplace-Token", "")
    if SERVICE_TOKEN and token != SERVICE_TOKEN:
        raise HTTPException(status_code=401, detail="Invalid token")


@wrapper.get("/crawl_facebook_marketplace", dependencies=[Depends(verify_token)])
async def crawl(
    request: Request,
    city: str = "new york",
    query: str = "",
    max_price: int = None,
):
    # Inject FB credentials from request headers into the scraper module at runtime
    fb_email    = request.headers.get("X-FB-Email", os.environ.get("FB_EMAIL", ""))
    fb_password = request.headers.get("X-FB-Password", os.environ.get("FB_PASSWORD", ""))

    if fb_email:
        scraper_module.EMAIL    = fb_email
    if fb_password:
        scraper_module.PASSWORD = fb_password

    # Call the original scraper function
    import asyncio
    from app import get_listings  # adjust if the function name differs

    try:
        listings = await get_listings(city=city, query=query, max_price=max_price)
        return listings
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))


@wrapper.get("/health")
async def health():
    return {"status": "ok"}
PYEOF

# ── 7. Write systemd service ───────────────────────────────────────────────
echo "[7/7] Writing systemd service…"
cat > "/etc/systemd/system/${SERVICE_NAME}.service" << EOF
[Unit]
Description=AE Facebook Marketplace Scraper
After=network.target

[Service]
Type=simple
User=root
WorkingDirectory=${INSTALL_DIR}
Environment=MARKETPLACE_TOKEN=${SERVICE_TOKEN}
ExecStart=${VENV}/bin/uvicorn ae_wrapper:wrapper --host 127.0.0.1 --port ${PORT} --workers 1
Restart=on-failure
RestartSec=5
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable --quiet "${SERVICE_NAME}"
systemctl restart "${SERVICE_NAME}"

# Brief wait then health check
sleep 3
if curl -sf "http://127.0.0.1:${PORT}/health" > /dev/null; then
    echo ""
    echo "══════════════════════════════════════════════════"
    echo "  ✓ Marketplace scraper is running on port ${PORT}"
    echo ""
    echo "  SERVICE_TOKEN: ${SERVICE_TOKEN}"
    echo ""
    echo "  Copy this token into:"
    echo "  WP Admin → AE Settings → Marketplace Service Token"
    echo "  Marketplace Service URL: http://127.0.0.1:${PORT}"
    echo "══════════════════════════════════════════════════"
else
    echo ""
    echo "  ⚠ Service started but health check failed."
    echo "  Check logs: journalctl -u ${SERVICE_NAME} -n 40"
    echo ""
    echo "  SERVICE_TOKEN (save this): ${SERVICE_TOKEN}"
fi

deactivate 2>/dev/null || true
