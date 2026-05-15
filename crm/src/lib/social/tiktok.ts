/**
 * TikTok adapter via TikTok Content Posting API.
 * Requires: video.publish, video.upload, user.info.basic
 * Docs: https://developers.tiktok.com/doc/content-posting-api-get-started
 */
import type { SocialAdapter, PublishPayload, PublishResult, PostMetrics, AccountProfile } from "./types";

const API = "https://open.tiktokapis.com/v2";

export const tiktokAdapter: SocialAdapter = {
  getAuthUrl(redirectUri, state) {
    const params = new URLSearchParams({
      client_key: process.env.TIKTOK_CLIENT_KEY || "",
      redirect_uri: redirectUri,
      scope: "user.info.basic,video.publish,video.upload",
      response_type: "code",
      state,
    });
    return `https://www.tiktok.com/v2/auth/authorize?${params}`;
  },

  async exchangeCode(code, redirectUri) {
    const res = await fetch(`${API}/oauth/token/`, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        client_key: process.env.TIKTOK_CLIENT_KEY || "",
        client_secret: process.env.TIKTOK_CLIENT_SECRET || "",
        code,
        grant_type: "authorization_code",
        redirect_uri: redirectUri,
      }),
    });
    const data = await res.json();
    if (!res.ok || data.error) throw new Error(data.error_description || "TikTok OAuth failed");
    return {
      accessToken: data.access_token,
      refreshToken: data.refresh_token,
      expiresIn: data.expires_in,
      scope: data.scope,
    };
  },

  async refreshAccessToken(refreshToken) {
    const res = await fetch(`${API}/oauth/token/`, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        client_key: process.env.TIKTOK_CLIENT_KEY || "",
        client_secret: process.env.TIKTOK_CLIENT_SECRET || "",
        grant_type: "refresh_token",
        refresh_token: refreshToken,
      }),
    });
    const data = await res.json();
    if (!res.ok || data.error) throw new Error(data.error_description || "Token refresh failed");
    return { accessToken: data.access_token, refreshToken: data.refresh_token, expiresIn: data.expires_in };
  },

  async getProfile(accessToken) {
    const res = await fetch(`${API}/user/info/?fields=open_id,display_name,avatar_url,profile_deep_link,follower_count`, {
      headers: { Authorization: `Bearer ${accessToken}` },
    });
    const data = await res.json();
    if (!res.ok || data.error) throw new Error(data.error?.message || "Profile fetch failed");
    const user = data.data?.user;
    return {
      accountId: user.open_id,
      accountName: user.display_name,
      profileUrl: user.profile_deep_link,
      avatarUrl: user.avatar_url,
      followerCount: user.follower_count,
    };
  },

  async publish(accessToken, payload) {
    try {
      // TikTok Content Posting requires a video file URL
      // Images/carousels are supported via the Photo Post API
      const videoUrl = payload.mediaUrls?.find((u) => /\.(mp4|mov)$/i.test(u));
      const imageUrls = payload.mediaUrls?.filter((u) => /\.(jpg|jpeg|png|webp)$/i.test(u));

      if (videoUrl) {
        const res = await fetch(`${API}/post/publish/video/init/`, {
          method: "POST",
          headers: { Authorization: `Bearer ${accessToken}`, "Content-Type": "application/json" },
          body: JSON.stringify({
            post_info: {
              title: payload.content.slice(0, 150),
              privacy_level: "PUBLIC_TO_EVERYONE",
              disable_duet: false,
              disable_comment: false,
              disable_stitch: false,
            },
            source_info: { source: "PULL_FROM_URL", video_url: videoUrl },
          }),
        });
        const data = await res.json();
        if (!res.ok || data.error) return { success: false, error: data.error?.message || "Video publish failed" };
        return { success: true, externalPostId: data.data?.publish_id };
      }

      if (imageUrls?.length) {
        const parts = [payload.content];
        if (payload.hashtags?.length) parts.push(payload.hashtags.join(" "));

        const res = await fetch(`${API}/post/publish/content/init/`, {
          method: "POST",
          headers: { Authorization: `Bearer ${accessToken}`, "Content-Type": "application/json" },
          body: JSON.stringify({
            post_info: {
              title: parts.join("\n"),
              privacy_level: "PUBLIC_TO_EVERYONE",
              disable_comment: false,
            },
            source_info: {
              source: "PULL_FROM_URL",
              photo_cover_index: 0,
              photo_images: imageUrls,
            },
            post_mode: "DIRECT_POST",
            media_type: "PHOTO",
          }),
        });
        const data = await res.json();
        if (!res.ok || data.error) return { success: false, error: data.error?.message || "Photo publish failed" };
        return { success: true, externalPostId: data.data?.publish_id };
      }

      return { success: false, error: "TikTok requires at least one video or image URL" };
    } catch (err: any) {
      return { success: false, error: err.message };
    }
  },

  async getMetrics(accessToken, externalPostId) {
    const res = await fetch(`${API}/video/query/?fields=id,like_count,comment_count,share_count,view_count`, {
      method: "POST",
      headers: { Authorization: `Bearer ${accessToken}`, "Content-Type": "application/json" },
      body: JSON.stringify({ filters: { video_ids: [externalPostId] } }),
    });
    const data = await res.json();
    const v = data.data?.videos?.[0] || {};
    return {
      likes: v.like_count || 0,
      comments: v.comment_count || 0,
      shares: v.share_count || 0,
      reach: v.view_count || 0,
      impressions: v.view_count || 0,
      clicks: 0,
    };
  },
};
