/**
 * Application Constants
 *
 * All industry-specific values are derived from the tenant configuration.
 * Generic constants (roles, task statuses, etc.) remain hardcoded here
 * since they are universal across all deployments.
 */

import {
  tenantConfig,
  getStatusIds,
  getStageIds,
  getTierIds,
  getSourceIds,
  getOrderTypeIds,
  getTransferTypeIds,
  getTemperatureIds,
  getTierLabelMap,
  getTierFullLabelMap,
  getTierPriceMap,
  getAppointmentTypeIds,
} from "@/lib/tenant";

// ── Tenant-Derived Constants ──────────────────────────────────────────────

/** Contact statuses — derived from tenant config */
export const CONTACT_STATUS = getStatusIds();
export type ContactStatus = string;

/** Lead temperatures — derived from tenant config */
export const LEAD_TEMPERATURES = getTemperatureIds();
export type LeadTemperature = string;

/** Pipeline stages — derived from tenant config */
export const PIPELINE_STAGES = getStageIds();
export type PipelineStage = string;

/** Subscription tiers — derived from tenant config */
export const SUBSCRIPTION_TIERS = getTierIds();
export type SubscriptionTier = string;

/** Tier labels (tier ID → display label with price) */
export const TIER_LABELS: Record<string, string> = getTierFullLabelMap();

/** Tier short labels (tier ID → short label) */
export const TIER_SHORT_LABELS: Record<string, string> = getTierLabelMap();

/** Tier prices in cents */
export const TIER_PRICES: Record<string, number> = getTierPriceMap();

/** Contact sources — derived from tenant config */
export const CONTACT_SOURCES = getSourceIds();
export type ContactSource = string;

/** Order types — derived from tenant config */
export const ORDER_TYPES = getOrderTypeIds();

/** PHI transfer types — derived from tenant config (empty if PHI disabled) */
export const PHI_TRANSFER_TYPES = getTransferTypeIds();
export type PHITransferType = string;

/** PHI TTL — derived from tenant config */
export const PHI_TTL_HOURS = tenantConfig.compliance.ttlHours;
export const PHI_TTL_MS = PHI_TTL_HOURS * 60 * 60 * 1000;

// ── US States (generic, not industry-specific) ────────────────────────────

export const STATES = [
  "AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "FL", "GA",
  "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD",
  "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ",
  "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC",
  "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY",
] as const;

// ── Universal Constants (same across all industries) ──────────────────────

export const USER_ROLES = ["superadmin", "admin", "manager", "staff", "readonly"] as const;
export type UserRole = (typeof USER_ROLES)[number];
export type Role = UserRole;

export const ORDER_STATUS = ["pending", "ordered", "shipped", "delivered", "completed"] as const;

export const TASK_PRIORITY = ["low", "medium", "high", "urgent"] as const;
export const TASK_STATUS = ["todo", "in_progress", "done", "cancelled"] as const;

export const APPOINTMENT_STATUS = ["scheduled", "confirmed", "completed", "cancelled", "no_show"] as const;

export const APPOINTMENT_TYPES = getAppointmentTypeIds();

export const LIFECYCLE_STAGES = ["prospect", "lead", "opportunity", "customer", "churned"] as const;
export type LifecycleStage = (typeof LIFECYCLE_STAGES)[number];

// ── Messaging (universal) ─────────────────────────────────────────────────

export const MESSAGE_CHANNELS = ["email", "sms", "in_app", "instagram_dm", "facebook_dm", "twitter_dm"] as const;
export type MessageChannel = (typeof MESSAGE_CHANNELS)[number];

export const MESSAGE_DIRECTIONS = ["inbound", "outbound"] as const;
export type MessageDirection = (typeof MESSAGE_DIRECTIONS)[number];

// ── Campaigns (universal) ─────────────────────────────────────────────────

export const CAMPAIGN_TYPES = [
  "email", "sms", "google_ads", "facebook_ads",
  "instagram", "facebook_organic", "twitter", "linkedin", "tiktok",
  "referral", "organic",
] as const;
export type CampaignType = (typeof CAMPAIGN_TYPES)[number];

export const CAMPAIGN_STATUSES = ["draft", "active", "paused", "completed"] as const;
export type CampaignStatus = (typeof CAMPAIGN_STATUSES)[number];

// ── Social Media (universal) ──────────────────────────────────────────────

export const SOCIAL_PLATFORMS = ["instagram", "facebook", "twitter", "linkedin", "tiktok"] as const;
export type SocialPlatform = (typeof SOCIAL_PLATFORMS)[number];

export const SOCIAL_POST_STATUSES = ["draft", "scheduled", "published", "failed"] as const;
export type SocialPostStatus = (typeof SOCIAL_POST_STATUSES)[number];

// ── Automations (universal) ───────────────────────────────────────────────

export const AUTOMATION_TRIGGER_TYPES = [
  "contact_created",
  "contact_status_changed",
  "appointment_scheduled",
  "appointment_completed",
  "order_created",
  "subscription_created",
  "tag_added",
  "form_submitted",
] as const;
export type AutomationTriggerType = (typeof AUTOMATION_TRIGGER_TYPES)[number];

// ── Webhooks (universal) ──────────────────────────────────────────────────

export const WEBHOOK_SOURCES = ["pc_partner", "stripe", "sendgrid", "twilio", "meta", "twitter"] as const;
export type WebhookSource = (typeof WEBHOOK_SOURCES)[number];

export const PC_PARTNER_STATUSES = ["received", "processing", "completed", "rejected"] as const;
export type PCPartnerStatus = (typeof PC_PARTNER_STATUSES)[number];

// ── PHI Transfer (conditional on tenant config) ───────────────────────────

export const PHI_TRANSFER_STATUSES = ["pending", "transferred", "confirmed", "purged"] as const;
export type PHITransferStatus = (typeof PHI_TRANSFER_STATUSES)[number];

// ── Audit Actions (universal) ─────────────────────────────────────────────

export const AUDIT_ACTIONS = {
  PHI_TRANSFER_INITIATE: "phi_transfer.initiate",
  PHI_TRANSFER_CONFIRM: "phi_transfer.confirm",
  PHI_TRANSFER_EXPIRE: "phi_transfer.expire",
  CONTACT_CREATE: "contact.create",
  CONTACT_UPDATE: "contact.update",
  CONTACT_DELETE: "contact.delete",
} as const;

// ── Backward-Compat Aliases ───────────────────────────────────────────────

/** @deprecated Use CONTACT_STATUS */
export const CONTACT_STATUSES = CONTACT_STATUS;

/** @deprecated Use PIPELINE_STAGES */
export const OPPORTUNITY_STAGES = PIPELINE_STAGES;
