import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { SocialPost, AuditLog } from "@/lib/db/models";

export async function GET(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const post = await SocialPost.findById(params.id)
    .populate("createdBy", "name")
    .populate("campaignId", "name")
    .lean();
  if (!post) return NextResponse.json({ error: "Not found" }, { status: 404 });

  return NextResponse.json(post);
}

export async function PATCH(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const body = await req.json();

  if (body.status === "published" && !body.publishedAt) {
    body.publishedAt = new Date();
  }

  const post = await SocialPost.findByIdAndUpdate(
    params.id,
    { $set: body },
    { new: true }
  );
  if (!post) return NextResponse.json({ error: "Not found" }, { status: 404 });

  AuditLog.create({
    action: "social_post.updated",
    resource: "social_post",
    resourceId: params.id,
    userId: session.user.id,
    userName: session.user?.name,
    details: { fields: Object.keys(body) },
  }).catch(console.error);

  return NextResponse.json(post);
}

export async function DELETE(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  await SocialPost.findByIdAndDelete(params.id);

  AuditLog.create({
    action: "social_post.deleted",
    resource: "social_post",
    resourceId: params.id,
    userId: session.user.id,
    userName: session.user?.name,
  }).catch(console.error);

  return NextResponse.json({ success: true });
}
