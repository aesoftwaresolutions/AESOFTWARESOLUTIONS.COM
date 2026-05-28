/**
 * LinkedIn adapter via LinkedIn API v2.
 * Requires: w_member_social, r_basicprofile, r_organization_social
 * Docs: https://learn.microsoft.com/en-us/linkedin/marketing/integrations/community-management/shares
 */
import type { SocialAdapter, PublishPayload, PublishResult, PostMetrics, AccountProfile } from "./types";

const API = "https://api.linkedin.com/v2";

export const linkedinAdapter: SocialAdapter = {
  getAuthUrl(redirectUri, state) {
    const params = new URLSearchParams({
      response_type: "code",
      client_id: process.env.LINKEDIN_CLIENT_ID || "",
      redirect_uri: redirectUri,
      scope: "r_basicprofile w_member_social r_organization_social rw_organization_admin",
      state,
    });
    return `https://www.linkedin.com/oauth/v2/authorization?${params}`;
  },

  async exchangeCode(code, redirectUri) {
    const res = await fetch("https://www.linkedin.com/oauth/v2/accessToken", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        grant_type: "authorization_code",
        code,
        redirect_uri: redirectUri,
        client_id: process.env.LINKEDIN_CLIENT_ID || "",
        client_secret: process.env.LINKEDIN_CLIENT_SECRET || "",
      }),
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error_description || "LinkedIn OAuth failed");
    return {
      accessToken: data.access_token,
      refreshToken: data.refresh_token,
      expiresIn: data.expires_in,
      scope: data.scope,
    };
  },

  async refreshAccessToken(refreshToken) {
    const res = await fetch("https://www.linkedin.com/oauth/v2/accessToken", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        grant_type: "refresh_token",
        refresh_token: refreshToken,
        client_id: process.env.LINKEDIN_CLIENT_ID || "",
        client_secret: process.env.LINKEDIN_CLIENT_SECRET || "",
      }),
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error_description || "Token refresh failed");
    return { accessToken: data.access_token, refreshToken: data.refresh_token, expiresIn: data.expires_in };
  },

  async getProfile(accessToken) {
    const res = await fetch(
      `${API}/me?projection=(id,localizedFirstName,localizedLastName,profilePicture(displayImage~:playableStreams),vanityName)`,
      { headers: { Authorization: `Bearer ${accessToken}` } }
    );
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || "Profile fetch failed");

    const avatarUrl = data.profilePicture?.["displayImage~"]?.elements?.[0]?.identifiers?.[0]?.identifier;
    return {
      accountId: data.id,
      accountName: `${data.localizedFirstName} ${data.localizedLastName}`,
      accountHandle: data.vanityName ? `@${data.vanityName}` : undefined,
      profileUrl: data.vanityName ? `https://linkedin.com/in/${data.vanityName}` : `https://linkedin.com`,
      avatarUrl,
    };
  },

  async publish(accessToken, payload, accountId) {
    try {
      const parts = [payload.content];
      if (payload.hashtags?.length) parts.push(payload.hashtags.join(" "));
      const text = parts.join("\n\n");

      const body: Record<string, unknown> = {
        author: `urn:li:person:${accountId}`,
        lifecycleState: "PUBLISHED",
        specificContent: {
          "com.linkedin.ugc.ShareContent": {
            shareCommentary: { text },
            shareMediaCategory: "NONE",
          },
        },
        visibility: { "com.linkedin.ugc.MemberNetworkVisibility": "PUBLIC" },
      };

      if (payload.mediaUrls?.length) {
        // Simplified: reference media by URL. Full implementation needs upload step.
        const media = payload.mediaUrls.map((url) => ({
          status: "READY",
          originalUrl: url,
        }));
        (body.specificContent as any)["com.linkedin.ugc.ShareContent"].shareMediaCategory = "IMAGE";
        (body.specificContent as any)["com.linkedin.ugc.ShareContent"].media = media;
      }

      const res = await fetch(`${API}/ugcPosts`, {
        method: "POST",
        headers: {
          Authorization: `Bearer ${accessToken}`,
          "Content-Type": "application/json",
          "X-Restli-Protocol-Version": "2.0.0",
        },
        body: JSON.stringify(body),
      });
      const data = await res.json();
      if (!res.ok) return { success: false, error: data.message || "Post failed" };

      const postId = res.headers.get("x-restli-id") || data.id;
      return {
        success: true,
        externalPostId: postId || undefined,
        postUrl: postId ? `https://linkedin.com/feed/update/${postId}` : undefined,
      };
    } catch (err: any) {
      return { success: false, error: err.message };
    }
  },

  async getMetrics(accessToken, externalPostId) {
    const encoded = encodeURIComponent(externalPostId);
    const res = await fetch(
      `${API}/organizationalEntityShareStatistics?q=organizationalEntity&organizationalEntity=${encoded}&shares[0]=${encoded}`,
      { headers: { Authorization: `Bearer ${accessToken}`, "X-Restli-Protocol-Version": "2.0.0" } }
    );
    const data = await res.json();
    const stats = data.elements?.[0]?.totalShareStatistics || {};
    return {
      likes: stats.likeCount || 0,
      comments: stats.commentCount || 0,
      shares: stats.shareCount || 0,
      reach: stats.uniqueImpressionsCount || 0,
      impressions: stats.impressionCount || 0,
      clicks: stats.clickCount || 0,
    };
  },
};
