/**
 * Unified social media adapter registry.
 * All token encryption/decryption happens here — adapters only see plaintext tokens.
 */
import { createCipheriv, createDecipheriv, randomBytes, scryptSync } from "crypto";
import type { SocialAdapter } from "./types";
import { instagramAdapter } from "./instagram";
import { facebookAdapter } from "./facebook";
import { twitterAdapter } from "./twitter";
import { linkedinAdapter } from "./linkedin";
import { tiktokAdapter } from "./tiktok";

export { type SocialAdapter, type PublishPayload, type PublishResult, type PostMetrics } from "./types";

const ADAPTERS: Record<string, SocialAdapter> = {
  instagram: instagramAdapter,
  facebook: facebookAdapter,
  twitter: twitterAdapter,
  linkedin: linkedinAdapter,
  tiktok: tiktokAdapter,
};

export function getAdapter(platform: string): SocialAdapter {
  const adapter = ADAPTERS[platform];
  if (!adapter) throw new Error(`No adapter for platform: ${platform}`);
  return adapter;
}

// AES-256-GCM token encryption — same pattern as PHI transfer layer
const ALGO = "aes-256-gcm";
const SCRYPT_SALT = "tenant-social-token-v1"; // Static salt — key uniqueness comes from the secret

let _derivedKey: Buffer | null = null;

function getEncryptionKey(): Buffer {
  if (_derivedKey) return _derivedKey;

  const secret = process.env.SOCIAL_TOKEN_ENCRYPTION_KEY || process.env.NEXTAUTH_SECRET;
  if (!secret) {
    throw new Error("SOCIAL_TOKEN_ENCRYPTION_KEY or NEXTAUTH_SECRET must be set");
  }

  // If the key is already a 64-char hex string (32 bytes), use it directly
  if (/^[0-9a-fA-F]{64}$/.test(secret)) {
    _derivedKey = Buffer.from(secret, "hex");
  } else {
    // Derive a proper 32-byte key using scrypt
    _derivedKey = scryptSync(secret, SCRYPT_SALT, 32);
  }

  return _derivedKey;
}

export function encryptToken(plaintext: string): string {
  const key = getEncryptionKey();
  const iv = randomBytes(12);
  const cipher = createCipheriv(ALGO, key, iv);
  const encrypted = Buffer.concat([cipher.update(plaintext, "utf8"), cipher.final()]);
  const tag = cipher.getAuthTag();
  // Format: iv:tag:ciphertext (all hex)
  return `${iv.toString("hex")}:${tag.toString("hex")}:${encrypted.toString("hex")}`;
}

export function decryptToken(encrypted: string): string {
  const [ivHex, tagHex, dataHex] = encrypted.split(":");
  if (!ivHex || !tagHex || !dataHex) throw new Error("Invalid encrypted token format");
  const key = getEncryptionKey();
  const decipher = createDecipheriv(ALGO, key, Buffer.from(ivHex, "hex"));
  decipher.setAuthTag(Buffer.from(tagHex, "hex"));
  const decrypted = Buffer.concat([
    decipher.update(Buffer.from(dataHex, "hex")),
    decipher.final(),
  ]);
  return decrypted.toString("utf8");
}

/** Build the OAuth redirect URI for a given platform. */
export function getOAuthRedirectUri(platform: string): string {
  const base = process.env.NEXTAUTH_URL || "http://localhost:3000";
  return `${base}/api/social/oauth/callback/${platform}`;
}

/**
 * Centralized fetch wrapper for social platform API calls.
 * Adds timeouts, transient-failure retries with exponential backoff,
 * and normalized error handling.
 */
export async function socialFetch(
  url: string,
  options: RequestInit = {},
  config: { timeoutMs?: number; maxRetries?: number } = {}
): Promise<Response> {
  const { timeoutMs = 10000, maxRetries = 2 } = config;

  for (let attempt = 0; attempt <= maxRetries; attempt++) {
    const controller = new AbortController();
    const timer = setTimeout(() => controller.abort(), timeoutMs);

    try {
      const res = await fetch(url, { ...options, signal: controller.signal });
      clearTimeout(timer);

      // Retry on transient server errors
      if (attempt < maxRetries && (res.status === 429 || res.status >= 500)) {
        const backoff = Math.pow(2, attempt) * 500; // 500ms, 1s, 2s
        await new Promise((r) => setTimeout(r, backoff));
        continue;
      }

      return res;
    } catch (err: any) {
      clearTimeout(timer);
      if (attempt < maxRetries && (err.name === "AbortError" || err.code === "ECONNRESET")) {
        const backoff = Math.pow(2, attempt) * 500;
        await new Promise((r) => setTimeout(r, backoff));
        continue;
      }
      throw err;
    }
  }

  // Shouldn't reach here, but satisfy TypeScript
  throw new Error(`socialFetch: exhausted ${maxRetries} retries for ${url}`);
}
