import { NextRequest, NextResponse } from "next/server";
import { connectDB } from "@/lib/db/connection";
import { SocialAccount, AuditLog } from "@/lib/db/models";
import { getAdapter, encryptToken, getOAuthRedirectUri } from "@/lib/social";

/**
 * OAuth callback handler for all social platforms.
 * Platform navigates back here after user grants permission.
 * URL: /api/social/oauth/callback/[platform]?code=...&state=...
 */
export async function GET(req: NextRequest, { params }: { params: { platform: string } }) {
  const { platform } = params;
  const url = new URL(req.url);
  const code = url.searchParams.get("code");
  const stateB64 = url.searchParams.get("state");
  const error = url.searchParams.get("error");

  const dashboardUrl = process.env.NEXTAUTH_URL
    ? `${process.env.NEXTAUTH_URL}/social`
    : "/social";

  if (error) {
    return NextResponse.redirect(`${dashboardUrl}?error=${encodeURIComponent(error)}`);
  }

  if (!code || !stateB64) {
    return NextResponse.redirect(`${dashboardUrl}?error=missing_params`);
  }

  let userId: string;
  let codeVerifier: string | undefined;
  try {
    const state = JSON.parse(Buffer.from(stateB64, "base64url").toString());
    userId = state.userId;
    codeVerifier = state.codeVerifier; // For Twitter PKCE
    if (!userId) throw new Error("No userId in state");
  } catch {
    return NextResponse.redirect(`${dashboardUrl}?error=invalid_state`);
  }

  try {
    const adapter = getAdapter(platform);
    const redirectUri = getOAuthRedirectUri(platform);

    // For Twitter: pass the PKCE verifier alongside the code
    const codeForExchange = platform === "twitter" && codeVerifier
      ? `${code}|${codeVerifier}`
      : code;

    const tokens = await adapter.exchangeCode(codeForExchange, redirectUri);

    // Use getProfiles if available (e.g. Facebook returns one token for multiple Pages)
    // otherwise fall back to single getProfile
    const profiles = adapter.getProfiles
      ? await adapter.getProfiles(tokens.accessToken)
      : [await adapter.getProfile(tokens.accessToken)];

    const encryptedAccess = encryptToken(tokens.accessToken);
    const encryptedRefresh = tokens.refreshToken ? encryptToken(tokens.refreshToken) : undefined;
    const tokenExpiry = tokens.expiresIn ? new Date(Date.now() + tokens.expiresIn * 1000) : undefined;

    await connectDB();

    // Upsert one SocialAccount per profile (supports multi-page Facebook connections)
    for (const profile of profiles) {
      await SocialAccount.findOneAndUpdate(
        { platform, accountId: profile.accountId },
        {
          $set: {
            accountName: profile.accountName,
            accountHandle: profile.accountHandle,
            profileUrl: profile.profileUrl,
            avatarUrl: profile.avatarUrl,
            followerCount: profile.followerCount,
            accessToken: encryptedAccess,
            ...(encryptedRefresh && { refreshToken: encryptedRefresh }),
            ...(tokenExpiry && { tokenExpiry }),
            scopes: profile.scopes || [],
            isActive: true,
            connectedBy: userId,
            lastSyncedAt: new Date(),
          },
        },
        { upsert: true, new: true }
      );
    }

    AuditLog.create({
      action: "social_account.connected",
      resource: "social_account",
      resourceId: profiles[0].accountId,
      userId,
      details: { platform, accountName: profiles[0].accountName, totalConnected: profiles.length },
    }).catch(console.error);

    return NextResponse.redirect(`${dashboardUrl}?connected=${platform}`);
  } catch (err: any) {
    console.error(`[Social OAuth] ${platform} callback error:`, err);
    return NextResponse.redirect(`${dashboardUrl}?error=${encodeURIComponent(err.message)}`);
  }
}
