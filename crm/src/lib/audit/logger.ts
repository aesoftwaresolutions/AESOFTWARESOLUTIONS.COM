import { connectDB } from "@/lib/db/connection";
import { AuditLog, type IAuditChange } from "@/lib/db/models/audit-log.model";

interface AuditEntry {
  userId: string;
  action: string;
  resource: string;
  resourceId?: string;
  changes?: IAuditChange[];
  ipAddress?: string;
  userAgent?: string;
  metadata?: Record<string, unknown>;
}

/**
 * Write an audit log entry.
 * Fire-and-forget — audit logging must never block the main request.
 * Errors are caught and logged to console (never thrown to the caller).
 */
export async function auditLog(entry: AuditEntry): Promise<void> {
  try {
    await connectDB();
    await AuditLog.create({
      userId: entry.userId,
      action: entry.action,
      resource: entry.resource,
      resourceId: entry.resourceId,
      changes: entry.changes,
      ipAddress: entry.ipAddress,
      userAgent: entry.userAgent,
      metadata: entry.metadata,
    });
  } catch (err) {
    console.error("[AUDIT] Failed to write audit log:", err);
  }
}

/**
 * Compute changes between two objects for audit logging.
 * Only includes fields that actually changed.
 */
export function computeChanges(
  before: Record<string, unknown>,
  after: Record<string, unknown>,
  trackFields: string[]
): IAuditChange[] {
  const changes: IAuditChange[] = [];
  for (const field of trackFields) {
    const fromVal = before[field];
    const toVal = after[field];
    if (JSON.stringify(fromVal) !== JSON.stringify(toVal)) {
      changes.push({ field, from: fromVal, to: toVal });
    }
  }
  return changes;
}

/**
 * Build audit metadata from a Next.js request.
 */
export function extractRequestMeta(headers: Headers) {
  return {
    ipAddress:
      headers.get("x-forwarded-for")?.split(",")[0]?.trim() ??
      headers.get("x-real-ip") ??
      "unknown",
    userAgent: headers.get("user-agent") ?? "unknown",
  };
}
