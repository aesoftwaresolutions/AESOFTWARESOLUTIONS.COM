import { NextRequest, NextResponse } from "next/server";
import { connectDB } from "@/lib/db/connection";
import { Contact, Opportunity, AuditLog } from "@/lib/db/models";
import { TIER_PRICES } from "@/lib/constants";
import { tenantConfig } from "@/lib/tenant";

// ── CORS ──────────────────────────────────────────────────────────────────────
// Allow any origin — this endpoint is called server-to-server from MVC's
// Vercel functions (CORS is irrelevant there) and is not authentication-gated.
function corsHeaders() {
  return {
    "Access-Control-Allow-Origin": "*",
    "Access-Control-Allow-Methods": "POST, OPTIONS",
    "Access-Control-Allow-Headers": "Content-Type, x-api-key",
  };
}

// ── State normalization ───────────────────────────────────────────────────────
// MVC sends full state names ("California"); the Contact model validates against
// 2-letter STATES abbreviations. Normalize before saving.
const STATE_FULL_TO_ABBR: Record<string, string> = {
  Alabama: "AL", Alaska: "AK", Arizona: "AZ", Arkansas: "AR",
  California: "CA", Colorado: "CO", Connecticut: "CT", Delaware: "DE",
  Florida: "FL", Georgia: "GA", Hawaii: "HI", Idaho: "ID",
  Illinois: "IL", Indiana: "IN", Iowa: "IA", Kansas: "KS",
  Kentucky: "KY", Louisiana: "LA", Maine: "ME", Maryland: "MD",
  Massachusetts: "MA", Michigan: "MI", Minnesota: "MN", Mississippi: "MS",
  Missouri: "MO", Montana: "MT", Nebraska: "NE", Nevada: "NV",
  "New Hampshire": "NH", "New Jersey": "NJ", "New Mexico": "NM",
  "New York": "NY", "North Carolina": "NC", "North Dakota": "ND",
  Ohio: "OH", Oklahoma: "OK", Oregon: "OR", Pennsylvania: "PA",
  "Rhode Island": "RI", "South Carolina": "SC", "South Dakota": "SD",
  Tennessee: "TN", Texas: "TX", Utah: "UT", Vermont: "VT",
  Virginia: "VA", Washington: "WA", "West Virginia": "WV",
  Wisconsin: "WI", Wyoming: "WY",
};

function normalizeState(state?: string): string {
  if (!state) return "";
  const trimmed = state.trim();
  if (trimmed.length === 2) return trimmed.toUpperCase(); // already abbreviated
  return STATE_FULL_TO_ABBR[trimmed] ?? "";
}

export async function OPTIONS() {
  return new NextResponse(null, { status: 204, headers: corsHeaders() });
}

export async function POST(req: NextRequest) {
  const headers = corsHeaders();

  await connectDB();
  const body = await req.json();
  const {
    firstName, lastName, email, phone, state,
    source, utmSource, utmMedium, utmCampaign,
    honeypot,
    marketingConsent,
    // Assessment scoring fields (non-PHI)
    assessmentId,
    leadScore,
    leadTemperature,
    categoryInterests,
    primaryPathway,
    tier,
    readiness,
    timeline,
  } = body;

  // Normalize full state name → 2-letter abbreviation
  const normalizedState = normalizeState(state);

  // Honeypot spam check
  if (honeypot) return NextResponse.json({ success: true }, { headers });

  if (!firstName || !lastName || !email) {
    return NextResponse.json({ error: "Name and email required" }, { status: 400, headers });
  }

  const assessmentFields = {
    ...(leadScore      != null ? { leadScore }      : {}),
    ...(leadTemperature ? { leadTemperature } : {}),
    ...(tier            ? { recommendedTier: tier } : {}),
  };

  const customData = {
    ...(assessmentId   ? { assessmentId }   : {}),
    ...(primaryPathway  ? { primaryPathway }  : {}),
    ...(categoryInterests ? { interests: categoryInterests } : {}),
    ...(readiness   ? { readiness }   : {}),
    ...(timeline    ? { timeline }    : {}),
    ...(marketingConsent != null ? { marketingConsent: Boolean(marketingConsent) } : {}),
  };

  // Check existing
  let contact = await Contact.findOne({ email: email.toLowerCase() });
  if (contact) {
    await Contact.updateOne(
      { _id: contact._id },
      {
        $set: {
          phone: phone || contact.phone,
          state: normalizedState || contact.state,
          ...assessmentFields,
          customData: { ...(contact.customData || {}), ...customData },
        },
      }
    );
    return NextResponse.json({ success: true, message: "We already have your info", contactId: contact._id }, { headers });
  }

  contact = await Contact.create({
    firstName,
    lastName,
    email: email.toLowerCase(),
    phone,
    state: normalizedState,
    status: "lead",
    source: source || "website",
    utmSource,
    utmMedium,
    utmCampaign,
    ...assessmentFields,
    customData,
  });

  const defaultTier = tenantConfig.billing.tiers[0]?.id || "basic";
  const defaultPrice = tenantConfig.billing.tiers[0]?.priceInCents || 0;
  const opportunityValue = tier ? (TIER_PRICES[tier] ?? defaultPrice) : defaultPrice;
  await Opportunity.create({ contactId: contact._id, stage: "new_lead", tier: tier || undefined, value: opportunityValue });

  AuditLog.create({
    action: "lead.captured",
    resource: "contact",
    resourceId: contact._id.toString(),
    details: { source, utmSource, utmMedium, utmCampaign, assessmentId, leadScore, tier },
  }).catch(console.error);

  return NextResponse.json({ success: true, contactId: contact._id }, { status: 201, headers });
}
