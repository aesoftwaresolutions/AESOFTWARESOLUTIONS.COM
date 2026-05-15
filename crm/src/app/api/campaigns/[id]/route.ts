import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Campaign, AuditLog } from "@/lib/db/models";

export async function GET(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const campaign = await Campaign.findById(params.id).populate("createdBy", "name").lean();
  if (!campaign) return NextResponse.json({ error: "Not found" }, { status: 404 });

  return NextResponse.json(campaign);
}

export async function PATCH(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const body = await req.json();

  const campaign = await Campaign.findByIdAndUpdate(
    params.id,
    { $set: body },
    { new: true }
  );
  if (!campaign) return NextResponse.json({ error: "Not found" }, { status: 404 });

  AuditLog.create({
    action: "campaign.updated",
    resource: "campaign",
    resourceId: params.id,
    userId: session.user.id,
    userName: session.user?.name,
    details: { fields: Object.keys(body) },
  }).catch(console.error);

  return NextResponse.json(campaign);
}

export async function DELETE(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  await Campaign.findByIdAndDelete(params.id);

  AuditLog.create({
    action: "campaign.deleted",
    resource: "campaign",
    resourceId: params.id,
    userId: session.user.id,
    userName: session.user?.name,
  }).catch(console.error);

  return NextResponse.json({ success: true });
}
