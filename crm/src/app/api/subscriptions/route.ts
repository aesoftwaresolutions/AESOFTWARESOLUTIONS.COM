import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Subscription, AuditLog } from "@/lib/db/models";

export async function GET(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  await connectDB();

  const url = new URL(req.url);
  const status = url.searchParams.get("status");
  const tier = url.searchParams.get("tier");
  const filter: Record<string, unknown> = {};
  if (status && status !== "all") filter.status = status;
  if (tier && tier !== "all") filter.tier = tier;

  const subscriptions = await Subscription.find(filter).sort("-createdAt").populate("contactId", "firstName lastName email").lean();

  // Calculate MRR
  const activeSubs = await Subscription.find({ status: "active" }).lean();
  const mrr = activeSubs.reduce((sum, s) => sum + s.priceInCents, 0);

  return NextResponse.json({ subscriptions, mrr });
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  await connectDB();
  const body = await req.json();
  const sub = await Subscription.create(body);
  AuditLog.create({ action: "subscription.created", resource: "subscription", resourceId: sub._id.toString(), userId: session.user.id, userName: session.user?.name }).catch(console.error);
  return NextResponse.json(sub, { status: 201 });
}

export async function PATCH(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  await connectDB();
  const { id, ...update } = await req.json();
  if (!id) return NextResponse.json({ error: "id required" }, { status: 400 });
  if (update.status === "cancelled") update.cancelledAt = new Date();
  const sub = await Subscription.findByIdAndUpdate(id, { $set: update }, { new: true });
  AuditLog.create({ action: "subscription.updated", resource: "subscription", resourceId: id, userId: session.user.id, userName: session.user?.name, details: update }).catch(console.error);
  return NextResponse.json(sub);
}
