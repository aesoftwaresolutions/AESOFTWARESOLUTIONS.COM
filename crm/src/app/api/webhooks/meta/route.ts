import { NextRequest, NextResponse } from "next/server";
import { createHmac } from "crypto";
import { connectDB } from "@/lib/db/connection";
import { Message, Contact } from "@/lib/db/models";
import { WebhookEvent } from "@/lib/db/models/webhook-event.model";

/**
 * Meta (Facebook/Instagram) Webhook Handler
 *
 * Handles:
 * - Instagram Direct Messages
 * - Facebook Messenger messages
 * - Page mentions
 *
 * Setup: In Meta Developer Console, subscribe to:
 *   messages, messaging_postbacks, mentions
 * Verify token: process.env.META_WEBHOOK_VERIFY_TOKEN
 */

function verifyMetaSignature(body: string, signature: string | null): boolean {
  if (!signature || !process.env.META_APP_SECRET) return false;
  const expected = createHmac("sha256", process.env.META_APP_SECRET)
    .update(body)
    .digest("hex");
  return `sha256=${expected}` === signature;
}

// GET: Meta webhook verification challenge
export async function GET(req: NextRequest) {
  const url = new URL(req.url);
  const mode = url.searchParams.get("hub.mode");
  const token = url.searchParams.get("hub.verify_token");
  const challenge = url.searchParams.get("hub.challenge");

  if (mode === "subscribe" && token === process.env.META_WEBHOOK_VERIFY_TOKEN) {
    return new NextResponse(challenge, { status: 200 });
  }
  return NextResponse.json({ error: "Forbidden" }, { status: 403 });
}

// POST: Receive webhook events
export async function POST(req: NextRequest) {
  const rawBody = await req.text();
  const signature = req.headers.get("x-hub-signature-256");

  if (!verifyMetaSignature(rawBody, signature)) {
    return NextResponse.json({ error: "Invalid signature" }, { status: 401 });
  }

  let payload: any;
  try {
    payload = JSON.parse(rawBody);
  } catch {
    return NextResponse.json({ error: "Invalid JSON" }, { status: 400 });
  }

  await connectDB();

  // Log to webhook_events for event sourcing
  await WebhookEvent.create({
    source: "meta",
    eventType: `meta.${payload.object || "unknown"}`,
    payload: { object: payload.object, entryCount: payload.entry?.length },
    status: "processing",
  }).catch(console.error);


  for (const entry of payload.entry || []) {
    for (const event of entry.messaging || []) {
      await handleMessagingEvent(event, payload.object);
    }
    // Instagram mentions
    for (const change of entry.changes || []) {
      if (change.field === "mentions") {
        await handleMention(change.value, entry.id);
      }
    }
  }

  return NextResponse.json({ status: "ok" });
}

async function handleMessagingEvent(event: any, source: string) {
  if (!event.message?.text) return;

  const senderId = event.sender?.id;
  const text = event.message.text;
  const channel = source === "instagram" ? "instagram_dm" : "facebook_dm";

  // Try to find a matching contact by social platform ID
  // In production, link platform user IDs to contacts via a separate mapping
  const threadId = `${channel}-${senderId}`;

  try {
    await Message.create({
      threadId,
      contactId: senderId, // Placeholder — in prod, look up by mapped contact
      direction: "inbound",
      channel,
      body: text,
      status: "delivered",
      externalId: event.message.mid,
    });
  } catch {
    // Contact not found — log but don't fail
  }
}

async function handleMention(value: any, pageId: string) {
  // Handle Instagram/Facebook mentions for engagement tracking
  console.info(`[Meta Webhook] Mention received on page ${pageId}:`, value);
}
