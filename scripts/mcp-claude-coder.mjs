#!/usr/bin/env node
/**
 * MCP server — local claude-coder agent
 * Bridges Claude Code to the Ollama proxy at localhost:4000.
 * Runs as a stdio process; no network port is opened.
 */

import { McpServer }          from '@modelcontextprotocol/sdk/server/mcp.js';
import { StdioServerTransport } from '@modelcontextprotocol/sdk/server/stdio.js';
import { z }                  from 'zod';

const ENDPOINT = process.env.CLAUDE_CODER_URL   || 'http://localhost:4000/api/chat';
const MODEL    = process.env.CLAUDE_CODER_MODEL || 'claude-coder';

const server = new McpServer({ name: 'claude-coder', version: '1.0.0' });

server.tool(
  'call_claude_coder',
  'Send a coding prompt to the local claude-coder Ollama agent and return its response.',
  { prompt: z.string().describe('The coding task or question to send to claude-coder') },
  async ({ prompt }) => {
    let res;
    try {
      res = await fetch(ENDPOINT, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({
          model:    MODEL,
          stream:   false,
          messages: [{ role: 'user', content: prompt }],
        }),
        signal: AbortSignal.timeout(120_000),
      });
    } catch (err) {
      return { content: [{ type: 'text', text: `ERROR: could not reach ${ENDPOINT} — ${err.message}` }], isError: true };
    }

    if (!res.ok) {
      return { content: [{ type: 'text', text: `ERROR: agent returned HTTP ${res.status}` }], isError: true };
    }

    const data = await res.json();
    const text = data?.message?.content ?? '';

    if (!text) {
      return { content: [{ type: 'text', text: 'ERROR: empty response from agent' }], isError: true };
    }

    return { content: [{ type: 'text', text }] };
  }
);

const transport = new StdioServerTransport();
await server.connect(transport);
