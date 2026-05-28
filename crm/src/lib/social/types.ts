export interface PublishPayload {
  content: string;
  mediaUrls?: string[];
  hashtags?: string[];
  /** ISO datetime string for scheduling (platform-side if supported) */
  scheduledAt?: string;
}

export interface PublishResult {
  success: boolean;
  externalPostId?: string;
  postUrl?: string;
  error?: string;
  rawResponse?: unknown;
}

export interface PostMetrics {
  likes: number;
  shares: number;
  comments: number;
  reach: number;
  impressions: number;
  clicks: number;
}

export interface AccountProfile {
  accountId: string;
  accountName: string;
  accountHandle?: string;
  profileUrl?: string;
  avatarUrl?: string;
  followerCount?: number;
  scopes?: string[];
}

export interface SocialAdapter {
  /** Publish a post. Returns the platform post ID on success. */
  publish(accessToken: string, payload: PublishPayload, accountId: string): Promise<PublishResult>;
  /** Fetch engagement metrics for a given platform post ID. */
  getMetrics(accessToken: string, externalPostId: string): Promise<PostMetrics>;
  /** Exchange an OAuth code for access + refresh tokens. */
  exchangeCode(code: string, redirectUri: string): Promise<{
    accessToken: string;
    refreshToken?: string;
    expiresIn?: number;
    scope?: string;
  }>;
  /** Refresh a stale access token. */
  refreshAccessToken(refreshToken: string): Promise<{
    accessToken: string;
    refreshToken?: string;
    expiresIn?: number;
  }>;
  /** Get the connected account profile. */
  getProfile(accessToken: string): Promise<AccountProfile>;
  /**
   * Get all connected account profiles. Optional — implement on platforms
   * (e.g. Facebook) where one token can grant access to multiple accounts/pages.
   * The callback will use this instead of getProfile when present.
   */
  getProfiles?: (accessToken: string) => Promise<AccountProfile[]>;
  /** Build the OAuth authorization URL. */
  getAuthUrl(redirectUri: string, state: string): string;
}
