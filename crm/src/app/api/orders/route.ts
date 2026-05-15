import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Order, AuditLog } from "@/lib/db/models";

export async function GET(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  await connectDB();

  const url = new URL(req.url);
  const status = url.searchParams.get("status");
  const type = url.searchParams.get("type");
  const filter: Record<string, unknown> = {};
  if (status && status !== "all") filter.status = status;
  if (type && type !== "all") filter.type = type;

  const orders = await Order.find(filter).sort("-createdAt").populate("contactId", "firstName lastName email").lean();
  return NextResponse.json({ orders });
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  await connectDB();
  const body = await req.json();
  const order = await Order.create(body);
  AuditLog.create({ action: "order.created", resource: "order", resourceId: order._id.toString(), userId: session.user.id, userName: session.user?.name }).catch(console.error);
  return NextResponse.json(order, { status: 201 });
}

export async function PATCH(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  await connectDB();
  const { id, ...update } = await req.json();
  if (!id) return NextResponse.json({ error: "id required" }, { status: 400 });
  const order = await Order.findByIdAndUpdate(id, { $set: update }, { new: true });
  return NextResponse.json(order);
}
