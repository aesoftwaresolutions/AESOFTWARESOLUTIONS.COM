import mongoose, { Schema, type Document, type Types } from "mongoose";
import { CAMPAIGN_TYPES, CAMPAIGN_STATUSES } from "@/lib/constants";

export interface ICampaignMetrics {
  impressions: number;
  clicks: number;
  conversions: number;
  spend: number;
  costPerLead: number;
  revenue: number;
}

export interface ICampaign extends Document {
  name: string;
  type: (typeof CAMPAIGN_TYPES)[number];
  status: (typeof CAMPAIGN_STATUSES)[number];
  description?: string;
  budget?: number;
  startDate?: Date;
  endDate?: Date;
  utmSource?: string;
  utmMedium?: string;
  utmCampaign?: string;
  tags: string[];
  targetAudience?: string;
  createdBy?: Types.ObjectId;
  metrics: ICampaignMetrics;
  createdAt: Date;
  updatedAt: Date;
}

const CampaignSchema = new Schema<ICampaign>(
  {
    name: { type: String, required: true, trim: true },
    type: { type: String, required: true, enum: CAMPAIGN_TYPES },
    status: {
      type: String,
      required: true,
      enum: CAMPAIGN_STATUSES,
      default: "draft",
      index: true,
    },
    description: { type: String, trim: true },
    budget: { type: Number, min: 0 },
    startDate: { type: Date },
    endDate: { type: Date },
    utmSource: { type: String, trim: true },
    utmMedium: { type: String, trim: true },
    utmCampaign: { type: String, trim: true, index: true },
    tags: [{ type: String, trim: true }],
    targetAudience: { type: String, trim: true },
    createdBy: { type: Schema.Types.ObjectId, ref: "User" },
    metrics: {
      impressions: { type: Number, default: 0 },
      clicks: { type: Number, default: 0 },
      conversions: { type: Number, default: 0 },
      spend: { type: Number, default: 0 },
      costPerLead: { type: Number, default: 0 },
      revenue: { type: Number, default: 0 },
    },
  },
  { timestamps: true }
);

CampaignSchema.index({ type: 1, status: 1 });
CampaignSchema.index({ createdAt: -1 });

export const Campaign =
  (mongoose.models.Campaign as mongoose.Model<ICampaign>) ??
  mongoose.model<ICampaign>("Campaign", CampaignSchema);
