import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Opportunity, AuditLog } from "@/lib/db/models";

export async function GET(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const url = new URL(req.url);
  const stage = url.searchParams.get("stage");

  const filter: Record<string, unknown> = {};
  if (stage && stage !== "all") filter.stage = stage;

  const opportunities = await Opportunity.find(filter)
    .sort("-createdAt")
    .populate("contactId", "firstName lastName email status")
    .populate("assignedTo", "name")
    .lean();

  return NextResponse.json({ opportunities });
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const body = await req.json();
  const opp = await Opportunity.create({ ...body, assignedTo: body.assignedTo || session.user.id });

  AuditLog.create({
    action: "opportunity.created", resource: "opportunity", resourceId: opp._id.toString(),
    userId: session.user.id, userName: session.user?.name,
  }).catch(console.error);

  return NextResponse.json(opp, { status: 201 });
}

export async function PATCH(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const body = await req.json();
  const { id, ...update } = body;
  if (!id) return NextResponse.json({ error: "id required" }, { status: 400 });

  const opp = await Opportunity.findByIdAndUpdate(id, { $set: update }, { new: true });

  AuditLog.create({
    action: "opportunity.updated", resource: "opportunity", resourceId: id,
    userId: session.user.id, userName: session.user?.name,
    details: { fields: Object.keys(update) },
  }).catch(console.error);

  return NextResponse.json(opp);
}
