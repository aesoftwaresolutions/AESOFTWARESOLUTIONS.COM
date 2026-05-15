import mongoose, { Schema, type Document, type Types } from "mongoose";
import { MESSAGE_CHANNELS, MESSAGE_DIRECTIONS } from "@/lib/constants";

export interface IMessage extends Document {
  threadId: string;
  contactId: Types.ObjectId;
  direction: (typeof MESSAGE_DIRECTIONS)[number];
  channel: (typeof MESSAGE_CHANNELS)[number];
  subject?: string;
  body: string;
  sentBy?: Types.ObjectId;
  status: "queued" | "sent" | "delivered" | "failed" | "read";
  externalId?: string;
  createdAt: Date;
}

const MessageSchema = new Schema<IMessage>(
  {
    threadId: { type: String, required: true, index: true },
    contactId: {
      type: Schema.Types.ObjectId,
      ref: "Contact",
      required: true,
      index: true,
    },
    direction: { type: String, required: true, enum: MESSAGE_DIRECTIONS },
    channel: { type: String, required: true, enum: MESSAGE_CHANNELS },
    subject: { type: String, trim: true },
    body: { type: String, required: true },
    sentBy: { type: Schema.Types.ObjectId, ref: "User" },
    status: {
      type: String,
      required: true,
      enum: ["queued", "sent", "delivered", "failed", "read"],
      default: "queued",
    },
    externalId: { type: String, sparse: true },
  },
  { timestamps: { createdAt: true, updatedAt: false } }
);

MessageSchema.index({ contactId: 1, createdAt: -1 });
MessageSchema.index({ threadId: 1, createdAt: 1 });

export const Message =
  (mongoose.models.Message as mongoose.Model<IMessage>) ??
  mongoose.model<IMessage>("Message", MessageSchema);
