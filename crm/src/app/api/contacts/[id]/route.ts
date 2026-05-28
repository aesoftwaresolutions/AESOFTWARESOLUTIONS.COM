import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Contact, Opportunity, Appointment, Task, Order, Subscription, AuditLog } from "@/lib/db/models";

export async function GET(_req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const contact = await Contact.findById(params.id).populate("assignedTo", "name email").lean();
  if (!contact) return NextResponse.json({ error: "Not found" }, { status: 404 });

  const [opportunities, appointments, tasks, orders, subscriptions] = await Promise.all([
    Opportunity.find({ contactId: params.id }).sort("-createdAt").lean(),
    Appointment.find({ contactId: params.id }).sort("-scheduledAt").lean(),
    Task.find({ contactId: params.id }).sort("-createdAt").lean(),
    Order.find({ contactId: params.id }).sort("-createdAt").lean(),
    Subscription.find({ contactId: params.id }).sort("-createdAt").lean(),
  ]);

  return NextResponse.json({ contact, opportunities, appointments, tasks, orders, subscriptions });
}

export async function PATCH(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const body = await req.json();
  const contact = await Contact.findByIdAndUpdate(params.id, { $set: body }, { new: true });
  if (!contact) return NextResponse.json({ error: "Not found" }, { status: 404 });

  AuditLog.create({
    action: "contact.updated", resource: "contact", resourceId: params.id,
    userId: session.user.id, userName: session.user.name,
    details: { fields: Object.keys(body) },
  }).catch(console.error);

  return NextResponse.json(contact);
}

export async function DELETE(_req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  // Soft delete
  const contact = await Contact.findByIdAndUpdate(params.id, { $set: { status: "cancelled" } }, { new: true });
  if (!contact) return NextResponse.json({ error: "Not found" }, { status: 404 });

  AuditLog.create({
    action: "contact.deleted", resource: "contact", resourceId: params.id,
    userId: session.user.id, userName: session.user.name,
  }).catch(console.error);

  return NextResponse.json({ success: true });
}
