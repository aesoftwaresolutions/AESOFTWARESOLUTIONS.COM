import { z } from "zod";
import { OPPORTUNITY_STAGES } from "@/lib/constants";

export const createOpportunitySchema = z.object({
  contactId: z.string().min(1, "Contact is required"),
  title: z.string().min(1, "Title is required").max(200).trim(),
  stage: z.string().refine((val) => OPPORTUNITY_STAGES.includes(val), { message: "Invalid stage" }).default("new_lead"),
  value: z.number().min(0).optional(),
  assignedTo: z.string().optional(),
  expectedCloseDate: z.string().datetime().optional(),
  notes: z.string().max(5000).default(""),
});

export const updateOpportunitySchema = z.object({
  title: z.string().min(1).max(200).trim().optional(),
  stage: z.string().refine((val) => OPPORTUNITY_STAGES.includes(val), { message: "Invalid stage" }).optional(),
  value: z.number().min(0).nullable().optional(),
  lostReason: z.string().max(500).optional(),
  assignedTo: z.string().nullable().optional(),
  expectedCloseDate: z.string().datetime().nullable().optional(),
  notes: z.string().max(5000).optional(),
});

export const opportunityQuerySchema = z.object({
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(100).default(25),
  stage: z.string().refine((val) => OPPORTUNITY_STAGES.includes(val), { message: "Invalid stage" }).optional(),
  assignedTo: z.string().optional(),
  contactId: z.string().optional(),
  sortBy: z.enum(["title", "stage", "value", "createdAt"]).default("createdAt"),
  sortOrder: z.enum(["asc", "desc"]).default("desc"),
});

export type CreateOpportunityInput = z.infer<typeof createOpportunitySchema>;
export type UpdateOpportunityInput = z.infer<typeof updateOpportunitySchema>;
export type OpportunityQuery = z.infer<typeof opportunityQuerySchema>;
