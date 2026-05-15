import type { Role } from "@/lib/constants";

// ── Resource Types ──
export type Resource =
  | "contacts"
  | "opportunities"
  | "appointments"
  | "campaigns"
  | "tasks"
  | "messages"
  | "orders"
  | "subscriptions"
  | "automations"
  | "reports"
  | "phi_transfers"
  | "audit_logs"
  | "users"
  | "settings";

// ── Operations ──
export type Operation = "create" | "read" | "update" | "delete";

// ── Scope: "all" = unrestricted, "own" = only assigned records, "team" = manager's direct reports ──
export type Scope = "all" | "own" | "team" | "none";

interface PermissionRule {
  create: Scope;
  read: Scope;
  update: Scope;
  delete: Scope;
}

/**
 * RBAC Permission Matrix
 *
 * Defines what each role can do for each resource.
 * "own" means the user can only operate on records where assignedTo matches their userId.
 * "team" means the user can operate on records assigned to them or their team members.
 */
const PERMISSION_MATRIX: Record<Role, Record<Resource, PermissionRule>> = {
  superadmin: {
    contacts: { create: "all", read: "all", update: "all", delete: "all" },
    opportunities: { create: "all", read: "all", update: "all", delete: "all" },
    appointments: { create: "all", read: "all", update: "all", delete: "all" },
    campaigns: { create: "all", read: "all", update: "all", delete: "all" },
    tasks: { create: "all", read: "all", update: "all", delete: "all" },
    messages: { create: "all", read: "all", update: "all", delete: "all" },
    orders: { create: "all", read: "all", update: "all", delete: "all" },
    subscriptions: { create: "all", read: "all", update: "all", delete: "all" },
    automations: { create: "all", read: "all", update: "all", delete: "all" },
    reports: { create: "all", read: "all", update: "all", delete: "all" },
    phi_transfers: { create: "all", read: "all", update: "all", delete: "all" },
    audit_logs: { create: "none", read: "all", update: "none", delete: "none" },
    users: { create: "all", read: "all", update: "all", delete: "all" },
    settings: { create: "all", read: "all", update: "all", delete: "all" },
  },
  admin: {
    contacts: { create: "all", read: "all", update: "all", delete: "all" },
    opportunities: { create: "all", read: "all", update: "all", delete: "all" },
    appointments: { create: "all", read: "all", update: "all", delete: "all" },
    campaigns: { create: "all", read: "all", update: "all", delete: "all" },
    tasks: { create: "all", read: "all", update: "all", delete: "all" },
    messages: { create: "all", read: "all", update: "all", delete: "all" },
    orders: { create: "all", read: "all", update: "all", delete: "none" },
    subscriptions: { create: "none", read: "all", update: "none", delete: "none" },
    automations: { create: "all", read: "all", update: "all", delete: "all" },
    reports: { create: "all", read: "all", update: "all", delete: "all" },
    phi_transfers: { create: "all", read: "all", update: "none", delete: "none" },
    audit_logs: { create: "none", read: "all", update: "none", delete: "none" },
    users: { create: "none", read: "all", update: "none", delete: "none" },
    settings: { create: "none", read: "all", update: "own", delete: "none" },
  },
  manager: {
    contacts: { create: "all", read: "all", update: "all", delete: "all" },
    opportunities: { create: "all", read: "all", update: "all", delete: "all" },
    appointments: { create: "all", read: "all", update: "all", delete: "all" },
    campaigns: { create: "none", read: "all", update: "none", delete: "none" },
    tasks: { create: "all", read: "all", update: "all", delete: "all" },
    messages: { create: "all", read: "all", update: "all", delete: "none" },
    orders: { create: "none", read: "all", update: "none", delete: "none" },
    subscriptions: { create: "none", read: "all", update: "none", delete: "none" },
    automations: { create: "none", read: "all", update: "none", delete: "none" },
    reports: { create: "none", read: "team", update: "none", delete: "none" },
    phi_transfers: { create: "none", read: "none", update: "none", delete: "none" },
    audit_logs: { create: "none", read: "own", update: "none", delete: "none" },
    users: { create: "none", read: "none", update: "none", delete: "none" },
    settings: { create: "none", read: "none", update: "none", delete: "none" },
  },
  staff: {
    contacts: { create: "all", read: "own", update: "own", delete: "none" },
    opportunities: { create: "all", read: "own", update: "own", delete: "none" },
    appointments: { create: "all", read: "own", update: "own", delete: "none" },
    campaigns: { create: "none", read: "none", update: "none", delete: "none" },
    tasks: { create: "all", read: "own", update: "own", delete: "none" },
    messages: { create: "all", read: "own", update: "own", delete: "none" },
    orders: { create: "none", read: "all", update: "none", delete: "none" },
    subscriptions: { create: "none", read: "all", update: "none", delete: "none" },
    automations: { create: "none", read: "none", update: "none", delete: "none" },
    reports: { create: "none", read: "own", update: "none", delete: "none" },
    phi_transfers: { create: "none", read: "none", update: "none", delete: "none" },
    audit_logs: { create: "none", read: "none", update: "none", delete: "none" },
    users: { create: "none", read: "none", update: "none", delete: "none" },
    settings: { create: "none", read: "none", update: "none", delete: "none" },
  },
  readonly: {
    contacts: { create: "none", read: "all", update: "none", delete: "none" },
    opportunities: { create: "none", read: "all", update: "none", delete: "none" },
    appointments: { create: "none", read: "all", update: "none", delete: "none" },
    campaigns: { create: "none", read: "all", update: "none", delete: "none" },
    tasks: { create: "none", read: "all", update: "none", delete: "none" },
    messages: { create: "none", read: "none", update: "none", delete: "none" },
    orders: { create: "none", read: "all", update: "none", delete: "none" },
    subscriptions: { create: "none", read: "all", update: "none", delete: "none" },
    automations: { create: "none", read: "none", update: "none", delete: "none" },
    reports: { create: "none", read: "all", update: "none", delete: "none" },
    phi_transfers: { create: "none", read: "none", update: "none", delete: "none" },
    audit_logs: { create: "none", read: "none", update: "none", delete: "none" },
    users: { create: "none", read: "none", update: "none", delete: "none" },
    settings: { create: "none", read: "none", update: "none", delete: "none" },
  },
};

/**
 * Check if a role has permission for a resource + operation.
 * Returns the scope ("all", "own", "team", or "none").
 */
export function getPermissionScope(
  role: Role,
  resource: Resource,
  operation: Operation
): Scope {
  return PERMISSION_MATRIX[role]?.[resource]?.[operation] ?? "none";
}

/**
 * Boolean check: can this role perform this operation on this resource at all?
 */
export function hasPermission(
  role: Role,
  resource: Resource,
  operation: Operation
): boolean {
  return getPermissionScope(role, resource, operation) !== "none";
}

/**
 * Check if the permission is scoped to only the user's own records.
 */
export function isOwnOnly(
  role: Role,
  resource: Resource,
  operation: Operation
): boolean {
  return getPermissionScope(role, resource, operation) === "own";
}

/**
 * Build a MongoDB query filter that enforces scope.
 * For "all" scope: returns empty filter (no restriction).
 * For "own" scope: returns { assignedTo: userId }.
 * For "none": throws (caller should have checked hasPermission first).
 */
export function buildScopeFilter(
  role: Role,
  resource: Resource,
  operation: Operation,
  userId: string
): Record<string, unknown> {
  const scope = getPermissionScope(role, resource, operation);
  switch (scope) {
    case "all":
      return {};
    case "own":
      return { assignedTo: userId };
    case "team":
      // Team scope requires a separate team-member lookup.
      // For now, falls back to own. Extend when team structure is implemented.
      return { assignedTo: userId };
    case "none":
      throw new Error(
        `Forbidden: ${role} cannot ${operation} ${resource}`
      );
  }
}
