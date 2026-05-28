import mongoose, { Schema, Document } from "mongoose";

export interface IAuditChange {
  field: string;
  from: unknown;
  to: unknown;
}

export interface IAuditLog extends Document {
  action: string;
  resource: string;
  resourceId?: string;
  userId?: string;
  userName?: string;
  changes?: IAuditChange[];
  ipAddress?: string;
  userAgent?: string;
  metadata?: Record<string, unknown>;
  /** @deprecated Use metadata instead. Kept for backward compat with existing docs. */
  details?: Record<string, unknown>;
  createdAt: Date;
}

const AuditLogSchema = new Schema<IAuditLog>(
  {
    action: { type: String, required: true, index: true },
    resource: { type: String, required: true, index: true },
    resourceId: { type: String, index: true },
    userId: { type: String, index: true },
    userName: String,
    changes: [
      {
        field: { type: String, required: true },
        from: Schema.Types.Mixed,
        to: Schema.Types.Mixed,
        _id: false,
      },
    ],
    ipAddress: String,
    userAgent: String,
    metadata: Schema.Types.Mixed,
    details: Schema.Types.Mixed,
  },
  { timestamps: true }
);

AuditLogSchema.index({ createdAt: -1 });
AuditLogSchema.index({ resource: 1, resourceId: 1 });
AuditLogSchema.index({ userId: 1, createdAt: -1 });

export const AuditLog = mongoose.models.AuditLog || mongoose.model<IAuditLog>("AuditLog", AuditLogSchema);
