import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { SocialPost, AuditLog } from "@/lib/db/models";

export async function GET(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const url = new URL(req.url);
  const page = parseInt(url.searchParams.get("page") || "1");
  const limit = parseInt(url.searchParams.get("limit") || "20");
  const platform = url.searchParams.get("platform");
  const status = url.searchParams.get("status");
  const campaignId = url.searchParams.get("campaignId");

  const filter: Record<string, unknown> = {};
  if (platform && platform !== "all") filter.platform = platform;
  if (status && status !== "all") filter.status = status;
  if (campaignId) filter.campaignId = campaignId;

  const [posts, total] = await Promise.all([
    SocialPost.find(filter)
      .sort({ scheduledAt: 1, createdAt: -1 })
      .skip((page - 1) * limit)
      .limit(limit)
      .populate("createdBy", "name")
      .populate("campaignId", "name")
      .lean(),
    SocialPost.countDocuments(filter),
  ]);

  return NextResponse.json({ posts, total, page, limit, pages: Math.ceil(total / limit) });
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const body = await req.json();
  const { platform, content, status, mediaUrls, hashtags, scheduledAt, campaignId, contactId } = body;

  if (!platform || !content) {
    return NextResponse.json({ error: "platform and content are required" }, { status: 400 });
  }

  const postStatus = scheduledAt && !status ? "scheduled" : (status || "draft");

  const post = await SocialPost.create({
    platform,
    content,
    status: postStatus,
    mediaUrls: mediaUrls || [],
    hashtags: hashtags || [],
    scheduledAt,
    campaignId,
    contactId,
    createdBy: session.user.id,
  });

  AuditLog.create({
    action: "social_post.created",
    resource: "social_post",
    resourceId: post._id.toString(),
    userId: session.user.id,
    userName: session.user?.name,
    details: { platform, status: postStatus },
  }).catch(console.error);

  return NextResponse.json(post, { status: 201 });
}
