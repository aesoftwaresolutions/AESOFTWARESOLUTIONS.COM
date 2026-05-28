import mongoose, { Schema, type Document } from "mongoose";

export interface IPricingTier extends Document {
  name: string;
  description: string;
  priceMonthly: number;
  features: string[];
  published: boolean;
  sortOrder: number;
  createdAt: Date;
  updatedAt: Date;
}

const PricingTierSchema = new Schema<IPricingTier>(
  {
    name: { type: String, required: true },
    description: { type: String, default: "" },
    priceMonthly: { type: Number, required: true },
    features: [{ type: String }],
    published: { type: Boolean, default: false },
    sortOrder: { type: Number, default: 0 },
  },
  { timestamps: true }
);

export const PricingTier =
  mongoose.models.PricingTier ||
  mongoose.model<IPricingTier>("PricingTier", PricingTierSchema);
