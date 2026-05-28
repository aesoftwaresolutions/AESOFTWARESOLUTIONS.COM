import { NextRequest, NextResponse } from "next/server";
import { createHmac } from "crypto";
import { connectDB } from "@/lib/db/connection";
import { Message } from "@/lib/db/models";
import { WebhookEvent } from "@/lib/db/models/webhook-event.model";

/**
 * Twitter / X Webhook Handler (Account Activity API)
 *
 * Handles:
 * - Direct messages
 * - Mentions
 *
 * CRC challenge: GET with crc_token query param
 * Events: POST with X-Twitter-Webhooks-Signature header
 */

function verifyTwitterSignature(body: string, signature: string | null): boolean {
  if (!signature || !process.env.TWITTER_CONSUMER_SECRET) return false;
  const expected = createHmac("sha256", process.env.TWITTER_CONSUMER_SECRET)
    .update(body)
    .digest("base64");
  return `sha256=${expected}` === signature;
}

// GET: CRC challenge response
export async function GET(req: NextRequest) {
  const url = new URL(req.url);
  const crcToken = url.searchParams.get("crc_token");

  if (!crcToken || !process.env.TWITTER_CONSUMER_SECRET) {
    return NextResponse.json({ error: "Missing crc_token or secret" }, { status: 400 });
  }

  const responseToken = createHmac("sha256", process.env.TWITTER_CONSUMER_SECRET)
    .update(crcToken)
    .digest("base64");

  return NextResponse.json({ response_token: `sha256=${responseToken}` });
}

// POST: Receive activity events
export async function POST(req: NextRequest) {
  const rawBody = await req.text();
  const signature = req.headers.get("x-twitter-webhooks-signature");

  if (!verifyTwitterSignature(rawBody, signature)) {
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
    source: "twitter",
    eventType: "twitter.activity",
    payload: {
      hasDMs: !!payload.direct_message_events?.length,
      hasTweets: !!payload.tweet_create_events?.length,
    },
    status: "processing",
  }).catch(console.error);

  // Handle direct messages
  for (const dm of payload.direct_message_events || []) {
    if (dm.type === "message_create") {
      const senderId = dm.message_create?.sender_id;
      const text = dm.message_create?.message_data?.text;
      if (!senderId || !text) continue;

      const threadId = `twitter_dm-${senderId}`;
      try {
        await Message.create({
          threadId,
          contactId: senderId,
          direction: "inbound",
          channel: "twitter_dm",
          body: text,
          status: "delivered",
          externalId: dm.id,
        });
      } catch {
        // Contact not mapped — ignored
      }
    }
  }

  // Handle tweet mentions
  for (const tweet of payload.tweet_create_events || []) {
    if (payload.for_user_id && tweet.user?.id_str !== payload.for_user_id) {
      // Mention of our account
      console.info("[Twitter Webhook] Mention:", tweet.text);
    }
  }

  return NextResponse.json({ status: "ok" });
}
