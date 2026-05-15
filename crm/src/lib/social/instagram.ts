/**
 * Instagram adapter via Meta Graph API v19.
 * Requires: instagram_basic, instagram_content_publish, instagram_manage_insights
 * Docs: https://developers.facebook.com/docs/instagram-api
 */
import type { SocialAdapter, PublishPayload, PublishResult, PostMetrics, AccountProfile } from "./types";

const GRAPH = "https://graph.facebook.com/v19.0";

export const instagramAdapter: SocialAdapter = {
  getAuthUrl(redirectUri, state) {
    const params = new URLSearchParams({
      client_id: process.env.INSTAGRAM_APP_ID || "",
      redirect_uri: redirectUri,
      scope: "instagram_basic,instagram_content_publish,instagram_manage_insights,pages_show_list",
      response_type: "code",
      state,
    });
    return `https://api.instagram.com/oauth/authorize?${params}`;
  },

  async exchangeCode(code, redirectUri) {
    const res = await fetch("https://api.instagram.com/oauth/access_token", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        client_id: process.env.INSTAGRAM_APP_ID || "",
        client_secret: process.env.INSTAGRAM_APP_SECRET || "",
        grant_type: "authorization_code",
        redirect_uri: redirectUri,
        code,
      }),
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error_message || "Instagram OAuth failed");

    // Exchange short-lived token for long-lived token
    const longRes = await fetch(
      `${GRAPH}/oauth/access_token?grant_type=fb_exchange_token&client_id=${process.env.INSTAGRAM_APP_ID}&client_secret=${process.env.INSTAGRAM_APP_SECRET}&fb_exchange_token=${data.access_token}`
    );
    const longData = await longRes.json();

    return {
      accessToken: longData.access_token || data.access_token,
      expiresIn: longData.expires_in,
    };
  },

  async refreshAccessToken(refreshToken) {
    // Instagram long-lived tokens can be refreshed within 60 days
    const res = await fetch(
      `${GRAPH}/refresh_access_token?grant_type=ig_refresh_token&access_token=${refreshToken}`
    );
    const data = await res.json();
    if (!res.ok) throw new Error(data.error?.message || "Token refresh failed");
    return { accessToken: data.access_token, expiresIn: data.expires_in };
  },

  async getProfile(accessToken) {
    const res = await fetch(
      `${GRAPH}/me?fields=id,name,username,profile_picture_url,followers_count,website&access_token=${accessToken}`
    );
    const data = await res.json();
    if (!res.ok) throw new Error(data.error?.message || "Profile fetch failed");
    return {
      accountId: data.id,
      accountName: data.name,
      accountHandle: data.username ? `@${data.username}` : undefined,
      profileUrl: data.website || `https://instagram.com/${data.username}`,
      avatarUrl: data.profile_picture_url,
      followerCount: data.followers_count,
    };
  },

  async publish(accessToken, payload, accountId) {
    try {
      const captionParts = [payload.content];
      if (payload.hashtags?.length) captionParts.push(payload.hashtags.join(" "));
      const caption = captionParts.join("\n\n");

      // Step 1: Create media container
      const containerBody: Record<string, string> = {
        caption,
        access_token: accessToken,
      };

      if (payload.mediaUrls?.length === 1) {
        const url = payload.mediaUrls[0];
        const isVideo = /\.(mp4|mov|avi)$/i.test(url);
        if (isVideo) {
          containerBody.media_type = "VIDEO";
          containerBody.video_url = url;
        } else {
          containerBody.image_url = url;
        }
      } else if (payload.mediaUrls && payload.mediaUrls.length > 1) {
        // Carousel: create individual items first
        const itemIds: string[] = [];
        for (const url of payload.mediaUrls) {
          const itemRes = await fetch(`${GRAPH}/${accountId}/media`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ image_url: url, is_carousel_item: true, access_token: accessToken }),
          });
          const item = await itemRes.json();
          if (item.id) itemIds.push(item.id);
        }
        containerBody.media_type = "CAROUSEL";
        containerBody.children = itemIds.join(",");
      } else {
        // Text-only not supported on Instagram — use a placeholder image approach
        // In production, require at least one image
        containerBody.image_url = "https://via.placeholder.com/1080x1080.png?text=Post";
      }

      const containerRes = await fetch(`${GRAPH}/${accountId}/media`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(containerBody),
      });
      const container = await containerRes.json();
      if (!containerRes.ok || !container.id) {
        return { success: false, error: container.error?.message || "Container creation failed" };
      }

      // Step 2: Publish the container
      const publishRes = await fetch(`${GRAPH}/${accountId}/media_publish`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ creation_id: container.id, access_token: accessToken }),
      });
      const published = await publishRes.json();
      if (!publishRes.ok || !published.id) {
        return { success: false, error: published.error?.message || "Publish failed" };
      }

      return {
        success: true,
        externalPostId: published.id,
        postUrl: `https://instagram.com/p/${published.id}`,
      };
    } catch (err: any) {
      return { success: false, error: err.message };
    }
  },

  async getMetrics(accessToken, externalPostId) {
    const fields = "like_count,comments_count,shares_count,reach,impressions";
    const res = await fetch(
      `${GRAPH}/${externalPostId}?fields=${fields}&access_token=${accessToken}`
    );
    const data = await res.json();

    const insightsRes = await fetch(
      `${GRAPH}/${externalPostId}/insights?metric=reach,impressions,profile_visits&access_token=${accessToken}`
    );
    const insights = await insightsRes.json();
    const insightMap: Record<string, number> = {};
    for (const item of insights?.data || []) {
      insightMap[item.name] = item.values?.[0]?.value || 0;
    }

    return {
      likes: data.like_count || 0,
      comments: data.comments_count || 0,
      shares: data.shares_count || 0,
      reach: insightMap.reach || 0,
      impressions: insightMap.impressions || 0,
      clicks: insightMap.profile_visits || 0,
    };
  },
};
