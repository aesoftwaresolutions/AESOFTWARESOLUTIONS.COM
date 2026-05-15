import mongoose, { Schema, type Document, type Types } from "mongoose";
import { SOCIAL_PLATFORMS } from "@/lib/constants";

export interface ISocialAccount extends Document {
  platform: (typeof SOCIAL_PLATFORMS)[number];
  accountId: string;       // platform's user/page ID
  accountName: string;     // display name
  accountHandle?: string;  // @handle
  profileUrl?: string;
  avatarUrl?: string;
  followerCount?: number;
  // Tokens are AES-256-GCM encrypted at rest (same pattern as PHI transfer)
  accessToken: string;
  refreshToken?: string;
  tokenExpiry?: Date;
  // Webhook subscription ID (for platforms that need it)
  webhookSubscriptionId?: string;
  // Scopes granted
  scopes: string[];
  isActive: boolean;
  connectedBy: Types.ObjectId;
  lastSyncedAt?: Date;
  createdAt: Date;
  updatedAt: Date;
}

const SocialAccountSchema = new Schema<ISocialAccount>(
  {
    platform: { type: String, required: true, enum: SOCIAL_PLATFORMS, index: true },
    accountId: { type: String, required: true },
    accountName: { type: String, required: true, trim: true },
    accountHandle: { type: String, trim: true },
    profileUrl: { type: String },
    avatarUrl: { type: String },
    followerCount: { type: Number, default: 0 },
    accessToken: { type: String, required: true },
    refreshToken: { type: String },
    tokenExpiry: { type: Date },
    webhookSubscriptionId: { type: String },
    scopes: [{ type: String }],
    isActive: { type: Boolean, default: true, index: true },
    connectedBy: { type: Schema.Types.ObjectId, ref: "User", required: true },
    lastSyncedAt: { type: Date },
  },
  { timestamps: true }
);

// Prevent duplicate platform+accountId pairs
SocialAccountSchema.index({ platform: 1, accountId: 1 }, { unique: true });

export const SocialAccount =
  (mongoose.models.SocialAccount as mongoose.Model<ISocialAccount>) ??
  mongoose.model<ISocialAccount>("SocialAccount", SocialAccountSchema);
