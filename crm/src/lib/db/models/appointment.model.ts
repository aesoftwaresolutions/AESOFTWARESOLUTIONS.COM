import mongoose, { Schema, Document } from "mongoose";
import { APPOINTMENT_STATUS, APPOINTMENT_TYPES } from "@/lib/constants";

export interface IAppointment extends Document {
  contactId: mongoose.Types.ObjectId;
  type: string;
  status: string;
  scheduledAt: Date;
  duration: number;
  provider?: string;
  videoLink?: string;
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const AppointmentSchema = new Schema<IAppointment>(
  {
    contactId: { type: Schema.Types.ObjectId, ref: "Contact", required: true },
    type: { type: String, enum: APPOINTMENT_TYPES, default: APPOINTMENT_TYPES[0] },
    status: { type: String, enum: APPOINTMENT_STATUS, default: "scheduled" },
    scheduledAt: { type: Date, required: true },
    duration: { type: Number, default: 30 },
    provider: String,
    videoLink: String,
    notes: String,
  },
  { timestamps: true }
);

AppointmentSchema.index({ contactId: 1 });
AppointmentSchema.index({ scheduledAt: 1 });
AppointmentSchema.index({ status: 1 });
AppointmentSchema.index({ contactId: 1, scheduledAt: 1 });
AppointmentSchema.index({ status: 1, scheduledAt: 1 });

export const Appointment = mongoose.models.Appointment || mongoose.model<IAppointment>("Appointment", AppointmentSchema);
