import mongoose, { Schema, type Document, type Types } from "mongoose";
import { SOCIAL_PLATFORMS, SOCIAL_POST_STATUSES } from "@/lib/constants";

export interface ISocialPostMetrics {
  likes: number;
  shares: number;
  comments: number;
  reach: number;
  impressions: number;
  clicks: number;
}

export interface ISocialPost extends Document {
  platform: (typeof SOCIAL_PLATFORMS)[number];
  status: (typeof SOCIAL_POST_STATUSES)[number];
  content: string;
  mediaUrls: string[];
  hashtags: string[];
  scheduledAt?: Date;
  publishedAt?: Date;
  externalPostId?: string;
  contactId?: Types.ObjectId;
  campaignId?: Types.ObjectId;
  metrics: ISocialPostMetrics;
  createdBy?: Types.ObjectId;
  createdAt: Date;
  updatedAt: Date;
}

const SocialPostSchema = new Schema<ISocialPost>(
  {
    platform: { type: String, required: true, enum: SOCIAL_PLATFORMS, index: true },
    status: {
      type: String,
      required: true,
      enum: SOCIAL_POST_STATUSES,
      default: "draft",
      index: true,
    },
    content: { type: String, required: true },
    mediaUrls: [{ type: String }],
    hashtags: [{ type: String, trim: true }],
    scheduledAt: { type: Date, index: true },
    publishedAt: { type: Date },
    externalPostId: { type: String, sparse: true },
    contactId: { type: Schema.Types.ObjectId, ref: "Contact" },
    campaignId: { type: Schema.Types.ObjectId, ref: "Campaign" },
    metrics: {
      likes: { type: Number, default: 0 },
      shares: { type: Number, default: 0 },
      comments: { type: Number, default: 0 },
      reach: { type: Number, default: 0 },
      impressions: { type: Number, default: 0 },
      clicks: { type: Number, default: 0 },
    },
    createdBy: { type: Schema.Types.ObjectId, ref: "User" },
  },
  { timestamps: true }
);

SocialPostSchema.index({ platform: 1, status: 1 });
SocialPostSchema.index({ scheduledAt: 1, status: 1 });
SocialPostSchema.index({ campaignId: 1 });

export const SocialPost =
  (mongoose.models.SocialPost as mongoose.Model<ISocialPost>) ??
  mongoose.model<ISocialPost>("SocialPost", SocialPostSchema);
