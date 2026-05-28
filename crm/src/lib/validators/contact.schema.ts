import { z } from "zod";
import {
  CONTACT_STATUSES,
  CONTACT_SOURCES,
  LIFECYCLE_STAGES,
} from "@/lib/constants";

// Build dynamic zod enums from tenant-derived arrays
const contactStatusEnum = z.string().refine((val) => CONTACT_STATUSES.includes(val), {
  message: "Invalid contact status",
});

const contactSourceEnum = z.string().refine((val) => CONTACT_SOURCES.includes(val), {
  message: "Invalid contact source",
});

export const createContactSchema = z.object({
  firstName: z.string().min(1, "First name is required").max(100).trim(),
  lastName: z.string().min(1, "Last name is required").max(100).trim(),
  email: z.string().email("Invalid email address").toLowerCase().trim(),
  phone: z.string().max(20).trim().optional(),
  source: contactSourceEnum.default("website"),
  campaignId: z.string().optional(),
  assignedTo: z.string().optional(),
  tags: z.array(z.string().trim()).default([]),
  notes: z.string().max(5000).default(""),
  consentGiven: z.boolean().default(false),
});

export const updateContactSchema = z.object({
  firstName: z.string().min(1).max(100).trim().optional(),
  lastName: z.string().min(1).max(100).trim().optional(),
  email: z.string().email().toLowerCase().trim().optional(),
  phone: z.string().max(20).trim().optional(),
  status: contactStatusEnum.optional(),
  source: contactSourceEnum.optional(),
  campaignId: z.string().nullable().optional(),
  assignedTo: z.string().nullable().optional(),
  tags: z.array(z.string().trim()).optional(),
  notes: z.string().max(5000).optional(),
  consentGiven: z.boolean().optional(),
  lifecycleStage: z.enum(LIFECYCLE_STAGES).optional(),
  customData: z.record(z.unknown()).optional(),
});

export const contactQuerySchema = z.object({
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(100).default(25),
  status: contactStatusEnum.optional(),
  source: contactSourceEnum.optional(),
  assignedTo: z.string().optional(),
  search: z.string().max(200).optional(),
  sortBy: z
    .enum(["firstName", "lastName", "email", "status", "createdAt", "lastContactedAt"])
    .default("createdAt"),
  sortOrder: z.enum(["asc", "desc"]).default("desc"),
  tags: z.string().optional(), // comma-separated
  lifecycleStage: z.enum(LIFECYCLE_STAGES).optional(),
});

export type CreateContactInput = z.infer<typeof createContactSchema>;
export type UpdateContactInput = z.infer<typeof updateContactSchema>;
export type ContactQuery = z.infer<typeof contactQuerySchema>;
