import mongoose, { Schema, Document } from "mongoose";
import {
  CONTACT_STATUS,
  CONTACT_SOURCES,
  SUBSCRIPTION_TIERS,
  STATES,
  LEAD_TEMPERATURES,
} from "@/lib/constants";

export interface IContact extends Document {
  firstName: string;
  lastName: string;
  email: string;
  phone?: string;
  state?: string;
  status: string;
  source?: string;
  tier?: string;
  assignedTo?: mongoose.Types.ObjectId;
  tags: string[];
  notes?: string;
  notesList?: Array<{ _id: mongoose.Types.ObjectId; body: string; authorName: string; createdAt: Date }>;
  utmSource?: string;
  utmMedium?: string;
  utmCampaign?: string;
  // Generic CRM assessment fields
  leadScore?: number;
  leadTemperature?: string;
  recommendedTier?: string;
  /** Dynamic custom fields from tenant config — stored as key-value pairs */
  customData?: Record<string, unknown>;
  createdAt: Date;
  updatedAt: Date;
}

const NoteSchema = new Schema(
  {
    body: { type: String, required: true },
    authorName: { type: String, required: true },
  },
  { timestamps: { createdAt: true, updatedAt: false } }
);

const ContactSchema = new Schema<IContact>(
  {
    firstName: { type: String, required: true, trim: true },
    lastName: { type: String, required: true, trim: true },
    email: { type: String, required: true, unique: true, lowercase: true, trim: true },
    phone: { type: String, trim: true },
    state: { type: String, enum: [...STATES, ""] },
    status: {
      type: String,
      enum: CONTACT_STATUS,
      default: CONTACT_STATUS[0] || "lead",
    },
    source: { type: String, enum: [...CONTACT_SOURCES, ""], default: CONTACT_SOURCES[0] || "direct" },
    tier: { type: String, enum: [...SUBSCRIPTION_TIERS, ""] },
    assignedTo: { type: Schema.Types.ObjectId, ref: "User" },
    tags: [{ type: String }],
    notes: String,
    notesList: { type: [NoteSchema], default: [] },
    utmSource: String,
    utmMedium: String,
    utmCampaign: String,
    leadScore: { type: Number, min: 0, max: 100 },
    leadTemperature: { type: String, enum: [...LEAD_TEMPERATURES, ""] },
    recommendedTier: { type: String, enum: [...SUBSCRIPTION_TIERS, ""] },
    customData: { type: Schema.Types.Mixed, default: {} },
  },
  { timestamps: true }
);

ContactSchema.index({ status: 1 });
ContactSchema.index({ assignedTo: 1 });
ContactSchema.index({ createdAt: -1 });
ContactSchema.index({ status: 1, assignedTo: 1 });
ContactSchema.index({ status: 1, createdAt: -1 });
ContactSchema.index({ tags: 1 });

export const Contact = mongoose.models.Contact || mongoose.model<IContact>("Contact", ContactSchema);
