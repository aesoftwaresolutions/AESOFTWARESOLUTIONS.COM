import mongoose, { Schema, Document } from "mongoose";
import { ORDER_STATUS, ORDER_TYPES } from "@/lib/constants";

export interface IOrder extends Document {
  contactId: mongoose.Types.ObjectId;
  type: string;
  status: string;
  trackingNumber?: string;
  carrier?: string;
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const OrderSchema = new Schema<IOrder>(
  {
    contactId: { type: Schema.Types.ObjectId, ref: "Contact", required: true },
    type: { type: String, enum: ORDER_TYPES, required: true },
    status: { type: String, enum: ORDER_STATUS, default: "pending" },
    trackingNumber: String,
    carrier: String,
    notes: String,
  },
  { timestamps: true }
);

OrderSchema.index({ contactId: 1 });
OrderSchema.index({ status: 1 });
OrderSchema.index({ contactId: 1, status: 1 });
OrderSchema.index({ createdAt: -1 });

export const Order = mongoose.models.Order || mongoose.model<IOrder>("Order", OrderSchema);
