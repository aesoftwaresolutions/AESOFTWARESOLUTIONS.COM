import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Contact, AuditLog } from "@/lib/db/models";

export async function GET(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const url = new URL(req.url);
  const page = parseInt(url.searchParams.get("page") || "1");
  const limit = parseInt(url.searchParams.get("limit") || "20");
  const status = url.searchParams.get("status");
  const search = url.searchParams.get("search");
  const state = url.searchParams.get("state");
  const sort = url.searchParams.get("sort") || "-createdAt";

  const filter: Record<string, unknown> = {};
  if (status && status !== "all") filter.status = status;
  if (state && state !== "all") filter.state = state;
  if (search) {
    filter.$or = [
      { firstName: { $regex: search, $options: "i" } },
      { lastName: { $regex: search, $options: "i" } },
      { email: { $regex: search, $options: "i" } },
      { phone: { $regex: search, $options: "i" } },
    ];
  }

  const [contacts, total] = await Promise.all([
    Contact.find(filter)
      .sort(sort)
      .skip((page - 1) * limit)
      .limit(limit)
      .populate("assignedTo", "name email")
      .lean(),
    Contact.countDocuments(filter),
  ]);

  return NextResponse.json({ contacts, total, page, limit, pages: Math.ceil(total / limit) });
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const body = await req.json();
  const { firstName, lastName, email, phone, state, status, source, tier, notes, tags } = body;

  if (!firstName || !lastName || !email) {
    return NextResponse.json({ error: "firstName, lastName, and email are required" }, { status: 400 });
  }

  // Duplicate check
  const existing = await Contact.findOne({ email: email.toLowerCase() });
  if (existing) {
    return NextResponse.json({ error: "A contact with this email already exists", existingId: existing._id }, { status: 409 });
  }

  const contact = await Contact.create({
    firstName, lastName, email: email.toLowerCase(), phone, state,
    status: status || "lead", source: source || "direct", tier, notes,
    tags: tags || [], assignedTo: session.user.id,
  });

  // Audit log
  AuditLog.create({
    action: "contact.created", resource: "contact", resourceId: contact._id.toString(),
    userId: session.user.id, userName: session.user.name,
    details: { firstName, lastName, email },
  }).catch(console.error);

  return NextResponse.json(contact, { status: 201 });
}
