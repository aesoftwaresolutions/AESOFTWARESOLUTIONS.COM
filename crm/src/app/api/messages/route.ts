import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Message, Contact } from "@/lib/db/models";
import mongoose from "mongoose";

export async function GET(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const url = new URL(req.url);
  const contactId = url.searchParams.get("contactId");
  const threadId = url.searchParams.get("threadId");
  const channel = url.searchParams.get("channel");
  const mode = url.searchParams.get("mode") || "threads"; // threads | messages

  if (mode === "messages" && (contactId || threadId)) {
    // Fetch messages for a specific thread
    const filter: Record<string, unknown> = {};
    if (contactId) filter.contactId = contactId;
    if (threadId) filter.threadId = threadId;
    if (channel && channel !== "all") filter.channel = channel;

    const messages = await Message.find(filter)
      .sort({ createdAt: 1 })
      .populate("sentBy", "name")
      .lean();

    return NextResponse.json({ messages });
  }

  // Threads view: get the latest message per contact, with unread counts
  const threads = await Message.aggregate([
    // Group by contactId to get last message per contact
    {
      $sort: { createdAt: -1 },
    },
    {
      $group: {
        _id: "$contactId",
        lastMessage: { $first: "$$ROOT" },
        totalMessages: { $sum: 1 },
        unreadCount: {
          $sum: {
            $cond: [
              { $and: [{ $eq: ["$direction", "inbound"] }, { $ne: ["$status", "read"] }] },
              1,
              0,
            ],
          },
        },
      },
    },
    { $sort: { "lastMessage.createdAt": -1 } },
    { $limit: 50 },
    {
      $lookup: {
        from: "contacts",
        localField: "_id",
        foreignField: "_id",
        as: "contact",
      },
    },
    { $unwind: { path: "$contact", preserveNullAndEmptyArrays: true } },
  ]);

  return NextResponse.json({ threads });
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const body = await req.json();
  const { contactId, channel, subject, body: messageBody, threadId } = body;

  if (!contactId || !channel || !messageBody) {
    return NextResponse.json({ error: "contactId, channel, and body are required" }, { status: 400 });
  }

  const resolvedThreadId = threadId || `${contactId}-${channel}`;

  const message = await Message.create({
    threadId: resolvedThreadId,
    contactId,
    direction: "outbound",
    channel,
    subject,
    body: messageBody,
    sentBy: session.user.id,
    status: "queued",
  });

  // In a real integration, dispatch to Twilio/SendGrid here based on channel.
  // For now, mark as sent optimistically.
  await Message.findByIdAndUpdate(message._id, { status: "sent" });

  return NextResponse.json({ ...message.toObject(), status: "sent" }, { status: 201 });
}

export async function PATCH(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const { id, ...update } = await req.json();
  if (!id) return NextResponse.json({ error: "id required" }, { status: 400 });

  const message = await Message.findByIdAndUpdate(id, { $set: update }, { new: true });
  return NextResponse.json(message);
}
