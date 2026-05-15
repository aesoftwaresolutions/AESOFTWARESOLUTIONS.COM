import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Appointment, AuditLog } from "@/lib/db/models";

export async function GET(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const url = new URL(req.url);
  const status = url.searchParams.get("status");

  const filter: Record<string, unknown> = {};
  if (status && status !== "all") filter.status = status;

  const appointments = await Appointment.find(filter)
    .sort("scheduledAt")
    .populate("contactId", "firstName lastName email phone")
    .lean();

  return NextResponse.json({ appointments });
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const body = await req.json();
  const appt = await Appointment.create(body);

  AuditLog.create({
    action: "appointment.created", resource: "appointment", resourceId: appt._id.toString(),
    userId: session.user.id, userName: session.user?.name,
  }).catch(console.error);

  return NextResponse.json(appt, { status: 201 });
}

export async function PATCH(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const { id, ...update } = await req.json();
  if (!id) return NextResponse.json({ error: "id required" }, { status: 400 });

  const appt = await Appointment.findByIdAndUpdate(id, { $set: update }, { new: true });
  return NextResponse.json(appt);
}
