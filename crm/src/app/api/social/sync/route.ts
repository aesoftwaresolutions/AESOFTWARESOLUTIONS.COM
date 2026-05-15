import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { SocialPost, SocialAccount } from "@/lib/db/models";
import { getAdapter, decryptToken } from "@/lib/social";

/**
 * POST /api/social/sync
 * Pulls latest engagement metrics from each platform for all published posts.
 * Can be called manually or via a Vercel cron job.
 */
export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const publishedPosts = await SocialPost.find({
    status: "published",
    externalPostId: { $exists: true, $ne: "" },
  }).lean();

  if (!publishedPosts.length) {
    return NextResponse.json({ synced: 0, message: "No published posts to sync" });
  }

  // Pre-fetch all active accounts in a single query to avoid N+1
  const activeAccounts = await SocialAccount.find({ isActive: true }).lean();
  const accountByPlatform = new Map<string, typeof activeAccounts[0]>();
  for (const account of activeAccounts) {
    // Use the first active account per platform
    if (!accountByPlatform.has(account.platform)) {
      accountByPlatform.set(account.platform, account);
    }
  }

  const results = { synced: 0, failed: 0, errors: [] as string[] };

  // Batch update operations for SocialPost metrics
  const postUpdates: Array<{
    updateOne: { filter: Record<string, unknown>; update: Record<string, unknown> };
  }> = [];
  const accountSyncDates = new Set<string>();

  for (const post of publishedPosts) {
    try {
      const account = accountByPlatform.get(post.platform);
      if (!account) continue;

      const adapter = getAdapter(post.platform);
      const token = decryptToken(account.accessToken);
      const metrics = await adapter.getMetrics(token, post.externalPostId!);

      postUpdates.push({
        updateOne: {
          filter: { _id: post._id },
          update: { $set: { metrics, lastSyncedAt: new Date() } },
        },
      });

      accountSyncDates.add(account._id.toString());
      results.synced++;
    } catch (err: any) {
      results.failed++;
      results.errors.push(`${post.platform} post ${post._id}: ${err.message}`);
    }
  }

  // Batch write post metrics updates
  if (postUpdates.length > 0) {
    await SocialPost.bulkWrite(postUpdates);
  }

  // Batch update account sync dates
  if (accountSyncDates.size > 0) {
    const accountIds = Array.from(accountSyncDates);
    await SocialAccount.updateMany(
      { _id: { $in: accountIds } },
      { $set: { lastSyncedAt: new Date() } }
    );
  }

  return NextResponse.json(results);
}

// Allow cron job to trigger sync
export async function GET(req: NextRequest) {
  const cronSecret = req.headers.get("authorization");
  if (cronSecret !== `Bearer ${process.env.CRON_SECRET}`) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }
  return POST(req);
}
