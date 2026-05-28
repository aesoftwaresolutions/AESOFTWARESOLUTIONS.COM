/**
 * Facebook Page adapter via Meta Graph API v19.
 * Requires: pages_manage_posts, pages_read_engagement, pages_show_list
 * Docs: https://developers.facebook.com/docs/graph-api/reference/page/feed
 */
import type { SocialAdapter, PublishPayload, PublishResult, PostMetrics, AccountProfile } from "./types";

const GRAPH = "https://graph.facebook.com/v19.0";

export const facebookAdapter: SocialAdapter = {
  getAuthUrl(redirectUri, state) {
    const params = new URLSearchParams({
      client_id: process.env.FACEBOOK_APP_ID || "",
      redirect_uri: redirectUri,
      scope: "pages_manage_posts,pages_read_engagement,pages_show_list,instagram_basic,instagram_content_publish",
      response_type: "code",
      state,
    });
    return `https://www.facebook.com/v19.0/dialog/oauth?${params}`;
  },

  async exchangeCode(code, redirectUri) {
    const res = await fetch(
      `${GRAPH}/oauth/access_token?client_id=${process.env.FACEBOOK_APP_ID}&redirect_uri=${encodeURIComponent(redirectUri)}&client_secret=${process.env.FACEBOOK_APP_SECRET}&code=${code}`
    );
    const data = await res.json();
    if (!res.ok) throw new Error(data.error?.message || "Facebook OAuth failed");
    return {
      accessToken: data.access_token,
      expiresIn: data.expires_in,
      scope: data.scope,
    };
  },

  async refreshAccessToken(refreshToken) {
    // Facebook user tokens can be extended; page tokens are non-expiring
    const res = await fetch(
      `${GRAPH}/oauth/access_token?grant_type=fb_exchange_token&client_id=${process.env.FACEBOOK_APP_ID}&client_secret=${process.env.FACEBOOK_APP_SECRET}&fb_exchange_token=${refreshToken}`
    );
    const data = await res.json();
    if (!res.ok) throw new Error(data.error?.message || "Token refresh failed");
    return { accessToken: data.access_token, expiresIn: data.expires_in };
  },

  async getProfiles(accessToken) {
    // Also fetch token debug info (scopes) to aid troubleshooting.
    // debug_token requires an App Access Token (appId|appSecret) as the bearer.
    const appToken = `${process.env.FACEBOOK_APP_ID}|${process.env.FACEBOOK_APP_SECRET}`;
    const [accountsRes, debugRes, meRes] = await Promise.all([
      fetch(`${GRAPH}/me/accounts?fields=id,name,username,picture,fan_count,link&access_token=${accessToken}`),
      fetch(`${GRAPH}/debug_token?input_token=${accessToken}&access_token=${encodeURIComponent(appToken)}`),
      fetch(`${GRAPH}/me?fields=id,name,email&access_token=${accessToken}`),
    ]);

    const data = await accountsRes.json();
    const debugData = await debugRes.json().catch(() => ({}));
    const meData = await meRes.json().catch(() => ({}));
    const grantedScopes: string[] = debugData?.data?.scopes ?? [];

    // Log full raw response for /me/accounts
    console.log("[Facebook OAuth] /me/accounts full response:", JSON.stringify(data).substring(0, 500));

    // Log user token validity
    console.log("[Facebook OAuth] /me user token validation:", JSON.stringify({
      id: meData?.id,
      name: meData?.name,
      email: meData?.email,
      error: meData?.error,
    }));

    console.log("[Facebook OAuth] /me/accounts response:", JSON.stringify({
      ok: accountsRes.ok,
      pageCount: data.data?.length ?? 0,
      error: data.error,
      grantedScopes,
    }));

    if (!accountsRes.ok) throw new Error(data.error?.message || "Profile fetch failed");

    let pages: any[] = data.data ?? [];

    // Fallback: if /me/accounts returned empty, try again with explicit limit
    if (!pages.length) {
      console.log("[Facebook OAuth] /me/accounts returned empty, trying fallback with explicit limit...");
      const fallbackRes = await fetch(
        `${GRAPH}/me/accounts?fields=id,name,username,picture,fan_count,link&limit=100&access_token=${accessToken}`
      );
      const fallbackData = await fallbackRes.json();
      console.log("[Facebook OAuth] Fallback /me/accounts response:", JSON.stringify(fallbackData).substring(0, 500));
      pages = fallbackData.data ?? [];
    }

    if (!pages.length) {
      const scopeInfo = grantedScopes.length
        ? `Granted scopes: ${grantedScopes.join(", ")}`
        : "Could not retrieve token scopes";
      throw new Error(
        `No Facebook Pages were shared with this app. ` +
        `Please reconnect and select at least one Page in the authorization dialog. ` +
        `(${scopeInfo}) | /me/accounts returned: ${JSON.stringify(data).substring(0, 200)}`
      );
    }

    return pages.map((page) => ({
      accountId: page.id,
      accountName: page.name,
      accountHandle: page.username ? `@${page.username}` : undefined,
      profileUrl: page.link || `https://facebook.com/${page.id}`,
      avatarUrl: page.picture?.data?.url,
      followerCount: page.fan_count,
    }));
  },

  async getProfile(accessToken) {
    // Delegates to getProfiles and returns the first page
    const profiles = await facebookAdapter.getProfiles!(accessToken);
    return profiles[0];
  },

  async publish(accessToken, payload, accountId) {
    try {
      const captionParts = [payload.content];
      if (payload.hashtags?.length) captionParts.push(payload.hashtags.join(" "));
      const message = captionParts.join("\n\n");

      const body: Record<string, unknown> = { message, access_token: accessToken };

      if (payload.mediaUrls?.length === 1) {
        const url = payload.mediaUrls[0];
        const isVideo = /\.(mp4|mov)$/i.test(url);
        if (isVideo) {
          body.file_url = url;
          const res = await fetch(`${GRAPH}/${accountId}/videos`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(body),
          });
          const data = await res.json();
          if (!res.ok) return { success: false, error: data.error?.message };
          return { success: true, externalPostId: data.id };
        } else {
          body.url = url;
          const res = await fetch(`${GRAPH}/${accountId}/photos`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(body),
          });
          const data = await res.json();
          if (!res.ok) return { success: false, error: data.error?.message };
          return {
            success: true,
            externalPostId: data.post_id || data.id,
            postUrl: `https://facebook.com/${data.post_id || data.id}`,
          };
        }
      }

      // Text post or multiple images
      const res = await fetch(`${GRAPH}/${accountId}/feed`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body),
      });
      const data = await res.json();
      if (!res.ok) return { success: false, error: data.error?.message };
      return {
        success: true,
        externalPostId: data.id,
        postUrl: `https://facebook.com/${data.id}`,
      };
    } catch (err: any) {
      return { success: false, error: err.message };
    }
  },

  async getMetrics(accessToken, externalPostId) {
    const fields = "likes.summary(true),comments.summary(true),shares,insights.metric(post_impressions,post_reach,post_clicks)";
    const res = await fetch(
      `${GRAPH}/${externalPostId}?fields=${fields}&access_token=${accessToken}`
    );
    const data = await res.json();

    const insightMap: Record<string, number> = {};
    for (const item of data.insights?.data || []) {
      insightMap[item.name] = item.values?.[0]?.value || 0;
    }

    return {
      likes: data.likes?.summary?.total_count || 0,
      comments: data.comments?.summary?.total_count || 0,
      shares: data.shares?.count || 0,
      reach: insightMap.post_reach || 0,
      impressions: insightMap.post_impressions || 0,
      clicks: insightMap.post_clicks || 0,
    };
  },
};
