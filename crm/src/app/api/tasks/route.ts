import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Task, AuditLog } from "@/lib/db/models";

export async function GET(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const url = new URL(req.url);
  const status = url.searchParams.get("status");
  const priority = url.searchParams.get("priority");

  const filter: Record<string, unknown> = {};
  if (status && status !== "all") filter.status = status;
  if (priority && priority !== "all") filter.priority = priority;

  const tasks = await Task.find(filter)
    .sort({ priority: -1, dueDate: 1 })
    .populate("assignedTo", "name")
    .populate("contactId", "firstName lastName")
    .lean();

  return NextResponse.json({ tasks });
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const body = await req.json();
  const task = await Task.create({ ...body, assignedTo: body.assignedTo || session.user.id });

  AuditLog.create({
    action: "task.created", resource: "task", resourceId: task._id.toString(),
    userId: session.user.id, userName: session.user?.name,
  }).catch(console.error);

  return NextResponse.json(task, { status: 201 });
}

export async function PATCH(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const { id, ...update } = await req.json();
  if (!id) return NextResponse.json({ error: "id required" }, { status: 400 });

  if (update.status === "done") update.completedAt = new Date();

  const task = await Task.findByIdAndUpdate(id, { $set: update }, { new: true });

  AuditLog.create({
    action: "task.updated", resource: "task", resourceId: id,
    userId: session.user.id, userName: session.user?.name,
    details: { fields: Object.keys(update) },
  }).catch(console.error);

  return NextResponse.json(task);
}

export async function DELETE(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const { id } = await req.json();
  if (!id) return NextResponse.json({ error: "id required" }, { status: 400 });

  await Task.findByIdAndDelete(id);

  AuditLog.create({
    action: "task.deleted", resource: "task", resourceId: id,
    userId: session.user.id, userName: session.user?.name,
  }).catch(console.error);

  return NextResponse.json({ success: true });
}
