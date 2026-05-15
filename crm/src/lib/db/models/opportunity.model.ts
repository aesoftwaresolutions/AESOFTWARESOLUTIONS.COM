import mongoose, { Schema, Document } from "mongoose";
import { PIPELINE_STAGES, SUBSCRIPTION_TIERS } from "@/lib/constants";

export interface IOpportunity extends Document {
  contactId: mongoose.Types.ObjectId;
  stage: string;
  tier?: string;
  value?: number;
  assignedTo?: mongoose.Types.ObjectId;
  notes?: string;
  closedAt?: Date;
  lostReason?: string;
  createdAt: Date;
  updatedAt: Date;
}

const OpportunitySchema = new Schema<IOpportunity>(
  {
    contactId: { type: Schema.Types.ObjectId, ref: "Contact", required: true },
    stage: {
      type: String,
      enum: PIPELINE_STAGES,
      default: PIPELINE_STAGES[0] || "new_lead",
    },
    tier: { type: String, enum: [...SUBSCRIPTION_TIERS, ""] },
    value: { type: Number, default: 0 },
    assignedTo: { type: Schema.Types.ObjectId, ref: "User" },
    notes: String,
    closedAt: Date,
    lostReason: String,
  },
  { timestamps: true }
);

OpportunitySchema.index({ contactId: 1 });
OpportunitySchema.index({ stage: 1 });
OpportunitySchema.index({ assignedTo: 1 });
OpportunitySchema.index({ stage: 1, assignedTo: 1 });
OpportunitySchema.index({ contactId: 1, stage: 1 });
OpportunitySchema.index({ createdAt: -1 });

export const Opportunity = mongoose.models.Opportunity || mongoose.model<IOpportunity>("Opportunity", OpportunitySchema);
