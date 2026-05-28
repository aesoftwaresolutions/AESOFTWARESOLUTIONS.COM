import { z } from "zod";
import { PC_PARTNER_STATUSES } from "@/lib/constants";

/**
 * PC Partner webhook payload.
 * Strictly validated: NO PHI fields allowed.
 * Only operational status codes per integration contract.
 */
export const pcPartnerWebhookSchema = z
  .object({
    event: z.literal("status_update"),
    tenantContactId: z.string().min(1),
    status: z.enum(PC_PARTNER_STATUSES),
    timestamp: z.string().datetime(),
    metadata: z
      .object({
        trackingNumber: z.string().optional(),
        carrier: z.string().optional(),
        effectiveDate: z.string().datetime().optional(),
      })
      .optional(),
  })
  .strict(); // Reject any extra fields — prevents PHI leakage

export type PCPartnerWebhookPayload = z.infer<typeof pcPartnerWebhookSchema>;
