import mongoose, { Schema, type Document } from "mongoose";
import { WEBHOOK_SOURCES } from "@/lib/constants";

export interface IWebhookEvent extends Document {
  source: (typeof WEBHOOK_SOURCES)[number];
  eventType: string;
  /** Must NOT contain PHI per integration contract */
  payload: Record<string, unknown>;
  status: "received" | "processing" | "processed" | "failed";
  processedAt?: Date;
  error?: string;
  retryCount: number;
  createdAt: Date;
}

const WebhookEventSchema = new Schema<IWebhookEvent>(
  {
    source: { type: String, required: true, enum: WEBHOOK_SOURCES, index: true },
    eventType: { type: String, required: true, index: true },
    payload: { type: Schema.Types.Mixed, required: true },
    status: {
      type: String,
      required: true,
      enum: ["received", "processing", "processed", "failed"],
      default: "received",
      index: true,
    },
    processedAt: { type: Date },
    error: { type: String },
    retryCount: { type: Number, default: 0 },
  },
  {
    timestamps: { createdAt: true, updatedAt: false },
  }
);

// 30-day TTL for webhook events
WebhookEventSchema.index({ createdAt: 1 }, { expireAfterSeconds: 30 * 24 * 3600 });
WebhookEventSchema.index({ source: 1, eventType: 1, createdAt: -1 });

export const WebhookEvent =
  (mongoose.models.WebhookEvent as mongoose.Model<IWebhookEvent>) ??
  mongoose.model<IWebhookEvent>("WebhookEvent", WebhookEventSchema);
