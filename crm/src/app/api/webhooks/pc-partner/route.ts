import { NextRequest, NextResponse } from "next/server";
import { connectDB } from "@/lib/db/connection";
import { Contact, Order, AuditLog } from "@/lib/db/models";
import { WebhookEvent } from "@/lib/db/models/webhook-event.model";

const STATUS_MAP: Record<string, string> = {
  intake_approved: "approved",
  intake_denied: "denied",
  labs_ordered: "labs_ordered",
  labs_received: "labs_received",
  rx_sent: "treatment_active",
  medication_shipped: "treatment_active",
};

export async function POST(req: NextRequest) {
  // Verify webhook secret — uses Authorization: Bearer per integration contract
  const authHeader = req.headers.get("authorization");
  const expectedSecret = process.env.PC_WEBHOOK_SECRET;
  if (expectedSecret && authHeader !== `Bearer ${expectedSecret}`) {
    return NextResponse.json({ error: "Invalid webhook secret" }, { status: 403 });
  }

  await connectDB();
  const body = await req.json();
  const { patientId, event, tracking, effectiveDate } = body;

  if (!patientId || !event) {
    return NextResponse.json({ error: "patientId and event are required" }, { status: 400 });
  }

  // Log to webhook_events collection for event sourcing
  const webhookEvent = await WebhookEvent.create({
    source: "pc_partner",
    eventType: event,
    payload: { patientId, tracking, effectiveDate },
    status: "processing",
  });

  try {
    // Update contact status (non-PHI only)
    const newStatus = STATUS_MAP[event];
    if (newStatus) {
      await Contact.findByIdAndUpdate(patientId, { $set: { status: newStatus } });
    }

    // If shipping event, create/update order
    if (event === "labs_ordered" || event === "medication_shipped") {
      await Order.create({
        contactId: patientId,
        type: event === "labs_ordered" ? "lab_kit" : "medication",
        status: tracking ? "shipped" : "ordered",
        trackingNumber: tracking,
      });
    }

    // Mark webhook as processed
    await WebhookEvent.findByIdAndUpdate(webhookEvent._id, {
      status: "processed",
      processedAt: new Date(),
    });

    // Audit log
    await AuditLog.create({
      action: `webhook.${event}`, resource: "webhook", resourceId: patientId,
      details: { event, tracking, effectiveDate, webhookEventId: webhookEvent._id.toString() },
    });

    return NextResponse.json({ received: true, event });
  } catch (err) {
    await WebhookEvent.findByIdAndUpdate(webhookEvent._id, {
      status: "failed",
      error: String(err),
    });
    throw err;
  }
}
