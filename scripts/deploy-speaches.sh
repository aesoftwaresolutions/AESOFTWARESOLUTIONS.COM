#!/usr/bin/env bash
# Deploy Speaches (self-hosted Whisper transcription) on a CPU-only VPS.
# Run this on the KVM4 as root or a user with Docker access.
#
# Usage: bash scripts/deploy-speaches.sh [api-token]
#   api-token  Optional secret — set it in WordPress AE Settings > Transcription Token.
#              If omitted a random one is generated and printed.

set -euo pipefail

CONTAINER_NAME="speaches"
IMAGE="ghcr.io/speaches-ai/speaches:latest-cpu"
HOST_PORT="8000"
WHISPER_MODEL="${SPEACHES_MODEL:-Systran/faster-whisper-small}"
API_TOKEN="${1:-$(openssl rand -hex 24)}"
CACHE_VOLUME="speaches-cache"

echo ""
echo "═══════════════════════════════════════════"
echo "  Speaches Transcription Server — Deploy"
echo "═══════════════════════════════════════════"
echo ""

# ── 1. Install Docker if missing ─────────────────────────────────────────────
if ! command -v docker &>/dev/null; then
    echo "▸ Docker not found — installing via get.docker.com …"
    curl -fsSL https://get.docker.com | sh
    systemctl enable docker
    systemctl start docker
    echo "✓ Docker installed"
else
    echo "✓ Docker already installed ($(docker --version | cut -d' ' -f3 | tr -d ','))"
fi

# ── 2. Stop & remove any existing container ───────────────────────────────────
if docker ps -a --format '{{.Names}}' | grep -q "^${CONTAINER_NAME}$"; then
    echo "▸ Removing existing '${CONTAINER_NAME}' container …"
    docker rm -f "${CONTAINER_NAME}"
fi

# ── 3. Create a named volume for the Hugging Face model cache ─────────────────
docker volume create "${CACHE_VOLUME}" &>/dev/null || true
echo "✓ Cache volume '${CACHE_VOLUME}' ready"

# ── 4. Run Speaches ───────────────────────────────────────────────────────────
echo "▸ Starting Speaches (model: ${WHISPER_MODEL}) …"
echo "  First run downloads ~500 MB — this may take a few minutes on a fresh install."
echo ""

docker run -d \
    --name "${CONTAINER_NAME}" \
    --restart unless-stopped \
    -p 127.0.0.1:${HOST_PORT}:8000 \
    -e WHISPER__MODEL="${WHISPER_MODEL}" \
    -e API_KEY="${API_TOKEN}" \
    -v "${CACHE_VOLUME}":/home/user/.cache \
    "${IMAGE}"

echo "✓ Container started"

# ── 5. Wait for the server to become ready (up to 3 min for model download) ───
echo ""
echo "▸ Waiting for Speaches to become ready (model download on first run …)"
ATTEMPTS=0
MAX_ATTEMPTS=36  # 36 × 5s = 3 min
until curl -sf "http://127.0.0.1:${HOST_PORT}/health" -o /dev/null 2>&1; do
    ATTEMPTS=$((ATTEMPTS + 1))
    if [ $ATTEMPTS -ge $MAX_ATTEMPTS ]; then
        echo ""
        echo "✗ Speaches did not start within 3 minutes."
        echo "  Check logs: docker logs ${CONTAINER_NAME}"
        exit 1
    fi
    printf '.'
    sleep 5
done
echo ""
echo "✓ Speaches is healthy"

# ── 6. Smoke test ─────────────────────────────────────────────────────────────
echo ""
echo "▸ Running a quick transcription smoke test …"
# Generate a 1-second silent WAV and try transcribing it
python3 - <<'PYEOF'
import wave, struct, tempfile, subprocess, sys, os
path = tempfile.mktemp(suffix='.wav')
with wave.open(path, 'w') as f:
    f.setnchannels(1); f.setsampwidth(2); f.setframerate(16000)
    f.writeframes(struct.pack('<' + '0h' * 0))  # empty audio
os.environ['TEST_WAV'] = path
PYEOF
# The silent audio gives an empty transcription — that's fine; we just need HTTP 200
HTTP_CODE=$(curl -sf -o /dev/null -w "%{http_code}" \
    -X POST "http://127.0.0.1:${HOST_PORT}/v1/audio/transcriptions" \
    -H "Authorization: Bearer ${API_TOKEN}" \
    -F "file=@/dev/null;filename=test.wav;type=audio/wav" \
    -F "model=${WHISPER_MODEL}" \
    2>/dev/null || echo "000")
if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "422" ]; then
    echo "✓ API responding (HTTP ${HTTP_CODE})"
else
    echo "⚠  Smoke test got HTTP ${HTTP_CODE} — server may still be loading. Check: docker logs ${CONTAINER_NAME}"
fi

# ── 7. Print summary ──────────────────────────────────────────────────────────
echo ""
echo "═══════════════════════════════════════════"
echo "  Speaches is running!"
echo "═══════════════════════════════════════════"
echo ""
echo "  Internal URL:  http://127.0.0.1:${HOST_PORT}"
echo "  API token:     ${API_TOKEN}"
echo "  Model:         ${WHISPER_MODEL}"
echo ""
echo "  → In WordPress Admin > AE Settings:"
echo "       Speaches URL:   http://127.0.0.1:${HOST_PORT}"
echo "       Speaches Token: ${API_TOKEN}"
echo ""
echo "  Logs:    docker logs -f ${CONTAINER_NAME}"
echo "  Restart: docker restart ${CONTAINER_NAME}"
echo "  Stop:    docker stop ${CONTAINER_NAME}"
echo ""
