import mongoose, { Schema, Document } from "mongoose";
import { TASK_STATUS, TASK_PRIORITY } from "@/lib/constants";

export interface ITask extends Document {
  title: string;
  description?: string;
  status: string;
  priority: string;
  assignedTo?: mongoose.Types.ObjectId;
  contactId?: mongoose.Types.ObjectId;
  dueDate?: Date;
  completedAt?: Date;
  createdAt: Date;
  updatedAt: Date;
}

const TaskSchema = new Schema<ITask>(
  {
    title: { type: String, required: true },
    description: String,
    status: { type: String, enum: TASK_STATUS, default: "todo" },
    priority: { type: String, enum: TASK_PRIORITY, default: "medium" },
    assignedTo: { type: Schema.Types.ObjectId, ref: "User" },
    contactId: { type: Schema.Types.ObjectId, ref: "Contact" },
    dueDate: Date,
    completedAt: Date,
  },
  { timestamps: true }
);

TaskSchema.index({ assignedTo: 1 });
TaskSchema.index({ contactId: 1 });
TaskSchema.index({ status: 1 });
TaskSchema.index({ dueDate: 1 });
TaskSchema.index({ assignedTo: 1, status: 1 });
TaskSchema.index({ status: 1, dueDate: 1 });

export const Task = mongoose.models.Task || mongoose.model<ITask>("Task", TaskSchema);
