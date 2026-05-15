import mongoose, { Schema, Document } from "mongoose";
import { SUBSCRIPTION_TIERS } from "@/lib/constants";

export interface ISubscription extends Document {
  contactId: mongoose.Types.ObjectId;
  tier: string;
  status: string;
  priceInCents: number;
  stripeSubscriptionId?: string;
  stripeCustomerId?: string;
  currentPeriodStart?: Date;
  currentPeriodEnd?: Date;
  cancelledAt?: Date;
  pausedUntil?: Date;
  createdAt: Date;
  updatedAt: Date;
}

const SubscriptionSchema = new Schema<ISubscription>(
  {
    contactId: { type: Schema.Types.ObjectId, ref: "Contact", required: true },
    tier: { type: String, enum: SUBSCRIPTION_TIERS, required: true },
    status: { type: String, enum: ["active", "paused", "cancelled", "past_due", "trialing"], default: "active" },
    priceInCents: { type: Number, required: true },
    stripeSubscriptionId: String,
    stripeCustomerId: String,
    currentPeriodStart: Date,
    currentPeriodEnd: Date,
    cancelledAt: Date,
    pausedUntil: Date,
  },
  { timestamps: true }
);

SubscriptionSchema.index({ contactId: 1 });
SubscriptionSchema.index({ status: 1 });
SubscriptionSchema.index({ contactId: 1, status: 1 });
SubscriptionSchema.index({ stripeSubscriptionId: 1 }, { sparse: true, unique: true });
SubscriptionSchema.index({ stripeCustomerId: 1 }, { sparse: true });
SubscriptionSchema.index({ currentPeriodEnd: 1 });

export const Subscription = mongoose.models.Subscription || mongoose.model<ISubscription>("Subscription", SubscriptionSchema);
