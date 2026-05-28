import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { SocialPost, SocialAccount, AuditLog } from "@/lib/db/models";
import { getAdapter, decryptToken } from "@/lib/social";

/**
 * POST /api/social-posts/[id]/publish
 * Publishes a draft/scheduled post to its target platform using the connected account.
 * Body: { accountId?: string } — if omitted, uses the first active account for the post's platform.
 */
export async function POST(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const post = await SocialPost.findById(params.id);
  if (!post) return NextResponse.json({ error: "Post not found" }, { status: 404 });

  if (post.status === "published") {
    return NextResponse.json({ error: "Post is already published" }, { status: 400 });
  }

  const body = await req.json().catch(() => ({}));
  const accountId = body.accountId;

  // Find the connected social account for this platform
  const accountQuery = accountId
    ? { _id: accountId, platform: post.platform, isActive: true }
    : { platform: post.platform, isActive: true };

  const account = await SocialAccount.findOne(accountQuery);
  if (!account) {
    return NextResponse.json(
      { error: `No connected ${post.platform} account found. Connect one in Social Media settings.` },
      { status: 400 }
    );
  }

  // Check token expiry and refresh if needed
  if (account.tokenExpiry && account.tokenExpiry < new Date()) {
    if (account.refreshToken) {
      try {
        const adapter = getAdapter(post.platform);
        const { encryptToken } = await import("@/lib/social");
        const plain = decryptToken(account.refreshToken);
        const refreshed = await adapter.refreshAccessToken(plain);
        account.accessToken = encryptToken(refreshed.accessToken);
        if (refreshed.refreshToken) account.refreshToken = encryptToken(refreshed.refreshToken);
        if (refreshed.expiresIn) account.tokenExpiry = new Date(Date.now() + refreshed.expiresIn * 1000);
        await account.save();
      } catch (err: any) {
        return NextResponse.json({ error: `Token expired and refresh failed: ${err.message}` }, { status: 401 });
      }
    } else {
      return NextResponse.json({ error: "Access token expired — reconnect the account" }, { status: 401 });
    }
  }

  const adapter = getAdapter(post.platform);
  const plainToken = decryptToken(account.accessToken);

  const result = await adapter.publish(plainToken, {
    content: post.content,
    mediaUrls: post.mediaUrls,
    hashtags: post.hashtags,
    scheduledAt: post.scheduledAt?.toISOString(),
  }, account.accountId);

  if (!result.success) {
    await SocialPost.findByIdAndUpdate(params.id, { status: "failed" });
    return NextResponse.json({ error: result.error || "Publish failed" }, { status: 502 });
  }

  await SocialPost.findByIdAndUpdate(params.id, {
    status: "published",
    publishedAt: new Date(),
    externalPostId: result.externalPostId,
  });

  AuditLog.create({
    action: "social_post.published",
    resource: "social_post",
    resourceId: params.id,
    userId: session.user.id,
    userName: session.user.name,
    details: { platform: post.platform, externalPostId: result.externalPostId },
  }).catch(console.error);

  return NextResponse.json({
    success: true,
    externalPostId: result.externalPostId,
    postUrl: result.postUrl,
  });
}
