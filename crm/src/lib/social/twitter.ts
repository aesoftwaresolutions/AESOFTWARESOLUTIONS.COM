/**
 * Twitter / X adapter via Twitter API v2.
 * Requires OAuth 2.0 with PKCE: tweet.read, tweet.write, users.read, offline.access
 * Docs: https://developer.twitter.com/en/docs/twitter-api
 */
import { createHash, randomBytes } from "crypto";
import type { SocialAdapter, PublishPayload, PublishResult, PostMetrics, AccountProfile } from "./types";

const API = "https://api.twitter.com/2";
const AUTH = "https://twitter.com/i/oauth2";

/**
 * Generate a proper PKCE code verifier + S256 challenge.
 * The verifier is passed through the OAuth state so it can be
 * recovered during the callback for the token exchange.
 */
function generatePKCE(): { codeVerifier: string; codeChallenge: string } {
  const codeVerifier = randomBytes(32).toString("base64url");
  const codeChallenge = createHash("sha256")
    .update(codeVerifier)
    .digest("base64url");
  return { codeVerifier, codeChallenge };
}

export const twitterAdapter: SocialAdapter = {
  getAuthUrl(redirectUri, state) {
    const { codeVerifier, codeChallenge } = generatePKCE();

    // Embed the PKCE verifier in the state so we can extract it in the callback.
    // The caller passes a base64url-encoded JSON state — we append the verifier.
    let stateObj: Record<string, unknown> = {};
    try { stateObj = JSON.parse(Buffer.from(state, "base64url").toString()); } catch { /* ignore */ }
    stateObj.codeVerifier = codeVerifier;
    const enrichedState = Buffer.from(JSON.stringify(stateObj)).toString("base64url");

    const params = new URLSearchParams({
      response_type: "code",
      client_id: process.env.TWITTER_CLIENT_ID || "",
      redirect_uri: redirectUri,
      scope: "tweet.read tweet.write users.read offline.access",
      state: enrichedState,
      code_challenge: codeChallenge,
      code_challenge_method: "S256",
    });
    return `${AUTH}/authorize?${params}`;
  },

  async exchangeCode(code, redirectUri) {
    // The codeVerifier is extracted from state in the OAuth callback handler
    // and passed as part of the code parameter as: code|codeVerifier
    let actualCode = code;
    let codeVerifier = "challenge"; // Fallback for legacy flows
    if (code.includes("|")) {
      [actualCode, codeVerifier] = code.split("|", 2);
    }

    const creds = Buffer.from(
      `${process.env.TWITTER_CLIENT_ID}:${process.env.TWITTER_CLIENT_SECRET}`
    ).toString("base64");

    const res = await fetch(`${AUTH}/token`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        Authorization: `Basic ${creds}`,
      },
      body: new URLSearchParams({
        code: actualCode,
        grant_type: "authorization_code",
        redirect_uri: redirectUri,
        code_verifier: codeVerifier,
      }),
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error_description || "Twitter OAuth failed");
    return {
      accessToken: data.access_token,
      refreshToken: data.refresh_token,
      expiresIn: data.expires_in,
      scope: data.scope,
    };
  },

  async refreshAccessToken(refreshToken) {
    const creds = Buffer.from(
      `${process.env.TWITTER_CLIENT_ID}:${process.env.TWITTER_CLIENT_SECRET}`
    ).toString("base64");

    const res = await fetch(`${AUTH}/token`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        Authorization: `Basic ${creds}`,
      },
      body: new URLSearchParams({ grant_type: "refresh_token", refresh_token: refreshToken }),
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error_description || "Token refresh failed");
    return {
      accessToken: data.access_token,
      refreshToken: data.refresh_token,
      expiresIn: data.expires_in,
    };
  },

  async getProfile(accessToken) {
    const res = await fetch(
      `${API}/users/me?user.fields=id,name,username,profile_image_url,public_metrics,url`,
      { headers: { Authorization: `Bearer ${accessToken}` } }
    );
    const data = await res.json();
    if (!res.ok) throw new Error(data.detail || "Profile fetch failed");
    const user = data.data;
    return {
      accountId: user.id,
      accountName: user.name,
      accountHandle: `@${user.username}`,
      profileUrl: `https://twitter.com/${user.username}`,
      avatarUrl: user.profile_image_url?.replace("_normal", ""),
      followerCount: user.public_metrics?.followers_count,
    };
  },

  async publish(accessToken, payload) {
    try {
      const parts = [payload.content];
      if (payload.hashtags?.length) parts.push(payload.hashtags.join(" "));
      const text = parts.join("\n\n").slice(0, 280); // enforce limit

      const body: Record<string, unknown> = { text };

      // Media upload: upload each image and attach media IDs
      // Twitter v2 media upload uses v1.1 endpoint with OAuth 1.0a - simplified here
      // In production, media_ids are obtained from the v1.1 media upload endpoint
      if (payload.mediaUrls?.length) {
        // Placeholder: in production, upload media and get IDs
        // body.media = { media_ids: [...uploadedIds] };
      }

      const res = await fetch(`${API}/tweets`, {
        method: "POST",
        headers: {
          Authorization: `Bearer ${accessToken}`,
          "Content-Type": "application/json",
        },
        body: JSON.stringify(body),
      });
      const data = await res.json();
      if (!res.ok) {
        return { success: false, error: data.detail || data.errors?.[0]?.message };
      }
      const tweetId = data.data?.id;
      return {
        success: true,
        externalPostId: tweetId,
        postUrl: tweetId ? `https://twitter.com/i/web/status/${tweetId}` : undefined,
      };
    } catch (err: any) {
      return { success: false, error: err.message };
    }
  },

  async getMetrics(accessToken, externalPostId) {
    const res = await fetch(
      `${API}/tweets/${externalPostId}?tweet.fields=public_metrics,non_public_metrics`,
      { headers: { Authorization: `Bearer ${accessToken}` } }
    );
    const data = await res.json();
    const m = data.data?.public_metrics || {};
    const nm = data.data?.non_public_metrics || {};
    return {
      likes: m.like_count || 0,
      shares: m.retweet_count || 0,
      comments: m.reply_count || 0,
      reach: m.impression_count || 0,
      impressions: m.impression_count || 0,
      clicks: nm.url_link_clicks || 0,
    };
  },
};
