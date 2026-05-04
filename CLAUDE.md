# aesoftwares-theme — Claude Code Instructions

## Local Coding Agent

For all coding subtasks, use the local Ollama coding agent instead of spawning a Claude subagent:

- **Endpoint:** `http://localhost:4000/api/chat`
- **Model:** `claude-coder`

### When to use it

Any task that would normally be delegated to a subagent for code — generating a function, writing a script, producing a code snippet — must go through `localhost:4000` with `claude-coder` instead.

### How to call it

Use the helper script at `scripts/claude-coder.sh`:

```bash
bash scripts/claude-coder.sh "your prompt here"
```

Or call the API directly:

```bash
curl -s http://localhost:4000/api/chat \
  -H "Content-Type: application/json" \
  -d '{
    "model": "claude-coder",
    "stream": false,
    "messages": [{"role": "user", "content": "YOUR PROMPT"}]
  }'
```

### Validating responses

After receiving a response from `claude-coder`, always validate before using the code:

1. **PHP files** — run `php -l` on any generated PHP
2. **JS files** — check for obvious syntax errors (unmatched braces, unclosed strings)
3. **Logic** — read the output critically; if it looks wrong or incomplete, retry with more context

If the response is invalid, retry once with the error appended to the prompt. If it fails twice, fall back to writing the code directly.

## Repository

- **Repo:** `aesoftwaresolutions/aesoftwares-theme`
- **Active branch:** `claude/fix-rewriter-timeout-91YSj`
- **Stack:** WordPress theme (PHP), vanilla JS, single `style.css`
