#!/usr/bin/env bash
# Call the local claude-coder Ollama agent and print its response text.
# Usage: bash scripts/claude-coder.sh "your prompt"

set -euo pipefail

ENDPOINT="http://localhost:4000/api/chat"
MODEL="claude-coder"
PROMPT="${1:-}"

if [[ -z "$PROMPT" ]]; then
  echo "Usage: $0 \"prompt\"" >&2
  exit 1
fi

PAYLOAD=$(printf '{"model":"%s","stream":false,"messages":[{"role":"user","content":%s}]}' \
  "$MODEL" \
  "$(printf '%s' "$PROMPT" | python3 -c 'import json,sys; print(json.dumps(sys.stdin.read()))')")

RESPONSE=$(curl -sf --max-time 120 \
  -H "Content-Type: application/json" \
  -d "$PAYLOAD" \
  "$ENDPOINT")

if [[ -z "$RESPONSE" ]]; then
  echo "Error: no response from $ENDPOINT" >&2
  exit 1
fi

# Extract content from Ollama chat response: { message: { content: "..." } }
echo "$RESPONSE" | python3 -c '
import json, sys
data = json.load(sys.stdin)
print(data["message"]["content"])
'
