import mongoose, { Schema, type Document, type Types } from "mongoose";
import { AUTOMATION_TRIGGER_TYPES } from "@/lib/constants";

export interface IAutomationTrigger {
  type: (typeof AUTOMATION_TRIGGER_TYPES)[number];
  config: Record<string, unknown>;
}

export interface IAutomationCondition {
  field: string;
  operator: "eq" | "neq" | "gt" | "lt" | "gte" | "lte" | "contains" | "in";
  value: unknown;
}

export interface IAutomationAction {
  type:
    | "send_email"
    | "send_sms"
    | "create_task"
    | "update_contact"
    | "update_opportunity"
    | "assign_to"
    | "add_tag"
    | "webhook";
  config: Record<string, unknown>;
  /** Delay in minutes before executing this action */
  delayMinutes?: number;
}

export interface IAutomation extends Document {
  name: string;
  description?: string;
  trigger: IAutomationTrigger;
  conditions: IAutomationCondition[];
  actions: IAutomationAction[];
  status: "active" | "paused" | "draft";
  executionCount: number;
  lastExecutedAt?: Date;
  createdBy: Types.ObjectId;
  createdAt: Date;
  updatedAt: Date;
}

const AutomationSchema = new Schema<IAutomation>(
  {
    name: { type: String, required: true, trim: true },
    description: { type: String, trim: true },
    trigger: {
      type: { type: String, required: true, enum: AUTOMATION_TRIGGER_TYPES },
      config: { type: Schema.Types.Mixed, required: true },
    },
    conditions: [
      {
        field: { type: String, required: true },
        operator: {
          type: String,
          required: true,
          enum: ["eq", "neq", "gt", "lt", "gte", "lte", "contains", "in"],
        },
        value: { type: Schema.Types.Mixed, required: true },
        _id: false,
      },
    ],
    actions: [
      {
        type: {
          type: String,
          required: true,
          enum: [
            "send_email",
            "send_sms",
            "create_task",
            "update_contact",
            "update_opportunity",
            "assign_to",
            "add_tag",
            "webhook",
          ],
        },
        config: { type: Schema.Types.Mixed, required: true },
        delayMinutes: { type: Number, min: 0 },
        _id: false,
      },
    ],
    status: {
      type: String,
      required: true,
      enum: ["active", "paused", "draft"],
      default: "draft",
      index: true,
    },
    executionCount: { type: Number, default: 0 },
    lastExecutedAt: { type: Date },
    createdBy: {
      type: Schema.Types.ObjectId,
      ref: "User",
      required: true,
    },
  },
  { timestamps: true }
);

export const Automation =
  (mongoose.models.Automation as mongoose.Model<IAutomation>) ??
  mongoose.model<IAutomation>("Automation", AutomationSchema);
